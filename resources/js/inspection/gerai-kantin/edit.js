$(document).ready(function() {
    // Get data from global window object (passed from Blade template)
    if (window.geraiKantinEditData) {
        const { 
            kecKantin: kecKantinVal, 
            kelKantin: kelKantinVal,
            kecGerai: kecGeraiVal, 
            kelGerai: kelGeraiVal 
        } = window.geraiKantinEditData;

        let checkKec = setInterval(function() {
            if (kecamatan.length > 0) {
                // Setup dropdown kantin (read-only)
                let kantinOptions = '<option value="">Pilih Kecamatan</option>';
                kecamatan.forEach((el) => {
                    kantinOptions += `<option value="${el.name}" ${kecKantinVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                });
                $("#kec-kantin").html(kantinOptions);

                // Setup kelurahan untuk kantin jika ada kecamatan yang dipilih
                if (kecKantinVal) {
                    let kecKantinId = kecamatan.find((el) => el.name == kecKantinVal)?.id;
                    if (kecKantinId) {
                        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecKantinId}.json`)
                            .then((response) => response.json())
                            .then((villages) => {
                                let kantinKelOptions = '<option value="">Pilih Kelurahan</option>';
                                villages.forEach((el) => {
                                    kantinKelOptions += `<option value="${el.name}" ${kelKantinVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                                });
                                $("#kel-kantin").html(kantinKelOptions);
                            });
                    }
                }

                // Setup dropdown gerai (editable)
                let geraiOptions = '<option value="">Pilih Kecamatan</option>';
                kecamatan.forEach((el) => {
                    geraiOptions += `<option value="${el.name}" ${kecGeraiVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                });
                $("#kec-gerai").html(geraiOptions);

                // Setup kelurahan untuk gerai jika ada kecamatan yang dipilih
                if (kecGeraiVal) {
                    let kecGeraiId = kecamatan.find((el) => el.name == kecGeraiVal)?.id;
                    if (kecGeraiId) {
                        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecGeraiId}.json`)
                            .then((response) => response.json())
                            .then((villages) => {
                                let geraiKelOptions = '<option value="">Pilih Kelurahan</option>';
                                villages.forEach((el) => {
                                    geraiKelOptions += `<option value="${el.name}" ${kelGeraiVal == el.name ? 'selected' : ''}>${el.name}</option>`;
                                });
                                $("#kel-gerai").html(geraiKelOptions);
                            });
                    }
                }

                clearInterval(checkKec);
            }
        }, 500);

        // Event handler untuk perubahan kecamatan gerai
        $('#kec-gerai').on('change', function() {
            const selectedValue = this.value;
            
            if (selectedValue !== "") {
                const selectedKec = kecamatan.find((el) => el.name === selectedValue);
                
                if (selectedKec) {
                    // Show loading
                    $("#kel-gerai").html('<option value="">Memuat kelurahan...</option>');
                    $("#kel-gerai").prop('disabled', true);
                    
                    fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
                        .then((response) => response.json())
                        .then((villages) => {
                            let options = '<option value="">Pilih Kelurahan</option>';
                            villages.forEach((el) => {
                                options += `<option value="${el.name}">${el.name}</option>`;
                            });
                            $("#kel-gerai").html(options);
                            $("#kel-gerai").prop('disabled', false);
                        })
                        .catch((error) => {
                            console.error('Error loading villages:', error);
                            $("#kel-gerai").html('<option value="">Gagal memuat kelurahan</option>');
                        });
                }
            } else {
                $("#kel-gerai").html('<option value="">Pilih Kelurahan</option>');
                $("#kel-gerai").prop('disabled', true);
            }
        });
    }
});

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