// Debugging script untuk kelurahan dropdown
// Tambahkan ini di console browser untuk debug

console.log('=== DEBUGGING KELURAHAN DROPDOWN ===');

// Check if jQuery is loaded
console.log('jQuery loaded:', typeof $ !== 'undefined');

// Check if getDistrictsAndVillages.js is loaded
console.log('getDistrictsAndVillages.js loaded:', typeof window.refreshKecamatanData === 'function');

// Check dropdown elements
console.log('Kecamatan dropdown exists:', $('#kec').length > 0);
console.log('Kelurahan selects exists:', $('.kelurahan-select').length);
console.log('Kelurahan admin selects exists:', $('.kelurahan-select-admin').length);

// Check if kecamatan has options
console.log('Kecamatan options count:', $('#kec option').length);
console.log('Kecamatan disabled?', $('#kec').prop('disabled'));

// Check current values
console.log('Selected kecamatan:', $('#kec').val());
console.log('Hidden kecamatan value:', $('#selected-kecamatan').val());

// Check kelurahan options
$('.kelurahan-select').each(function(index) {
    console.log(`Kelurahan select ${index} options:`, $(this).find('option').length);
    console.log(`Kelurahan select ${index} disabled?`, $(this).prop('disabled'));
    console.log(`Kelurahan select ${index} value:`, $(this).val());
});

// Test kecamatan change
console.log('\n=== TESTING KECAMATAN CHANGE ===');
$('#kec').trigger('change');

// Check if API is accessible
fetch('https://dev4ult.github.io/api-wilayah-indonesia/api/districts/3276.json')
    .then(response => {
        console.log('API accessible:', response.ok);
        return response.json();
    })
    .then(data => {
        console.log('API data received:', data.length, 'kecamatan');
    })
    .catch(error => {
        console.log('API error:', error);
    });

// Manual refresh function
window.debugRefreshKelurahan = function() {
    console.log('Manual refresh triggered');
    if (typeof window.refreshKecamatanData === 'function') {
        window.refreshKecamatanData();
    } else {
        console.log('refreshKecamatanData function not available');
    }
};

console.log('Debug script loaded. Run debugRefreshKelurahan() to manually refresh.');
