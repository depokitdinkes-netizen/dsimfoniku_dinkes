// Global variable to store user's allowed kecamatan and kelurahan
let userKelurahanData = null;
let isRestrictedUser = false; // Flag to indicate if user should have restrictions
let dataLoadingPromise = null; // Promise to track data loading

function calculateSlhsExpireDate() {
    const issuedDateInput = document.getElementById('slhs_issued_date');
    const expireDateInput = document.getElementById('slhs_expire_date');
    
    if (issuedDateInput && expireDateInput && issuedDateInput.value) {
        // Parse issued date
        const issuedDate = new Date(issuedDateInput.value);
        
        // Add 3 years
        const expireDate = new Date(issuedDate);
        expireDate.setFullYear(expireDate.getFullYear() + 3);
        
        // Format to YYYY-MM-DD
        const formattedDate = expireDate.toISOString().split('T')[0];
        
        // Set expire date
        expireDateInput.value = formattedDate;
    } else if (expireDateInput) {
        // Clear expire date if no issued date
        expireDateInput.value = '';
    }
}

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
            initializeKecamatanDropdown();
        } else {
            isRestrictedUser = false;
        }
    } catch (error) {
        isRestrictedUser = false;
    }
}
   

// Initialize kecamatan dropdown based on user's access
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

// Populate kecamatan dropdown with restricted data
function populateRestrictedKecamatan(kecamatanSelect, kelurahanSelect) {
    // Clear existing options
    kecamatanSelect.innerHTML = '';

    // Add placeholder option
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Pilih Kecamatan';
    placeholder.disabled = true;
    placeholder.selected = true;
    kecamatanSelect.appendChild(placeholder);

    // Add user's kecamatan options
    let autoSelectedKecamatan = null;
    if (userKelurahanData.kecamatan && userKelurahanData.kecamatan.length > 0) {
        userKelurahanData.kecamatan.forEach(kec => {
            const option = document.createElement('option');
            option.value = kec;
            option.textContent = kec;
            kecamatanSelect.appendChild(option);
        });
        
        if (userKelurahanData.kecamatan.length === 1) {
            autoSelectedKecamatan = userKelurahanData.kecamatan[0];
        }
    }

    // Reset kelurahan dropdown
    kelurahanSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan Terlebih Dahulu</option>';
    kelurahanSelect.disabled = true;

    // Remove old event listener if exists and add new one
    const newKecamatanSelect = kecamatanSelect.cloneNode(true);
    kecamatanSelect.parentNode.replaceChild(newKecamatanSelect, kecamatanSelect);
    
    // Add event listener for kecamatan change
    newKecamatanSelect.addEventListener('change', function() {
        updateRestrictedKelurahanDropdown(this.value, kelurahanSelect);
    });
    
    // If only 1 kecamatan, auto-select and trigger kelurahan update
    if (autoSelectedKecamatan) {
        newKecamatanSelect.value = autoSelectedKecamatan;
        // Manually trigger kelurahan update
        updateRestrictedKelurahanDropdown(autoSelectedKecamatan, kelurahanSelect);
    }
}

// Update kelurahan dropdown based on selected kecamatan (for restricted users)
function updateRestrictedKelurahanDropdown(selectedKecamatan, kelurahanSelect) {
    // Clear existing options
    kelurahanSelect.innerHTML = '';

    // Add placeholder
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
    } else {
        kelurahanSelect.disabled = true;
    }
}

// Prevent getDistrictsAndVillages.js from running for restricted users
// This function will be called before getDistrictsAndVillages.js initializes
window.shouldRestrictKelurahan = function() {
    return isRestrictedUser;
};

// Function to wait for user data to be loaded
window.waitForUserData = function() {
    return dataLoadingPromise;
};

// Initialize immediately (not waiting for DOMContentLoaded)
// This ensures the flag is set before getDistrictsAndVillages.js runs
dataLoadingPromise = fetchUserKelurahan();

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {
    calculateSlhsExpireDate();
});