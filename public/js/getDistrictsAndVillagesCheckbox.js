let kecamatan = [];

$(document).ready(function () {
    fetch(
        `https://dev4ult.github.io/api-wilayah-indonesia/api/districts/3276.json`
    )
        .then((response) => response.json())
        .then((districts) => {
            kecamatan = districts;
            let options = "";
            districts.forEach((el) => {
                options += `<option value="${el.name}">${el.name}</option>`;
            });

            $("#kec").html($("#kec").html() + options);
        });

    $("#kec").change(function () {
        if (this.value != "") {
            $("#kel").html('<label>Kelurahan</label>');

            let kecId = kecamatan.find((el) => el.name == this.value).id;

            fetch(
                `https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecId}.json`
            )
                .then((response) => response.json())
                .then((villages) => {
                    let options = '';
                    villages.forEach((el) => {
                        options += `
                        <label class="flex items-center gap-2 cursor-pointer text-sm p-2 border rounded-md">
                            <input type="checkbox" name="kel[]" value="${el.name}" class="checkbox checkbox-primary rounded">
                            <span>${el.name}</span>
                        </label>`;
                    });

                    $("#kel").html($("#kel").html() + options);
                });
        } else {
            $("#kel").attr("disabled");
        }
    });
});
