// Global variable to store user's allowed kecamatan and kelurahan
let userKelurahanData = null;
let isRestrictedUser = false;

// Fetch user's kelurahan and kecamatan from API
async function fetchUserKelurahan() {
    try {
        const response = await fetch('/api/user-kelurahan', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch user kelurahan data');
        }

        const result = await response.json();
        
        if (result.success) {
            userKelurahanData = result.data;
            isRestrictedUser = !result.data.is_superadmin;
            // console.log('User Kelurahan Data:', userKelurahanData);
            // console.log('Is Restricted User (ADMIN):', isRestrictedUser);
        } else {
            console.error('Failed to load user kelurahan:', result.message);
            isRestrictedUser = false;
        }
    } catch (error) {
        console.error('Error fetching user kelurahan:', error);
        isRestrictedUser = false;
    }
}

$(document).ready(function() {
    // Function to show error toast
    function showErrorToast(message) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'alert border-b-4 border-error fixed capitalize font-medium top-0 right-0 m-5 w-fit animate-fade-in z-20 max-w-md';
        toast.innerHTML = `
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left text-sm normal-case">${message}</div>
            </div>
            <button type="button" class="close-alert btn btn-ghost btn-square btn-sm">
                <i class="ri-close-line"></i>
            </button>
        `;
        
        // Add to body
        document.body.appendChild(toast);
        
        // Add click handler for close button
        const closeBtn = toast.querySelector('.close-alert');
        closeBtn.addEventListener('click', function() {
            toast.remove();
        });
        
        // Auto remove after 8 seconds (longer for detailed message)
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 8000);
    }

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        // Only validate for update action, not duplicate
        if (e.submitter && e.submitter.value === 'duplicate') {
            return true; // Allow duplicate without validation
        }
        
        e.preventDefault();
        
        const requiredFields = [
            { name: 'subjek', label: 'Nama Sekolah' },
            { name: 'jenis_sekolah', label: 'Jenis Sekolah' },
            { name: 'alamat', label: 'Alamat' },
            { name: 'kecamatan', label: 'Kecamatan' },
            { name: 'kelurahan', label: 'Kelurahan' },
            { name: 'pengelola', label: 'Kepala Sekolah/NIP' },
            { name: 'kontak', label: 'Kontak yang Dapat Dihubungi' },
            { name: 'u004', label: 'Jumlah Siswa' },
            { name: 'u005', label: 'Jumlah Guru' },
            { name: 'u006', label: 'Nomor Pokok Sekolah Nasional' },
            { name: 'nama-pemeriksa', label: 'Nama Pemeriksa' },
            { name: 'instansi-pemeriksa', label: 'Instansi Pemeriksa' },
            { name: 'tanggal-penilaian', label: 'Tanggal Penilaian' },
            { name: 'koordinat', label: 'Titik GPS' },
            { name: 'status-operasi', label: 'Status Operasi' },
            { name: 'catatan-lain', label: 'Hasil IKL' },
            { name: 'rencana-tindak-lanjut', label: 'Rencana Tindak Lanjut' }
        ];

        // Hasil pengukuran fields
        const hasilPengukuranFields = [
            { name: 'hpp001', label: 'Hasil Pengukuran Pencahayaan di Ruang Kelas' },
            { name: 'hpp002', label: 'Hasil Pengukuran Pencahayaan di Ruang Perpustakaan' },
            { name: 'hpp003', label: 'Hasil Pengukuran Pencahayaan di Ruang Laboratorium' },
            { name: 'hpp004', label: 'Hasil Pengukuran Kelembaban' },
            { name: 'hpp005', label: 'Hasil Pengukuran Kebisingan' },
            { name: 'hpp006', label: 'Hasil Pengukuran PM 2,5' },
            { name: 'hpp007', label: 'Hasil Pengukuran PM10' }
        ];

        let hasErrors = false;
        let errorMessages = [];
        
        // Clear previous error states
        document.querySelectorAll('.input-error, .select-error, .textarea-error').forEach(el => {
            el.classList.remove('input-error', 'select-error', 'textarea-error');
        });
        document.querySelectorAll('.text-error').forEach(el => el.remove());

        // Validate required fields
        [...requiredFields, ...hasilPengukuranFields].forEach(field => {
            const element = document.getElementById(field.name) || document.querySelector(`[name="${field.name}"]`);
            if (element) {
                const value = element.value.trim();
                if (!value || value === '' || value === 'Pilih Kecamatan' || value === 'Pilih Kelurahan' || value === 'Pilih Jenis Sekolah' || value === 'Pilih Status') {
                    hasErrors = true;
                    errorMessages.push(field.label);
                    
                    // Add error class based on element type
                    if (element.tagName === 'SELECT') {
                        element.classList.add('select-error');
                    } else if (element.tagName === 'TEXTAREA') {
                        element.classList.add('textarea-error');
                    } else {
                        element.classList.add('input-error');
                    }
                    
                    // Add error message
                    if (!element.parentNode.querySelector('.text-error')) {
                        const errorSpan = document.createElement('span');
                        errorSpan.className = 'text-error text-xs';
                        errorSpan.textContent = `${field.label} harus diisi`;
                        element.parentNode.appendChild(errorSpan);
                    }
                }
            }
        });

        // Validate koordinat format (lat, long)
        const koordinat = document.getElementById('koordinat');
        if (koordinat && koordinat.value) {
            const koordinatPattern = /^-?\d+\.?\d*,\s*-?\d+\.?\d*$/;
            if (!koordinatPattern.test(koordinat.value.trim())) {
                hasErrors = true;
                errorMessages.push('Format Koordinat tidak valid');
                koordinat.classList.add('input-error');
                if (!koordinat.parentNode.querySelector('.text-error')) {
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'text-error text-xs';
                    errorSpan.textContent = 'Format koordinat harus: latitude, longitude (contoh: -6.324667, 106.891268)';
                    koordinat.parentNode.appendChild(errorSpan);
                }
            }
        }

        if (hasErrors) {
            // Scroll to first error
            const firstError = document.querySelector('.input-error, .select-error, .textarea-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Create detailed error message
            let errorMessage = 'Field yang belum diisi:\n';
            errorMessages.forEach((field, index) => {
                errorMessage += `${index + 1}. ${field}\n`;
            });
            
            // Show toast with specific errors
            showErrorToast(errorMessage.replace(/\n/g, '<br>'));
            return false;
        }
        
        // Show loading state
        const submitBtn = e.submitter;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Menyimpan...';
        }
        
        // Submit the form
        this.submit();
    });

    // District and Village population logic for edit form
    let kecVal = window.formData ? window.formData.kecamatan : '';
    let kelVal = window.formData ? window.formData.kelurahan : '';
    
    // console.log('Edit form loaded with:', { kecVal, kelVal });

    // Fetch user data first
    fetchUserKelurahan().then(() => {
        // If user is SUPERADMIN, use the old logic with API
        if (!isRestrictedUser) {
            loadDataFromAPI(kecVal, kelVal);
        } else {
            // If user is ADMIN, use restricted data from database
            loadRestrictedData(kecVal, kelVal);
        }
    });

    // Function for SUPERADMIN - uses external API
    function loadDataFromAPI(kecVal, kelVal) {
        // Function to populate kecamatan dropdown
        function populateKecamatan() {
            if (typeof kecamatan !== 'undefined' && kecamatan && kecamatan.length > 0) {
                // console.log('Populating kecamatan with', kecamatan.length, 'items');
                
                let options = '<option value="">Pilih Kecamatan</option>';
                kecamatan.forEach((el) => {
                    let selected = (kecVal && kecVal == el.name) ? 'selected' : '';
                    options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                    if (selected) {
                        // console.log('Pre-selecting kecamatan:', el.name);
                    }
                });

                $("#kec").html(options);
                
                // Trigger change event to load kelurahan if kecamatan is pre-selected
                if (kecVal) {
                    // console.log('Triggering kelurahan load for pre-selected kecamatan:', kecVal);
                    setTimeout(() => {
                        populateKelurahan(kecVal);
                    }, 100);
                }
                
                return true;
            }
            return false;
        }

        // Function to populate kelurahan dropdown
        function populateKelurahan(selectedKecamatan) {
            // console.log('Loading kelurahan for:', selectedKecamatan, 'Expected kelurahan:', kelVal);
            
            let selectedKec = kecamatan.find((el) => el.name == selectedKecamatan);
            if (!selectedKec || !selectedKec.id) {
                console.error('Kecamatan not found:', selectedKecamatan);
                $("#kel").html('<option value="">Kecamatan tidak ditemukan</option>');
                return;
            }

            // console.log('Found kecamatan ID:', selectedKec.id);

            // Show loading
            $("#kel").html('<option value="">Memuat kelurahan...</option>');
            $("#kel").prop('disabled', true);

            fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
                .then((response) => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then((villages) => {
                    // console.log('Villages loaded:', villages.length, 'items');
                    
                    let kelOptions = '<option value="">Pilih Kelurahan</option>';
                    villages.forEach((el) => {
                        let selected = (kelVal && kelVal == el.name) ? 'selected' : '';
                        kelOptions += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                        if (selected) {
                            // console.log('Pre-selecting kelurahan:', el.name);
                        }
                    });
                    
                    $("#kel").html(kelOptions);
                    $("#kel").prop('disabled', false);
                    // console.log('Kelurahan dropdown populated successfully');
                })
                .catch((error) => {
                    console.error('Error loading villages:', error);
                    $("#kel").html('<option value="">Gagal memuat kelurahan</option>');
                    $("#kel").prop('disabled', false);
                });
        }

        // Wait for kecamatan data to be loaded
        let checkKec = setInterval(function() {
            if (populateKecamatan()) {
                clearInterval(checkKec);
                // console.log('Kecamatan data loaded and populated');
            } else {
                // console.log('Waiting for kecamatan data...');
            }
        }, 500);

        // Handle kecamatan change event
        $("#kec").on('change', function() {
            let selectedValue = $(this).val();
            // console.log('Kecamatan changed to:', selectedValue);
            
            if (selectedValue) {
                populateKelurahan(selectedValue);
            } else {
                $("#kel").html('<option value="">Pilih Kelurahan</option>');
                $("#kel").prop('disabled', true);
            }
        });

        // Fallback timeout - give more time for edit forms
        setTimeout(() => {
            if ($("#kec option").length <= 1) {
                console.warn('Kecamatan data still not loaded after 15 seconds, trying fallback');
                // Try to preserve existing values
                if (kecVal) {
                    $("#kec").html(`<option value="">Pilih Kecamatan</option><option value="${kecVal}" selected>${kecVal}</option>`);
                }
                if (kelVal) {
                    $("#kel").html(`<option value="">Pilih Kelurahan</option><option value="${kelVal}" selected>${kelVal}</option>`);
                    $("#kel").prop('disabled', false);
                }
            }
        }, 15000);
        
        // Additional check for immediate display of existing values
        setTimeout(() => {
            if (kecVal && $("#kec option:selected").val() !== kecVal) {
                // console.log('Ensuring kecamatan value is preserved:', kecVal);
                let hasOption = $("#kec option[value='" + kecVal + "']").length > 0;
                if (!hasOption) {
                    $("#kec").append(`<option value="${kecVal}" selected>${kecVal}</option>`);
                } else {
                    $("#kec").val(kecVal);
                }
            }
            
            if (kelVal && $("#kel option:selected").val() !== kelVal) {
                // console.log('Ensuring kelurahan value is preserved:', kelVal);
                let hasOption = $("#kel option[value='" + kelVal + "']").length > 0;
                if (!hasOption) {
                    $("#kel").append(`<option value="${kelVal}" selected>${kelVal}</option>`);
                    $("#kel").prop('disabled', false);
                } else {
                    $("#kel").val(kelVal);
                }
            }
        }, 2000);
    }

    // Function for ADMIN - uses database data
    function loadRestrictedData(kecVal, kelVal) {
        // console.log('Loading restricted data for ADMIN user');
        
        // Populate kecamatan from database
        let kecOptions = '<option value="">Pilih Kecamatan</option>';
        userKelurahanData.kecamatan.forEach((kec) => {
            let selected = (kecVal && kecVal == kec) ? 'selected' : '';
            kecOptions += `<option value="${kec}" ${selected}>${kec}</option>`;
        });
        $("#kec").html(kecOptions);

        // Populate kelurahan based on kecVal
        if (kecVal && userKelurahanData.kelurahan_by_kecamatan[kecVal]) {
            updateRestrictedKelurahanDropdown(kecVal, kelVal);
        }

        // Handle kecamatan change - update kelurahan based on database
        $("#kec").off('change').on('change', function() {
            let selectedKec = $(this).val();
            if (selectedKec) {
                updateRestrictedKelurahanDropdown(selectedKec, '');
            } else {
                $("#kel").html('<option value="">Pilih Kelurahan</option>');
                $("#kel").prop('disabled', true);
            }
        });
    }

    // Helper function to update kelurahan dropdown for ADMIN
    function updateRestrictedKelurahanDropdown(selectedKec, selectedKel) {
        // console.log('Updating kelurahan for restricted user, kecamatan:', selectedKec);
        
        if (!userKelurahanData.kelurahan_by_kecamatan[selectedKec]) {
            $("#kel").html('<option value="">Tidak ada kelurahan tersedia</option>');
            $("#kel").prop('disabled', true);
            return;
        }

        let kelOptions = '<option value="">Pilih Kelurahan</option>';
        userKelurahanData.kelurahan_by_kecamatan[selectedKec].forEach((kel) => {
            let selected = (selectedKel && selectedKel == kel) ? 'selected' : '';
            kelOptions += `<option value="${kel}" ${selected}>${kel}</option>`;
        });
        
        $("#kel").html(kelOptions);
        $("#kel").prop('disabled', false);
    }

    // Duplicate validation function
    window.validateDuplicate = function() {
        const kecamatan = document.getElementById('kec').value;
        const kelurahan = document.getElementById('kel').value;
        
        if (!kecamatan || kecamatan === '' || kecamatan === 'Pilih Kecamatan') {
            alert('Kecamatan harus dipilih sebelum melakukan duplikasi penilaian');
            return false;
        }
        
        if (!kelurahan || kelurahan === '' || kelurahan === 'Pilih Kelurahan') {
            alert('Kelurahan harus dipilih sebelum melakukan duplikasi penilaian');
            return false;
        }
        
        return true;
    };

    // Auto-calculate on page load if issued date already filled
    document.addEventListener('DOMContentLoaded', function() {});
});