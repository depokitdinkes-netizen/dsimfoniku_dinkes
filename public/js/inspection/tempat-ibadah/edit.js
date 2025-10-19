// Tempat Ibadah Edit Form JavaScript
$(document).ready(function() {
    let kecVal = window.tempatIbadahFormData?.kecamatan || '';
    let kelVal = window.tempatIbadahFormData?.kelurahan || '';

    // console.log('Tempat Ibadah edit form initialized with:', { kecVal, kelVal });

    let checkKec = setInterval(function() {
        if (kecamatan.length > 0) {
            let options = "";

            kecamatan.forEach((el) => {
                options += `<option value="${el.name}" ${kecVal == el.name && 'selected'}>${el.name}</option>`;
            });

            $("#kec").html('<option>Pilih Kecamatan</option>');
            $("#kec").html($("#kec").html() + options);

            // Find the selected kecamatan and load its villages
            if (kecVal) {
                let selectedKec = kecamatan.find((el) => el.name == kecVal);
                if (selectedKec) {
                    let kecId = selectedKec.id;

                    fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecId}.json`)
                        .then((response) => response.json())
                        .then((villages) => {
                            let options = "";
                            villages.forEach((el) => {
                                options += `<option value="${el.name}" ${kelVal == el.name && 'selected'}>${el.name}</option>`;
                            });

                            $("#kel").html('<option>Pilih Kelurahan</option>');
                            $("#kel").html($("#kel").html() + options);
                            
                            // console.log('Villages loaded successfully for kecamatan:', kecVal);
                        })
                        .catch((error) => {
                            console.error('Error loading villages:', error);
                        });
                }
            }

            clearInterval(checkKec);
        }
    }, 500);

    // Auto-calculate on page load if issued date already filled
    // Additional initialization logic can be added here
});