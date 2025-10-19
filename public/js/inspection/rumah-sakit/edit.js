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
    // Get data from window object (passed from Blade template)
    let kecVal = window.rumahSakitEditData?.kecamatan || "";
    let kelVal = window.rumahSakitEditData?.kelurahan || "";
    
    // console.log('Edit form initialized with:', { kecVal, kelVal });

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
    
    // Populate tanggal penilaian if exists
    if (window.rumahSakitEditData?.tanggalPenilaian) {
        $("input[name='tanggal-penilaian']").val(window.rumahSakitEditData.tanggalPenilaian);
    }
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
                if (!villages || !Array.isArray(villages)) {
                    throw new Error('Invalid villages data format');
                }

                let options = '<option value="">Pilih Kelurahan</option>';
                villages.forEach((el) => {
                    const selected = kelVal == el.name ? 'selected' : '';
                    options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                });

                $("#kel").html(options);
                $("#kel").prop('disabled', false);
            })
            .catch((error) => {
                console.error('Error loading villages:', error);
                $("#kel").html('<option value="">Error memuat kelurahan</option>');
                $("#kel").prop('disabled', true);
            });
    }
    
    // Handle kecamatan change event
    $("#kec").change(function() {
        const selectedKecamatan = $(this).val();
        if (selectedKecamatan) {
            populateKelurahan(selectedKecamatan);
        } else {
            $("#kel").html('<option value="">Pilih Kelurahan</option>');
            $("#kel").prop('disabled', true);
        }
    });
    
    // Initialize dropdowns when kecamatan data is loaded
    let checkKec = setInterval(function() {
        if (typeof kecamatan !== 'undefined' && kecamatan.length > 0) {
            if (populateKecamatan()) {
                clearInterval(checkKec);
            }
        }
    }, 500);
}

