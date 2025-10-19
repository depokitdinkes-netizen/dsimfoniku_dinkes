/**
 * JavaScript for History Archived Page
 * Handles unarchive confirmation functionality
 */

function showUnarchiveConfirmation(inspectionName, sudUrl) {
    showConfirmationModal(
        'Pemulihan Hasil Inspeksi',
        `Apakah Anda yakin ingin memulihkan hasil inspeksi "${inspectionName}"? Data akan dipindahkan kembali ke histori inspeksi.`,
        function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.baseUrl + '/' + sudUrl;
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = window.csrfToken;
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