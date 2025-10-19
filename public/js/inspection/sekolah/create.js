let userKelurahanData = null;
let isRestrictedUser = false;
let dataLoadingPromise = null;

async function fetchUserKelurahan() {
    if (typeof window.isAuthenticated === 'undefined' || !window.isAuthenticated) {
        isRestrictedUser = false;
        return;
    }

    try {
        const response = await fetch('/api/user-kelurahan', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (!response.ok) {
            if (response.status === 401) {
                isRestrictedUser = false;
                return;
            }
            throw new Error('Failed to fetch user kelurahan data');
        }
        const result = await response.json();
        if (result.success) {
            userKelurahanData = result.data;
            isRestrictedUser = !result.data.is_superadmin;
            initializeKecamatanDropdown();
        } else {
            isRestrictedUser = false;
        }
    } catch (error) {
        isRestrictedUser = false;
    }
}

function initializeKecamatanDropdown() {
    const kecamatanSelect = document.getElementById('kecamatan');
    const kelurahanSelect = document.getElementById('kelurahan');
    if (!kecamatanSelect || !kelurahanSelect) {
        return;
    }
    if (userKelurahanData && userKelurahanData.is_superadmin) {
        return;
    }
    if (userKelurahanData && !userKelurahanData.is_superadmin) {
        populateRestrictedKecamatan(kecamatanSelect, kelurahanSelect);
    }
}

function populateRestrictedKecamatan(kecamatanSelect, kelurahanSelect) {
    kecamatanSelect.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Kecamatan';
    placeholder.disabled = true;
    placeholder.selected = true;
    kecamatanSelect.appendChild(placeholder);
    
    let autoSelectedKecamatan = null;
    if (userKelurahanData.kecamatan && userKelurahanData.kecamatan.length > 0) {
        userKelurahanData.kecamatan.forEach(kec => {
            const option = document.createElement('option');
            option.value = kec;
            option.textContent = kec;
            kecamatanSelect.appendChild(option);
        });
        console.log(`Populated ${userKelurahanData.kecamatan.length} kecamatan options`);
        if (userKelurahanData.kecamatan.length === 1) {
            autoSelectedKecamatan = userKelurahanData.kecamatan[0];
            console.log(`Auto-selecting kecamatan: ${autoSelectedKecamatan}`);
        }
    } else {
        console.warn('No kecamatan data available for this user');
    }
    
    kelurahanSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan Terlebih Dahulu</option>';
    kelurahanSelect.disabled = true;
    
    const newKecamatanSelect = kecamatanSelect.cloneNode(true);
    kecamatanSelect.parentNode.replaceChild(newKecamatanSelect, kecamatanSelect);
    newKecamatanSelect.addEventListener('change', function() {
        updateRestrictedKelurahanDropdown(this.value, kelurahanSelect);
    });
    
    if (autoSelectedKecamatan) {
        newKecamatanSelect.value = autoSelectedKecamatan;
        updateRestrictedKelurahanDropdown(autoSelectedKecamatan, kelurahanSelect);
    }
}

function updateRestrictedKelurahanDropdown(selectedKecamatan, kelurahanSelect) {
    kelurahanSelect.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Kelurahan';
    placeholder.disabled = true;
    placeholder.selected = true;
    kelurahanSelect.appendChild(placeholder);
    
    if (selectedKecamatan && userKelurahanData.kelurahan_by_kecamatan[selectedKecamatan]) {
        const kelurahanList = userKelurahanData.kelurahan_by_kecamatan[selectedKecamatan];
        kelurahanList.forEach(kel => {
            const option = document.createElement('option');
            option.value = kel;
            option.textContent = kel;
            kelurahanSelect.appendChild(option);
        });
        kelurahanSelect.disabled = false;
        console.log(`Populated ${kelurahanList.length} kelurahan options for ${selectedKecamatan}`);
    } else {
        kelurahanSelect.disabled = true;
        console.warn(`No kelurahan data for ${selectedKecamatan}`);
    }
}

window.shouldRestrictKelurahan = function() { return isRestrictedUser; };
window.waitForUserData = function() { return dataLoadingPromise; };
dataLoadingPromise = fetchUserKelurahan();

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
                const value = (element.value || '').trim();
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
            if (!koordinatPattern.test((koordinat.value || '').trim())) {
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
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Menyimpan...';
        }
        
        // Submit the form
        this.submit();
    });

    // Auto-calculate on page load if issued date already filled
    document.addEventListener('DOMContentLoaded', function() {});
});