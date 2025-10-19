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
    const kecamatanSelect = document.getElementById('kec');
    const kelurahanSelect = document.getElementById('kel');
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
        // console.log(`Populated ${userKelurahanData.kecamatan.length} kecamatan options`);
        if (userKelurahanData.kecamatan.length === 1) {
            autoSelectedKecamatan = userKelurahanData.kecamatan[0];
            // console.log(`Auto-selecting kecamatan: ${autoSelectedKecamatan}`);
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
        // console.log(`Populated ${kelurahanList.length} kelurahan options for ${selectedKecamatan}`);
    } else {
        kelurahanSelect.disabled = true;
        console.warn(`No kelurahan data for ${selectedKecamatan}`);
    }
}

window.shouldRestrictKelurahan = function() { return isRestrictedUser; };
window.waitForUserData = function() { return dataLoadingPromise; };
dataLoadingPromise = fetchUserKelurahan();

// Calculate SLHS expire date function
function calculateSlhsExpireDate() {
    const issuedDateInput = document.getElementById('slhs_issued_date');
    const expireDateInput = document.getElementById('slhs_expire_date');
    
    if (issuedDateInput && issuedDateInput.value && expireDateInput) {
        const issuedDate = new Date(issuedDateInput.value);
        issuedDate.setFullYear(issuedDate.getFullYear() + 3);
        
        const year = issuedDate.getFullYear();
        const month = String(issuedDate.getMonth() + 1).padStart(2, '0');
        const day = String(issuedDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        
        expireDateInput.value = formattedDate;
    } else if (expireDateInput) {
        expireDateInput.value = '';
    }
}

$(document).ready(function() {
    // Handle u001 category selection
    $('#u001-select').change(function() {
        $('#u001').val(this.value);

        if (this.value == 'Other') {
            $('#u001').removeClass('hidden');
            $('#u001').prop('required', true);
            $('#u001').val('');
        } else {
            $('#u001').addClass('hidden');
            $('#u001').removeAttr('required');
        }
    });

    // Handle u010 flood selection
    $('#u010').change(function() {
        if (this.value == "1") {
            $('#u010a').removeClass('hidden');
            $('#u010a').prop('required', true);
        } else {
            $('#u010a').addClass('hidden');
            $('#u010a').removeAttr('required');
        }
        $('#u010a').val('');
    });

    // Handle u011 operation status
    $('#u011').change(function() {
        $('#u011a').val('');
        if (this.value == "0") {
            $('#u011a').removeClass('hidden');
            $('#u011a').prop('required', true);
        } else {
            $('#u011a').addClass('hidden');
            $('#u011a').removeAttr('required');
        }
    });

    // Handle ins001 treatment selection
    $('input[name="ins001"]').change(function() {
        if (this.value == 0) {
            $('.ket-pengolahan').addClass('hidden');
        } else {
            $('.ket-pengolahan').removeClass('hidden');
        }
    });
});

// Calculate SLHS expire date function
function calculateSlhsExpireDate() {
    const issuedDateInput = document.getElementById('slhs_issued_date');
    const expireDateInput = document.getElementById('slhs_expire_date');
    
    if (issuedDateInput && issuedDateInput.value && expireDateInput) {
        const issuedDate = new Date(issuedDateInput.value);
        // Add 3 years to the issued date
        issuedDate.setFullYear(issuedDate.getFullYear() + 3);
        
        // Format the date as YYYY-MM-DD
        const year = issuedDate.getFullYear();
        const month = String(issuedDate.getMonth() + 1).padStart(2, '0');
        const day = String(issuedDate.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        
        expireDateInput.value = formattedDate;
    } else if (expireDateInput) {
        // Clear expire date if no issued date
        expireDateInput.value = '';
    }
}

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {
    calculateSlhsExpireDate();
});