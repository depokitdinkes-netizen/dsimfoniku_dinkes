// Global variable to store user's allowed kecamatan and kelurahan
let userKelurahanData = null;
let isRestrictedUser = false;

// Fetch user's kelurahan and kecamatan from API
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
        } else {
            isRestrictedUser = false;
        }
    } catch (error) {
        isRestrictedUser = false;
    }
}

$(document).ready(function() {
    // Get form data from window object (passed from Blade template)
    const formData = window.pasarEditData || {};
    let kecVal = formData.kecamatan || "";
    let kelVal = formData.kelurahan || "";
    
    console.log('Edit form initialized with:', { kecVal, kelVal });

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
     
     // Form submission handler to ensure kecamatan and kelurahan values are preserved
     $('form').on('submit', function(e) {
         const kecSelect = $('#kec');
         const kecBackup = $('#kecamatan-backup');
         const kelSelect = $('#kel');
         const kelBackup = $('#kelurahan-backup');
         
         // If kecamatan dropdown is empty or not loaded, use backup value
         if (!kecSelect.val() || kecSelect.val() === '' || kecSelect.val() === 'Pilih Kecamatan') {
             if (kecBackup.val()) {
                 console.log('Using backup kecamatan value:', kecBackup.val());
                 // Create a temporary hidden input with the backup value
                 $('<input>').attr({
                     type: 'hidden',
                     name: 'kecamatan',
                     value: kecBackup.val()
                 }).appendTo(this);
             }
         }
         
         // If kelurahan dropdown is empty or not loaded, use backup value
         if (!kelSelect.val() || kelSelect.val() === '' || kelSelect.val() === 'Pilih Kelurahan') {
             if (kelBackup.val()) {
                 console.log('Using backup kelurahan value:', kelBackup.val());
                 // Create a temporary hidden input with the backup value
                 $('<input>').attr({
                     type: 'hidden',
                     name: 'kelurahan',
                     value: kelBackup.val()
                 }).appendTo(this);
             }
         }
     });
});

// Load data from API for SUPERADMIN
function loadDataFromAPI(kecVal, kelVal) {
    // Function to populate kecamatan dropdown with existing value
    function populateKecamatan() {
        if (kecamatan.length > 0) {
            let options = '<option value="">Pilih Kecamatan</option>';
            
            kecamatan.forEach((el) => {
                const selected = kecVal == el.name ? 'selected' : '';
                options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
            });
            
            $("#kec").html(options);
            $("#kec").prop('disabled', false);
            
            // If there's a pre-selected kecamatan, load its kelurahan
            if (kecVal) {
                populateKelurahan(kecVal);
            }
            
            return true;
        }
        return false;
    }
    
    // Function to populate kelurahan dropdown
    function populateKelurahan(kecamatanName) {
        const selectedKec = kecamatan.find((el) => el.name === kecamatanName);
        
        if (!selectedKec) {
            console.error('Kecamatan not found:', kecamatanName);
            $("#kel").html('<option value="">Kecamatan tidak ditemukan</option>');
            $("#kel").prop('disabled', true);
            return;
        }
        
        $("#kel").html('<option value="">Memuat kelurahan...</option>');
        $("#kel").prop('disabled', true);
        
        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((villages) => {
                let options = '<option value="">Pilih Kelurahan</option>';
                
                villages.forEach((el) => {
                    const selected = kelVal == el.name ? 'selected' : '';
                    options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                });
                
                $("#kel").html(options);
                $("#kel").prop('disabled', false);
                
                console.log('Kelurahan loaded successfully:', villages.length, 'items');
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
            console.log('Kecamatan data loaded and populated');
        } else {
            console.log('Waiting for kecamatan data...');
        }
    }, 500)

    // Handle kecamatan change event
    $("#kec").on('change', function() {
        let selectedValue = $(this).val();
        console.log('Kecamatan changed to:', selectedValue);
        
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
                $("#kec").html(`<option value="${kecVal}" selected>${kecVal}</option>`);
            }
            if (kelVal) {
                $("#kel").html(`<option value="${kelVal}" selected>${kelVal}</option>`);
            }
        }
    }, 15000);
}