// Load restricted data from database for ADMIN
function loadRestrictedData(kecVal, kelVal) {
    const kecamatanSelect = document.getElementById('kec');
    const kelurahanSelect = document.getElementById('kel');
    
    if (!kecamatanSelect || !kelurahanSelect) {
        console.warn('Kecamatan or Kelurahan select not found');
        return;
    }

    // console.log('Loading restricted data for ADMIN');
    // console.log('Assigned Kecamatan:', userKelurahanData.kecamatan);
    // console.log('Assigned Kelurahan by Kecamatan:', userKelurahanData.kelurahan_by_kecamatan);
    
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
        
        // console.log(`Populated ${userKelurahanData.kecamatan.length} kecamatan options`);
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
        // console.log(`Populated ${kelurahanList.length} kelurahan options for ${selectedKecamatan}`);
        
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

// Conditional fields logic
function initializeConditionalFields() {
    // Dokumen Rintek TPS B3
    $('input[name="dokumen-rintek-tps-b3"]').change(function() {
        if ($(this).val() == 'Ya') {
            $('#nomor-dokumen-rintek-tps-b3-group').show();
            $('#nomor-dokumen-rintek-tps-b3').attr('required', true);
        } else {
            $('#nomor-dokumen-rintek-tps-b3-group').hide();
            $('#nomor-dokumen-rintek-tps-b3').attr('required', false);
            $('#nomor-dokumen-rintek-tps-b3').val('');
        }
    });
    
    // Dokumen Pertek IPAL
    $('input[name="dokumen-pertek-ipal"]').change(function() {
        if ($(this).val() == 'Ya') {
            $('#nomor-dokumen-pertek-ipal-group').show();
            $('#nomor-dokumen-pertek-ipal').attr('required', true);
        } else {
            $('#nomor-dokumen-pertek-ipal-group').hide();
            $('#nomor-dokumen-pertek-ipal').attr('required', false);
            $('#nomor-dokumen-pertek-ipal').val('');
        }
    });
    
    // Pengisian SIKELIM
    $('input[name="pengisian-sikelim"]').change(function() {
        if ($(this).val() == 'Tidak') {
            $('#alasan-sikelim-group').show();
            $('#alasan-sikelim').attr('required', true);
        } else {
            $('#alasan-sikelim-group').hide();
            $('#alasan-sikelim').attr('required', false);
            $('#alasan-sikelim').val('');
        }
    });
    
    // Pengisian DSMILING
    $('input[name="pengisian-dsmiling"]').change(function() {
        if ($(this).val() == 'Tidak') {
            $('#alasan-dsmiling-group').show();
            $('#alasan-dsmiling').attr('required', true);
        } else {
            $('#alasan-dsmiling-group').hide();
            $('#alasan-dsmiling').attr('required', false);
            $('#alasan-dsmiling').val('');
        }
    });
    
    // Initialize fields based on current values
    if ($('input[name="dokumen-rintek-tps-b3"]:checked').val() == 'Ya') {
        $('#nomor-dokumen-rintek-tps-b3-group').show();
        $('#nomor-dokumen-rintek-tps-b3').attr('required', true);
    }
    
    if ($('input[name="dokumen-pertek-ipal"]:checked').val() == 'Ya') {
        $('#nomor-dokumen-pertek-ipal-group').show();
        $('#nomor-dokumen-pertek-ipal').attr('required', true);
    }
    
    if ($('input[name="pengisian-sikelim"]:checked').val() == 'Tidak') {
        $('#alasan-sikelim-group').show();
        $('#alasan-sikelim').attr('required', true);
    }
    
    if ($('input[name="pengisian-dsmiling"]:checked').val() == 'Tidak') {
        $('#alasan-dsmiling-group').show();
        $('#alasan-dsmiling').attr('required', true);
    }
    
    // Ensure default values are set before form submission
    $('form').on('submit', function(e) {
        // Set default values if no radio button is selected
        if (!$('input[name="dokumen-rintek-tps-b3"]:checked').length) {
            $('input[name="dokumen-rintek-tps-b3"][value="Tidak"]').prop('checked', true);
        }
        if (!$('input[name="dokumen-pertek-ipal"]:checked').length) {
            $('input[name="dokumen-pertek-ipal"][value="Tidak"]').prop('checked', true);
        }
        if (!$('input[name="pengisian-sikelim"]:checked').length) {
            $('input[name="pengisian-sikelim"][value="Ya"]').prop('checked', true);
        }
        if (!$('input[name="pengisian-dsmiling"]:checked').length) {
            $('input[name="pengisian-dsmiling"][value="Ya"]').prop('checked', true);
        }
    });
}

// Initialize conditional fields when page loads
initializeConditionalFields();

// Handle option selection for field f1002 with duplicate values
$('input[name="f1002"]').on('change', function() {
    const fieldName = $(this).attr('name');
    const selectedValue = $(this).val();
    
    // Find the corresponding hidden field with option ID for the selected radio button
    const parentLabel = $(this).closest('label');
    const hiddenField = parentLabel.find('input[type="hidden"][name^="' + fieldName + '_selected_id_"]');
    
    if (hiddenField.length) {
        // Remove any existing selected_id field and add the new one
        $('input[name="' + fieldName + '_selected_id"]').remove();
        $('<input>').attr({
            type: 'hidden',
            name: fieldName + '_selected_id',
            value: hiddenField.val()
        }).appendTo('form');
    }
});

// Initialize selected IDs for currently checked options on page load
$('input[name="f1002"]:checked').each(function() {
    const fieldName = $(this).attr('name');
    const parentLabel = $(this).closest('label');
    const hiddenField = parentLabel.find('input[type="hidden"][name^="' + fieldName + '_selected_id_"]');
    
    if (hiddenField.length) {
        $('input[name="' + fieldName + '_selected_id"]').remove();
        $('<input>').attr({
            type: 'hidden',
            name: fieldName + '_selected_id',
            value: hiddenField.val()
        }).appendTo('form');
    }
});

// Conditional logic for f1005, f1006, f1007
function toggleF1006F1007() {
    const f1005Value = $('input[name="f1005"]:checked').val();
    // console.log('f1005 dipilih:', f1005Value);
    const f1006Container = $('input[name="f1006"]').closest('.px-3');
    const f1007Container = $('input[name="f1007"]').closest('.px-3');
    
    if (f1005Value === 'Ya') { // Ya
        // console.log('Menampilkan f1006 dan f1007');
        f1006Container.show();
        f1007Container.show();
    } else { // Tidak
        // console.log('Menyembunyikan f1006 dan f1007');
        f1006Container.hide();
        f1007Container.hide();
        // Reset values when hidden
        $('input[name="f1006"]').prop('checked', false);
        $('input[name="f1007"]').prop('checked', false);
    }
}

// Check initial state on page load
$(document).ready(function() {
    // Bind change event to f1005
    $('input[name="f1005"]').change(toggleF1006F1007);
    
    toggleF1006F1007();
    
    // JavaScript untuk menangani pilihan 'Lainnya...'
    function handleLainnyaOption(fieldName) {
        const selectedValue = $('input[name="' + fieldName + '"]:checked').val();
        const lainnyaContainer = $('#' + fieldName + '_lainnya_container');
        
        if (selectedValue === 'Lainnya') {
            lainnyaContainer.show();
        } else {
            lainnyaContainer.hide();
            $('input[name="' + fieldName + '_lainnya"]').val('');
        }
    }
    
    // Bind change events untuk f1006, f1007, dan 6001e
    ['f1006', 'f1007', '6001e'].forEach(function(fieldName) {
        $('input[name="' + fieldName + '"]').change(function() {
            handleLainnyaOption(fieldName);
        });
        
        // Check initial state
        handleLainnyaOption(fieldName);
    });
});