let kecamatan = [];
let isInitialized = false;
const MAX_RETRIES = 2;
let retryCount = 0;

$(document).ready(function () {
    // Prevent multiple initialization
    if (isInitialized) {
        return;
    }
    isInitialized = true;

    // Load fallback data if available
    function loadFallbackData() {
        if (window.FALLBACK_KECAMATAN) {
            console.log('Using fallback kecamatan data');
            kecamatan = window.FALLBACK_KECAMATAN;
            
            let options = '<option value="">Pilih Kecamatan</option>';
            kecamatan.forEach((el) => {
                options += `<option value="${el.name}">${el.name}</option>`;
            });

            $('#kec').html(options);
            $('#kec-admin').html(options); // Tambahkan untuk admin dropdown
            enableDropdown('#kec');
            enableDropdown('#kec-admin'); // Enable admin dropdown juga
            return true;
        }
        return false;
    }

    // Show loading indicator
    function showLoading(selector, text = 'Memuat...') {
        if (selector.startsWith('.')) {
            // Handle class selector (multiple elements)
            $(selector).each(function() {
                $(this).html(`<option value="">${text}</option>`);
                $(this).prop('disabled', true);
            });
        } else {
            // Handle ID selector (single element)
            $(selector).html(`<option value="">${text}</option>`);
            $(selector).prop('disabled', true);
        }
    }

    // Enable dropdown and remove loading
    function enableDropdown(selector) {
        if (selector.startsWith('.')) {
            // Handle class selector (multiple elements)
            $(selector).each(function() {
                $(this).prop('disabled', false);
            });
        } else {
            // Handle ID selector (single element)
            $(selector).prop('disabled', false);
        }
    }

    // Show error message
    function showError(selector, message = 'Gagal memuat data') {
        if (selector.startsWith('.')) {
            // Handle class selector (multiple elements)
            $(selector).each(function() {
                $(this).html(`<option value="">${message}</option>`);
                $(this).prop('disabled', true);
            });
        } else {
            // Handle ID selector (single element)
            $(selector).html(`<option value="">${message}</option>`);
            $(selector).prop('disabled', true);
        }
    }

    // Initialize kecamatan dropdown
    function initKecamatan() {
        showLoading('#kec', 'Memuat kecamatan...');

        fetch('https://dev4ult.github.io/api-wilayah-indonesia/api/districts/3276.json')
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((districts) => {
                if (!districts || !Array.isArray(districts)) {
                    throw new Error('Invalid data format');
                }

                kecamatan = districts;
                let options = '<option value="">Pilih Kecamatan</option>';
                
                districts.forEach((el) => {
                    options += `<option value="${el.name}">${el.name}</option>`;
                });

                $('#kec').html(options);
                $('#kec-admin').html(options); // Tambahkan untuk admin dropdown
                enableDropdown('#kec');
                enableDropdown('#kec-admin'); // Enable admin dropdown juga
                retryCount = 0; // Reset retry count on success
                
                console.log('Kecamatan data loaded successfully from API:', districts.length, 'items');
            })
            .catch((error) => {
                console.error('Error loading kecamatan from API:', error);
                retryCount++;

                if (retryCount <= MAX_RETRIES) {
                    console.log(`Retrying to load kecamatan... (${retryCount}/${MAX_RETRIES})`);
                    setTimeout(() => {
                        initKecamatan();
                    }, 2000 * retryCount); // Exponential backoff
                } else {
                    // Use fallback data if available
                    if (!loadFallbackData()) {
                        showError('#kec', 'Gagal memuat kecamatan');
                    }
                }
            });
    }

    // Initialize kelurahan dropdown for multiple selects
    function initKelurahan(kecamatanName) {
        const selectedKec = kecamatan.find((el) => el.name === kecamatanName);
        
        if (!selectedKec) {
            showError('.kelurahan-select', 'Data kecamatan tidak ditemukan');
            return;
        }

        showLoading('.kelurahan-select', 'Memuat kelurahan...');

        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((villages) => {
                if (!villages || !Array.isArray(villages)) {
                    throw new Error('Invalid villages data format');
                }

                let options = '<option value="">Pilih Kelurahan</option>';
                villages.forEach((el) => {
                    options += `<option value="${el.name}">${el.name}</option>`;
                });

                // Update all kelurahan selects
                $('.kelurahan-select').each(function() {
                    const currentValue = $(this).val();
                    $(this).html(options);
                    if (currentValue) {
                        $(this).val(currentValue);
                    }
                    $(this).prop('disabled', false);
                });
                
                // Also update old single select if exists for backward compatibility
                if ($('#kel').length) {
                    $('#kel').html(options);
                    $('#kel').prop('disabled', false);
                }
                
                console.log('Kelurahan data loaded successfully from API:', villages.length, 'items');
            })
            .catch((error) => {
                console.error('Error loading kelurahan from API:', error);
                
                // Try fallback data
                if (window.FALLBACK_KELURAHAN && window.FALLBACK_KELURAHAN[selectedKec.id]) {
                    console.log('Using fallback kelurahan data for', kecamatanName);
                    const fallbackVillages = window.FALLBACK_KELURAHAN[selectedKec.id];
                    
                    let options = '<option value="">Pilih Kelurahan</option>';
                    fallbackVillages.forEach((el) => {
                        options += `<option value="${el.name}">${el.name}</option>`;
                    });

                    $('.kelurahan-select').each(function() {
                        const currentValue = $(this).val();
                        $(this).html(options);
                        if (currentValue) {
                            $(this).val(currentValue);
                        }
                        $(this).prop('disabled', false);
                    });
                    
                    if ($('#kel').length) {
                        $('#kel').html(options);
                        $('#kel').prop('disabled', false);
                    }
                } else {
                    showError('.kelurahan-select', 'Gagal memuat kelurahan');
                    
                    // Retry once after 2 seconds
                    setTimeout(() => {
                        console.log('Retrying to load kelurahan...');
                        initKelurahan(kecamatanName);
                    }, 2000);
                }
            });
    }

    // Initialize kelurahan dropdown untuk admin edit sendiri (multiple selects)
    function initKelurahanAdmin(kecamatanName) {
        const selectedKec = kecamatan.find((el) => el.name === kecamatanName);
        
        if (!selectedKec) {
            showError('.kelurahan-select-admin', 'Data kecamatan tidak ditemukan');
            return;
        }

        showLoading('.kelurahan-select-admin', 'Memuat kelurahan...');

        fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((villages) => {
                if (!villages || !Array.isArray(villages)) {
                    throw new Error('Invalid villages data format');
                }

                let options = '<option value="">Pilih Kelurahan</option>';
                villages.forEach((el) => {
                    options += `<option value="${el.name}">${el.name}</option>`;
                });

                // Update all admin kelurahan selects
                $('.kelurahan-select-admin').each(function() {
                    const currentValue = $(this).val();
                    $(this).html(options);
                    if (currentValue) {
                        $(this).val(currentValue);
                    }
                    $(this).prop('disabled', false);
                });
                
                // Also update old single select if exists for backward compatibility
                if ($('#kel-admin').length) {
                    $('#kel-admin').html(options);
                    $('#kel-admin').prop('disabled', false);
                }
                
                console.log('Kelurahan admin data loaded successfully from API:', villages.length, 'items');
            })
            .catch((error) => {
                console.error('Error loading kelurahan admin from API:', error);
                
                // Try fallback data
                if (window.FALLBACK_KELURAHAN && window.FALLBACK_KELURAHAN[selectedKec.id]) {
                    console.log('Using fallback kelurahan data for admin', kecamatanName);
                    const fallbackVillages = window.FALLBACK_KELURAHAN[selectedKec.id];
                    
                    let options = '<option value="">Pilih Kelurahan</option>';
                    fallbackVillages.forEach((el) => {
                        options += `<option value="${el.name}">${el.name}</option>`;
                    });

                    $('.kelurahan-select-admin').each(function() {
                        const currentValue = $(this).val();
                        $(this).html(options);
                        if (currentValue) {
                            $(this).val(currentValue);
                        }
                        $(this).prop('disabled', false);
                    });
                    
                    if ($('#kel-admin').length) {
                        $('#kel-admin').html(options);
                        $('#kel-admin').prop('disabled', false);
                    }
                } else {
                    showError('.kelurahan-select-admin', 'Gagal memuat kelurahan');
                    
                    // Retry once after 2 seconds
                    setTimeout(() => {
                        console.log('Retrying to load kelurahan admin...');
                        initKelurahanAdmin(kecamatanName);
                    }, 2000);
                }
            });
    }

    // Remove existing event handlers to prevent duplicates
    $('#kec').off('change.kecamatan');
    $('#kec-admin').off('change.kecamatan-admin');
    
    // Add event handler for kecamatan change (superadmin edit admin)
    $('#kec').on('change.kecamatan', function () {
        const selectedValue = this.value;
        
        if (selectedValue !== "") {
            initKelurahan(selectedValue);
        } else {
            $('.kelurahan-select').each(function() {
                $(this).html('<option value="">Pilih Kelurahan</option>');
                $(this).prop('disabled', true);
            });
            // Backward compatibility
            if ($('#kel').length) {
                $('#kel').html('<option value="">Pilih Kelurahan</option>');
                $('#kel').prop('disabled', true);
            }
        }
    });
    
    // Add event handler for kecamatan change (admin edit sendiri)
    $('#kec-admin').on('change.kecamatan-admin', function () {
        const selectedValue = this.value;
        
        if (selectedValue !== "") {
            initKelurahanAdmin(selectedValue);
        } else {
            $('.kelurahan-select-admin').each(function() {
                $(this).html('<option value="">Pilih Kelurahan</option>');
                $(this).prop('disabled', true);
            });
            // Backward compatibility
            if ($('#kel-admin').length) {
                $('#kel-admin').html('<option value="">Pilih Kelurahan</option>');
                $('#kel-admin').prop('disabled', true);
            }
        }
    });

    // Initialize the dropdown
    initKecamatan();

    // Initially disable kelurahan dropdown
    $('.kelurahan-select').prop('disabled', true);
    $('.kelurahan-select-admin').prop('disabled', true);
    // Backward compatibility
    if ($('#kel').length) $('#kel').prop('disabled', true);
    if ($('#kel-admin').length) $('#kel-admin').prop('disabled', true);
});

// Global function to manually refresh kecamatan data
window.refreshKecamatanData = function() {
    isInitialized = false;
    kecamatan = [];
    retryCount = 0;
    $(document).ready();
};

// Global function to check dropdown status
window.checkDropdownStatus = function() {
    const kecStatus = $('#kec').prop('disabled') ? 'disabled' : 'enabled';
    const kelStatus = $('#kel').prop('disabled') ? 'disabled' : 'enabled';
    const kecOptions = $('#kec option').length;
    const kelOptions = $('#kel option').length;
    
    console.log('Dropdown Status:', {
        kecamatan: kecStatus,
        kelurahan: kelStatus,
        kecamatanOptions: kecOptions,
        kelurahanOptions: kelOptions,
        dataLoaded: kecamatan.length > 0
    });
    
    return {
        kecamatan: kecStatus,
        kelurahan: kelStatus,
        kecamatanOptions: kecOptions,
        kelurahanOptions: kelOptions,
        dataLoaded: kecamatan.length > 0
    };
};
