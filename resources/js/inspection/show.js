// Show page JavaScript functionality
// Extracted from show.blade.php

// Global variables that will be set by the Blade template
let geraiDeleteBaseURL;

function showDeleteGeraiConfirmation(geraiName, geraiId) {
    showDeleteConfirmationModal(
        'Hapus Gerai',
        `Apakah Anda yakin ingin menghapus gerai "${geraiName}"? Data yang dihapus tidak dapat dikembalikan.`,
        function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = geraiDeleteBaseURL.substr(0, geraiDeleteBaseURL.length - 1) + geraiId;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = window.showPageData.csrfToken;
            form.appendChild(csrfToken);
            
            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    );
}

function showDeleteMainInspectionConfirmation() {
    showDeleteConfirmationModal(
        'Hapus Hasil Inspeksi',
        'Apakah Anda yakin ingin menghapus hasil inspeksi ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.showPageData.destroyRoute;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = window.showPageData.csrfToken;
            form.appendChild(csrfToken);
            
            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    );
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set the global variable from window data
    if (window.showPageData && window.showPageData.geraiDeleteBaseURL) {
        geraiDeleteBaseURL = window.showPageData.geraiDeleteBaseURL;
    }
});