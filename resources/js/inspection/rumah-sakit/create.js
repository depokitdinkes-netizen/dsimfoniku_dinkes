let userKelurahanData = null;
let isRestrictedUser = false;
let dataLoadingPromise = null;

async function fetchUserKelurahan() {
    try {
        const response = await fetch('/api/user-kelurahan', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        if (!response.ok) throw new Error('Failed to fetch user kelurahan data');
        const result = await response.json();
        if (result.success) {
            userKelurahanData = result.data;
            isRestrictedUser = !result.data.is_superadmin;
            console.log('User Kelurahan Data:', userKelurahanData);
            console.log('Is Restricted User (ADMIN):', isRestrictedUser);
            initializeKecamatanDropdown();
        } else {
            console.error('Failed to load user kelurahan:', result.message);
            isRestrictedUser = false;
        }
    } catch (error) {
        console.error('Error fetching user kelurahan:', error);
    }
}

function initializeKecamatanDropdown() {
    const kecamatanSelect = document.getElementById('kec');
    const kelurahanSelect = document.getElementById('kel');
    if (!kecamatanSelect || !kelurahanSelect) {
        console.warn('Kecamatan or Kelurahan select not found');
        return;
    }
    if (userKelurahanData && userKelurahanData.is_superadmin) {
        console.log('User is SUPERADMIN, allowing access to all kecamatan/kelurahan');
        return;
    }
    if (userKelurahanData && !userKelurahanData.is_superadmin) {
        console.log('User is ADMIN, restricting to assigned areas');
        console.log('Assigned Kecamatan:', userKelurahanData.kecamatan);
        console.log('Assigned Kelurahan by Kecamatan:', userKelurahanData.kelurahan_by_kecamatan);
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

document.addEventListener('DOMContentLoaded', function() {
    // Conditional fields logic
    
    // Dokumen Rintek TPS B3
    $('input[name="dokumen-rintek-tps-b3"]').change(function() {
        if ($(this).val() == 'Ya') { // Ya
            $('#nomor-dokumen-rintek-tps-b3-group').show();
            $('#nomor-dokumen-rintek-tps-b3').attr('required', true);
        } else { // Tidak
            $('#nomor-dokumen-rintek-tps-b3-group').hide();
            $('#nomor-dokumen-rintek-tps-b3').attr('required', false);
            $('#nomor-dokumen-rintek-tps-b3').val('');
        }
    });
    
    // Dokumen Pertek IPAL
    $('input[name="dokumen-pertek-ipal"]').change(function() {
        if ($(this).val() == 'Ya') { // Ya
            $('#nomor-dokumen-pertek-ipal-group').show();
            $('#nomor-dokumen-pertek-ipal').attr('required', true);
        } else { // Tidak
            $('#nomor-dokumen-pertek-ipal-group').hide();
            $('#nomor-dokumen-pertek-ipal').attr('required', false);
            $('#nomor-dokumen-pertek-ipal').val('');
        }
    });
    
    // Pengisian SIKELIM
    $('input[name="pengisian-sikelim"]').change(function() {
        if ($(this).val() == 'Tidak') { // Tidak
            $('#alasan-sikelim-group').show();
            $('#alasan-sikelim').attr('required', true);
        } else { // Ya
            $('#alasan-sikelim-group').hide();
            $('#alasan-sikelim').attr('required', false);
            $('#alasan-sikelim').val('');
        }
    });
    
    // Pengisian DSMILING
    $('input[name="pengisian-dsmiling"]').change(function() {
        if ($(this).val() == 'Tidak') { // Tidak
            $('#alasan-dsmiling-group').show();
            $('#alasan-dsmiling').attr('required', true);
        } else { // Ya
            $('#alasan-dsmiling-group').hide();
            $('#alasan-dsmiling').attr('required', false);
            $('#alasan-dsmiling').val('');
        }
    });
    
    // Conditional logic for f1005, f1006, f1007
    function toggleF1006F1007() {
        const f1005Value = $('input[name="f1005"]:checked').val();
        const f1006Container = $('input[name="f1006"]').closest('.px-3');
        const f1007Container = $('input[name="f1007"]').closest('.px-3');
        
        if (f1005Value === 'Ya') { // Ya
            f1006Container.show();
            f1007Container.show();
        } else { // Tidak
            f1006Container.hide();
            f1007Container.hide();
            // Reset values when hidden
            $('input[name="f1006"]').prop('checked', false);
            $('input[name="f1007"]').prop('checked', false);
        }
    }
    
    // Initially hide f1006 and f1007
    $('input[name="f1006"]').closest('.px-3').hide();
    $('input[name="f1007"]').closest('.px-3').hide();
    
    // Bind change event to f1005
    $('input[name="f1005"]').change(toggleF1006F1007);
    
    // Check initial state
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
        
        // Ensure all 'selects' fields have a value selected (fix for fields that don't get submitted)
        const selectsFields = ['f1001', 'f1003', 'f1004', 'f2002', 'f3001', 'f3002', 'f4001', 'f4002', 'f4005', 'f5002', 'f7001', 'f7002', 'f7003', 'f7004', 'f7005', 'f9002', 'f9003'];
        selectsFields.forEach(function(fieldName) {
            if (!$('input[name="' + fieldName + '"]:checked').length) {
                // Select the first radio button for this field
                $('input[name="' + fieldName + '"]').first().prop('checked', true);
            }
        });
    });
});