// Load restricted data from database for ADMIN
function loadRestrictedData(kecVal, kelVal) {
    const kecamatanSelect = document.getElementById('kec');
    const kelurahanSelect = document.getElementById('kel');
    
    if (!kecamatanSelect || !kelurahanSelect) {
        console.warn('Kecamatan or Kelurahan select not found');
        return;
    }

    console.log('Loading restricted data for ADMIN');
    console.log('Assigned Kecamatan:', userKelurahanData.kecamatan);
    console.log('Assigned Kelurahan by Kecamatan:', userKelurahanData.kelurahan_by_kecamatan);
    
    // Clear existing options
    kecamatanSelect.innerHTML = '';

    // Add placeholder option
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Kecamatan';
    placeholder.disabled = true;
    kecamatanSelect.appendChild(placeholder);

    // Add user's kecamatan options
    if (userKelurahanData.kecamatan && userKelurahanData.kecamatan.length > 0) {
        userKelurahanData.kecamatan.forEach(kec => {
            const option = document.createElement('option');
            option.value = kec;
            option.textContent = kec;
            if (kec === kecVal) {
                option.selected = true;
            }
            kecamatanSelect.appendChild(option);
        });
        
        console.log(`Populated ${userKelurahanData.kecamatan.length} kecamatan options`);
    }

    // Load kelurahan for selected kecamatan
    if (kecVal && userKelurahanData.kelurahan_by_kecamatan[kecVal]) {
        updateRestrictedKelurahanDropdown(kecVal, kelurahanSelect, kelVal);
    } else {
        kelurahanSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan Terlebih Dahulu</option>';
        kelurahanSelect.disabled = true;
    }

    // Add event listener for kecamatan change
    $('#kec').off('change').on('change', function() {
        updateRestrictedKelurahanDropdown(this.value, kelurahanSelect);
    });
}

// Update kelurahan dropdown based on selected kecamatan (for restricted users)
function updateRestrictedKelurahanDropdown(selectedKecamatan, kelurahanSelect, preselectedKel = null) {
    // Clear existing options
    kelurahanSelect.innerHTML = '';

    // Add placeholder
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Kelurahan';
    placeholder.disabled = true;
    kelurahanSelect.appendChild(placeholder);

    if (selectedKecamatan && userKelurahanData.kelurahan_by_kecamatan[selectedKecamatan]) {
        const kelurahanList = userKelurahanData.kelurahan_by_kecamatan[selectedKecamatan];
        
        kelurahanList.forEach(kel => {
            const option = document.createElement('option');
            option.value = kel;
            option.textContent = kel;
            if (kel === preselectedKel) {
                option.selected = true;
            }
            kelurahanSelect.appendChild(option);
        });

        kelurahanSelect.disabled = false;
        console.log(`Populated ${kelurahanList.length} kelurahan options for ${selectedKecamatan}`);
        
        // Show info message for ADMIN
        if ($('#kelurahan-restriction-info').length === 0) {
            const infoHtml = `
                <div id="kelurahan-restriction-info" class="alert alert-info mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Anda hanya dapat memilih kelurahan yang telah ditetapkan: <strong>${kelurahanList.join(', ')}</strong></span>
                </div>
            `;
            $('#kel').after(infoHtml);
        }
    } else {
        kelurahanSelect.disabled = true;
        console.warn(`No kelurahan data for ${selectedKecamatan}`);
    }
}

function validateDuplicate() {
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
}

function showDuplicateConfirmation() {
    if (validateDuplicate()) {
        // Get route template from window object
        const routeTemplate = window.pasarEditData?.duplicateRoute || '';
        
        showConfirmationModal(
            'Duplikat Penilaian',
            'Apakah Anda yakin ingin membuat duplikat dari penilaian pasar ini? Data akan disalin dengan informasi yang sama.',
            function() {
                // Create a new form for duplicate action
                const originalForm = document.querySelector('form');
                const formData = new FormData(originalForm);
                
                // Create new form for duplicate
                const duplicateForm = document.createElement('form');
                duplicateForm.method = 'POST';
                duplicateForm.action = routeTemplate;
                duplicateForm.style.display = 'none';
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = window.pasarEditData?.csrfToken || '';
                duplicateForm.appendChild(csrfInput);
                
                // Add action input
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'duplicate';
                duplicateForm.appendChild(actionInput);
                
                // Add original ID for reference
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'original_id';
                idInput.value = window.pasarEditData?.id || '';
                duplicateForm.appendChild(idInput);
                
                // Copy all form data except _method
                for (let [key, value] of formData.entries()) {
                    if (key !== '_method' && key !== '_token' && key !== 'action') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        duplicateForm.appendChild(input);
                    }
                }
                
                // Append and submit
                document.body.appendChild(duplicateForm);
                duplicateForm.submit();
            }
        );
    }
}

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {
    // Additional initialization code for pasar edit form
});