// Utility functions for duplicate confirmation modal
// This script can be included in any form that has duplicate functionality

function validateDuplicateFields() {
    const kecamatan = document.getElementById('kec');
    const kelurahan = document.getElementById('kel');
    
    if (!kecamatan || !kelurahan) {
        // If no kec/kel dropdowns, assume it's valid
        return true;
    }
    
    if (!kecamatan.value || kecamatan.value === '' || kecamatan.value === 'Pilih Kecamatan') {
        alert('Kecamatan harus dipilih sebelum melakukan duplikasi penilaian');
        return false;
    }
    
    if (!kelurahan.value || kelurahan.value === '' || kelurahan.value === 'Pilih Kelurahan') {
        alert('Kelurahan harus dipilih sebelum melakukan duplikasi penilaian');
        return false;
    }
    
    return true;
}

function showDuplicateConfirmation(inspectionType = 'penilaian') {
    if (validateDuplicateFields()) {
        showConfirmationModal(
            'Duplikat Penilaian',
            `Apakah Anda yakin ingin membuat duplikat dari ${inspectionType} ini? Data akan disalin dengan informasi yang sama.`,
            function() {
                // Create hidden input for action
                const form = document.querySelector('form');
                if (form) {
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'duplicate';
                    form.appendChild(actionInput);
                    
                    // Submit form
                    form.submit();
                } else {
                    console.error('Form not found for duplicate submission');
                }
            }
        );
    }
}

// Auto-setup for common duplicate button
document.addEventListener('DOMContentLoaded', function() {
    const duplicateButton = document.querySelector('button[onclick*="showDuplicateConfirmation"]');
    if (duplicateButton && !window.duplicateSetup) {
        window.duplicateSetup = true;
        console.log('Duplicate confirmation utility loaded');
    }
});
