// Confirmation Modal JavaScript Functions

let confirmationCallback = null;
let deleteConfirmationCallback = null;

// Show general confirmation modal
function showConfirmationModal(title, message, callback) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    confirmationCallback = callback;
    document.getElementById('confirmationModal').classList.add('modal-open');
}

// Close general confirmation modal
function closeConfirmationModal() {
    document.getElementById('confirmationModal').classList.remove('modal-open');
    confirmationCallback = null;
}

// Show delete confirmation modal
function showDeleteConfirmationModal(title, message, callback) {
    document.getElementById('deleteModalTitle').textContent = title;
    document.getElementById('deleteModalMessage').textContent = message;
    deleteConfirmationCallback = callback;
    document.getElementById('deleteConfirmationModal').classList.add('modal-open');
}

// Close delete confirmation modal
function closeDeleteConfirmationModal() {
    document.getElementById('deleteConfirmationModal').classList.remove('modal-open');
    deleteConfirmationCallback = null;
}

// Initialize confirmation modal event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners for confirm buttons
    const confirmButton = document.getElementById('confirmButton');
    if (confirmButton) {
        confirmButton.addEventListener('click', function() {
            if (confirmationCallback) {
                confirmationCallback();
            }
            closeConfirmationModal();
        });
    }

    const deleteConfirmButton = document.getElementById('deleteConfirmButton');
    if (deleteConfirmButton) {
        deleteConfirmButton.addEventListener('click', function() {
            if (deleteConfirmationCallback) {
                deleteConfirmationCallback();
            }
            closeDeleteConfirmationModal();
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmationModal();
            closeDeleteConfirmationModal();
        }
    });
});