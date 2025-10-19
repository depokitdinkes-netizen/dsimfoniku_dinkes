// Sumur Bor Pompa Edit Form JavaScript

// Global variables for user kelurahan restrictions
let userKelurahanData = null;
let isRestrictedUser = false;

// Function to fetch user kelurahan data from database
async function fetchUserKelurahan() {
    if (typeof window.isAuthenticated === 'undefined' || !window.isAuthenticated) {
        isRestrictedUser = false;
        return;
    }

    try {
        const response = await fetch('/api/user-kelurahan');
        
        if (!response.ok) {
            if (response.status === 401) {
                isRestrictedUser = false;
                return;
            }
            throw new Error('Failed to fetch user kelurahan data');
        }

        const result = await response.json();
        
        if (result.success && result.data) {
            isRestrictedUser = !result.data.is_superadmin;
            if (isRestrictedUser) {
                userKelurahanData = result.data;
            }
        } else {
            isRestrictedUser = false;
        }
    } catch (error) {
        isRestrictedUser = false;
    }
}

$(document).ready(function() {
    // Initialize kecamatan and kelurahan values from form data
    let kecVal = window.sumurBorPompaEditData ? window.sumurBorPompaEditData.kecamatan : "";
    let kelVal = window.sumurBorPompaEditData ? window.sumurBorPompaEditData.kelurahan : "";

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
        // Populate kecamatan and kelurahan dropdowns
        let checkKec = setInterval(function() {
            if (kecamatan.length > 0) {
                let options = "";

                kecamatan.forEach((el) => {
                    options += `<option value="${el.name}" ${kecVal == el.name && 'selected'}>${el.name}</option>`;
                });

                $("#kec").html('<option>Pilih Kelurahan</option>');
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
                    });

                clearInterval(checkKec);
            }
        }, 500);
    }

    // Function for ADMIN - uses database data
    function loadRestrictedData(kecVal, kelVal) {
        // console.log('Loading restricted data for ADMIN user');
        
        // Populate kecamatan from database
        let kecOptions = '<option value="">Pilih Kecamatan</option>';
        userKelurahanData.kecamatan.forEach((kec) => {
            let selected = (kecVal && kecVal == kec) ? 'selected' : '';
            kecOptions += `<option value="${kec}" ${selected}>${kec}</option>`;
        });
        $("#kec").html(kecOptions);

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
        // console.log('Updating kelurahan for restricted user, kecamatan:', selectedKec);
        
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