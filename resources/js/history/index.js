/**
 * JavaScript for History Index Page
 * Handles delete confirmation functionality
 */

function showDeleteConfirmation(inspectionName, formIndex) {
    showDeleteConfirmationModal(
        'Hapus Hasil Inspeksi',
        `Apakah Anda yakin ingin menghapus hasil inspeksi "${inspectionName}"? Data yang dihapus tidak dapat dikembalikan.`,
        function() {
            document.getElementById('deleteForm' + formIndex).submit();
        }
    );
}