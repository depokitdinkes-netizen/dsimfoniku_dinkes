// Perpipaan Non PDAM Edit Form JavaScript

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
    // Initialize kecamatan and kelurahan values from form data
    let kecVal = window.perpipaanNonPdamEditData ? window.perpipaanNonPdamEditData.kecamatan : "";
    let kelVal = window.perpipaanNonPdamEditData ? window.perpipaanNonPdamEditData.kelurahan : "";

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
});

// Load data from API for SUPERADMIN
function loadDataFromAPI(kecVal, kelVal) {
    // Populate kecamatan and kelurahan dropdowns
    let checkKec = setInterval(function() {
        if (kecamatan.length > 0) {
            let options = "";

            kecamatan.forEach((el) => {
                options += `<option value="${el.name}" ${kecVal == el.name && 'selected'}>${el.name}</option>`;
            });

            $("#kec").html('<option value="">Pilih Kecamatan</option>');
            $("#kec").html($("#kec").html() + options);

            let kecId = kecamatan.find((el) => el.name == kecVal).id;

            fetch(
                    `https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecId}.json`
                )
                .then((response) => response.json())
                .then((villages) => {
                    let options = "";
                    villages.forEach((el) => {
                        options += `<option value="${el.name}" ${kelVal == el.name && 'selected'}>${el.name}</option>`;
                    });

                    $("#kel").html($("#kel").html() + options);
                    $("#kel").prop('disabled', false);
                });

            clearInterval(checkKec);
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

// Calculate SLHS expire date function
function calculateSlhsExpireDate() {
    const issuedDate = document.getElementById('slhs_issued_date').value;
    if (issuedDate) {
        const issued = new Date(issuedDate);
        const expired = new Date(issued);
        expired.setFullYear(expired.getFullYear() + 3);
        
        const expiredDateString = expired.toISOString().split('T')[0];
        document.getElementById('slhs_expire_date').value = expiredDateString;
    }
}

// Calculate expire date on page load if issued date exists
document.addEventListener('DOMContentLoaded', function() {
    const issuedDate = document.getElementById('slhs_issued_date').value;
    if (issuedDate) {
        calculateSlhsExpireDate();
    }
});