// Filter History Modal JavaScript Functions

// Toggle visibility of "Jenis Sekolah" filter based on "sekolah" checkbox
function toggleSekolahFilter() {
    const sekolahCheckbox = document.getElementById('sekolah');
    const jenisSekolahFilter = document.getElementById('jenis-sekolah-filter');
    const jenisSekolahSelect = document.getElementById('jenis_sekolah');
    
    if (sekolahCheckbox && jenisSekolahFilter) {
        if (sekolahCheckbox.checked) {
            jenisSekolahFilter.style.display = 'block';
        } else {
            jenisSekolahFilter.style.display = 'none';
            if (jenisSekolahSelect) {
                jenisSekolahSelect.value = ''; // Reset pilihan jenis sekolah
            }
        }
    }
}

// Initialize filter history modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if sekolah was selected from previous request
    const sekolahCheckbox = document.getElementById('sekolah');
    
    // Get data from window object (passed from server-side)
    if (window.filterHistoryData) {
        const currentFormTypes = window.filterHistoryData.currentFormTypes || [];
        const jenisSekolahParam = window.filterHistoryData.jenisSekolahParam;
        
        if (sekolahCheckbox && currentFormTypes.includes('sekolah')) {
            sekolahCheckbox.checked = true;
        }
        
        // If there's jenis_sekolah parameter in URL, show filter
        if (jenisSekolahParam && sekolahCheckbox) {
            sekolahCheckbox.checked = true;
        }
    }
    
    toggleSekolahFilter();
    
    // Add event listener for all form type checkboxes
    const formTypeCheckboxes = document.querySelectorAll('.form-type-checkbox');
    formTypeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleSekolahFilter);
    });
});