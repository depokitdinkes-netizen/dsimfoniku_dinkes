let userKelurahanData = null;
let isRestrictedUser = false;
let dataLoadingPromise = null;

function calculateSlhsExpireDate() {
    const issuedDateInput = document.getElementById('slhs_issued_date');
    const expireDateInput = document.getElementById('slhs_expire_date');
    
    if (issuedDateInput && expireDateInput && issuedDateInput.value) {
        const issuedDate = new Date(issuedDateInput.value);
        const expireDate = new Date(issuedDate);
        expireDate.setFullYear(expireDate.getFullYear() + 3);
        const formattedDate = expireDate.toISOString().split('T')[0];
        expireDateInput.value = formattedDate;
    } else if (expireDateInput) {
        expireDateInput.value = '';
    }
}

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
    calculateSlhsExpireDate();
});