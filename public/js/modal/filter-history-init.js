// Get data from data attributes
document.addEventListener('DOMContentLoaded', function() {
    const dialog = document.getElementById('filter_history');
    if (dialog) {
        const currentFormTypes = dialog.dataset.currentFormTypes;
        const jenisSekolahParam = dialog.dataset.jenisSekolahParam;
        
        // Initialize window data
        window.filterHistoryData = {
            currentFormTypes: currentFormTypes ? JSON.parse(currentFormTypes) : [],
            jenisSekolahParam: jenisSekolahParam && jenisSekolahParam !== 'null' ? jenisSekolahParam : null
        };
    }
});
