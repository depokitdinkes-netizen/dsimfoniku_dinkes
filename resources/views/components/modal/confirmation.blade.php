<!-- Confirmation Modal -->
<div id="confirmationModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="modalTitle">Konfirmasi</h3>
        <p class="py-4" id="modalMessage">Apakah Anda yakin ingin melakukan tindakan ini?</p>
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="closeConfirmationModal()">Batal</button>
            <button type="button" class="btn btn-primary" id="confirmButton">Ya, Lanjutkan</button>
        </div>
    </div>
    <button class="modal-backdrop" onclick="closeConfirmationModal()" onkeydown="if(event.key==='Enter'||event.key===' ')closeConfirmationModal()" tabindex="0" aria-label="Close modal"></button>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="modal">
    <div class="modal-box">
        <div class="flex items-center mb-4">
            <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="font-bold text-lg text-red-600" id="deleteModalTitle">Hapus Data</h3>
        </div>
        <p class="py-4" id="deleteModalMessage">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="closeDeleteConfirmationModal()">Batal</button>
            <button type="button" class="btn btn-error" id="deleteConfirmButton">Ya, Hapus</button>
        </div>
    </div>
    <button class="modal-backdrop" onclick="closeDeleteConfirmationModal()" onkeydown="if(event.key==='Enter'||event.key===' ')closeDeleteConfirmationModal()" tabindex="0" aria-label="Close modal"></button>
</div>

<script>
    let confirmationCallback = null;
    let deleteConfirmationCallback = null;

    function showConfirmationModal(title, message, callback) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        confirmationCallback = callback;
        document.getElementById('confirmationModal').classList.add('modal-open');
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.remove('modal-open');
        confirmationCallback = null;
    }

    function showDeleteConfirmationModal(title, message, callback) {
        document.getElementById('deleteModalTitle').textContent = title;
        document.getElementById('deleteModalMessage').textContent = message;
        deleteConfirmationCallback = callback;
        document.getElementById('deleteConfirmationModal').classList.add('modal-open');
    }

    function closeDeleteConfirmationModal() {
        document.getElementById('deleteConfirmationModal').classList.remove('modal-open');
        deleteConfirmationCallback = null;
    }

    // Event listeners for confirm buttons
    document.getElementById('confirmButton').addEventListener('click', function() {
        if (confirmationCallback) {
            confirmationCallback();
        }
        closeConfirmationModal();
    });

    document.getElementById('deleteConfirmButton').addEventListener('click', function() {
        if (deleteConfirmationCallback) {
            deleteConfirmationCallback();
        }
        closeDeleteConfirmationModal();
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmationModal();
            closeDeleteConfirmationModal();
        }
    });
</script>
