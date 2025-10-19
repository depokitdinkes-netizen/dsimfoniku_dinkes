// Get data from data attributes
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('get_lat_long');
    if (modal) {
        const existingLat = modal.dataset.existingLat;
        const existingLng = modal.dataset.existingLng;
        
        // Initialize window data
        window.getLatLongData = {
            existingLat: existingLat && existingLat !== 'null' ? parseFloat(existingLat) : null,
            existingLng: existingLng && existingLng !== 'null' ? parseFloat(existingLng) : null
        };
    }
});
