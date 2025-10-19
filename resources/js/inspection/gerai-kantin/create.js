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

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {
    calculateSlhsExpireDate();
});

$(document).ready(function() {
    // Get data from global window object (passed from Blade template)
    if (window.geraiKantinCreateData) {
        const { kecamatan: kecVal, kelurahan: kelVal } = window.geraiKantinCreateData;

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
});