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

document.addEventListener('DOMContentLoaded', function() {
    // Initialization code for pasar create form
});