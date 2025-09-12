// Debug utility untuk dropdown kecamatan
window.debugKecamatan = {
    // Check current status
    status: function() {
        console.log('=== Dropdown Kecamatan Debug ===');
        console.log('jQuery loaded:', typeof $ !== 'undefined');
        console.log('Kecamatan element exists:', $('#kec').length > 0);
        console.log('Kelurahan element exists:', $('#kel').length > 0);
        
        if ($('#kec').length > 0) {
            console.log('Kecamatan options count:', $('#kec option').length);
            console.log('Kecamatan disabled:', $('#kec').prop('disabled'));
            console.log('Kecamatan current value:', $('#kec').val());
        }
        
        if ($('#kel').length > 0) {
            console.log('Kelurahan options count:', $('#kel option').length);
            console.log('Kelurahan disabled:', $('#kel').prop('disabled'));
            console.log('Kelurahan current value:', $('#kel').val());
        }
        
        console.log('Kecamatan data loaded:', window.kecamatan?.length || 0);
        console.log('Fallback data available:', !!window.FALLBACK_KECAMATAN);
    },

    // Test API connectivity
    testAPI: async function() {
        console.log('Testing API connectivity...');
        try {
            const response = await fetch('https://dev4ult.github.io/api-wilayah-indonesia/api/districts/3276.json');
            console.log('API Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('API Data received:', data.length, 'kecamatan');
                return true;
            } else {
                console.log('API Error:', response.statusText);
                return false;
            }
        } catch (error) {
            console.log('API Connection failed:', error.message);
            return false;
        }
    },

    // Force reload dropdown
    reload: function() {
        console.log('Force reloading dropdown...');
        if (window.refreshKecamatanData) {
            window.refreshKecamatanData();
        } else {
            console.log('refreshKecamatanData function not available');
        }
    },

    // Simulate dropdown selection
    selectKecamatan: function(kecamatanName) {
        console.log('Selecting kecamatan:', kecamatanName);
        $('#kec').val(kecamatanName).trigger('change');
    },

    // Get available options
    getOptions: function() {
        const kecOptions = [];
        $('#kec option').each(function() {
            if ($(this).val()) {
                kecOptions.push($(this).val());
            }
        });
        
        const kelOptions = [];
        $('#kel option').each(function() {
            if ($(this).val()) {
                kelOptions.push($(this).val());
            }
        });
        
        console.log('Available Kecamatan:', kecOptions);
        console.log('Available Kelurahan:', kelOptions);
        
        return { kecamatan: kecOptions, kelurahan: kelOptions };
    }
};

// Auto-run basic status check when page loads
$(document).ready(function() {
    setTimeout(function() {
        console.log('Auto-checking dropdown status...');
        window.debugKecamatan.status();
    }, 2000);
});

console.log('Kecamatan debug utility loaded. Use debugKecamatan.status() to check status.');
