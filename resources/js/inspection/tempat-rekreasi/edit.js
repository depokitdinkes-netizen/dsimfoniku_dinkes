// Tempat Rekreasi Edit Form JavaScript

// Global variables for user kelurahan restrictions
let userKelurahanData = null;
let isRestrictedUser = false;

// Function to fetch user kelurahan data from database
async function fetchUserKelurahan() {
    try {
        const response = await fetch('/api/user-kelurahan');
        const result = await response.json();
        
        if (result.success && result.data) {
            isRestrictedUser = !result.data.is_superadmin;
            if (isRestrictedUser) {
                userKelurahanData = result.data;
                console.log('User kelurahan data loaded:', userKelurahanData);
            } else {
                console.log('User is SUPERADMIN, no restrictions');
            }
        }
    } catch (error) {
        console.error('Error fetching user kelurahan:', error);
        // Fallback to unrestricted if API fails
        isRestrictedUser = false;
    }
}

$(document).ready(function() {
    let kecVal = window.tempatRekreasiFormData?.kecamatan || "";
    let kelVal = window.tempatRekreasiFormData?.kelurahan || "";
    
    console.log('Edit form initialized with:', { kecVal, kelVal });

    // Fetch user data first, then load appropriate data
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
        // Function to populate kecamatan dropdown with existing value
        function populateKecamatan() {
            if (typeof kecamatan !== 'undefined' && kecamatan.length > 0) {
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
        }, 500);
        
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

    // Function for ADMIN - uses database data
    function loadRestrictedData(kecVal, kelVal) {
        console.log('Loading restricted data for ADMIN user');
        
        // Populate kecamatan from database
        let kecOptions = '<option value="">Pilih Kecamatan</option>';
        userKelurahanData.kecamatan.forEach((kec) => {
            let selected = (kecVal && kecVal == kec) ? 'selected' : '';
            kecOptions += `<option value="${kec}" ${selected}>${kec}</option>`;
        });
        $("#kec").html(kecOptions);
        $("#kec").prop('disabled', false);

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
        console.log('Updating kelurahan for restricted user, kecamatan:', selectedKec);
        
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
});

function calculateSlsExpireDate() {
    const issuedDateInput = document.getElementById('sls_issued_date');
    const expireDateInput = document.getElementById('sls_expire_date');
    
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

// Auto-calculate on page load if issued date already filled
calculateSlsExpireDate();