/**
 * JavaScript functionality for Instansi Pemeriksa component
 * Handles both create and edit forms
 */

/**
 * Toggle function for create form
 * Shows/hides the "Lainnya" input field based on selection
 */
function toggleInstansiLainnya() {
    const select = document.getElementById('instansi-pemeriksa');
    const lainnyaGroup = document.getElementById('instansi-lainnya-group');
    const lainnyaInput = document.getElementById('instansi-lainnya');
    
    if (select.value === 'Lainnya') {
        lainnyaGroup.style.display = 'block';
        lainnyaInput.required = true;
    } else {
        lainnyaGroup.style.display = 'none';
        lainnyaInput.required = false;
        lainnyaInput.value = '';
    }
}

/**
 * Toggle function for edit form
 * Shows/hides the "Lainnya" input field based on selection
 */
function toggleInstansiLainnyaEdit() {
    const select = document.getElementById('instansi-pemeriksa');
    const lainnyaGroup = document.getElementById('instansi-lainnya-group-edit');
    const lainnyaInput = document.getElementById('instansi-lainnya-edit');
    
    if (select.value === 'Lainnya') {
        lainnyaGroup.style.display = 'block';
        lainnyaInput.required = true;
    } else {
        lainnyaGroup.style.display = 'none';
        lainnyaInput.required = false;
        lainnyaInput.value = '';
    }
}