<dialog id="remove_inspection" class="modal">
    <form id="rm-inspection-form" method="POST" action="{{ $destroyRoute }}" class="modal-box max-w-[27rem]">
        @csrf
        @method('DELETE')

        <h3 class="font-bold text-lg">Konfirmasi Hapus Hasil Inspeksi</h3>

        <p>Apakah anda yakin untuk menghapus hasil inspeksi ini?</p>

        <div class="flex justify-end gap-1.5 mt-6">
            <button type="submit" class="btn btn-error">IYA</button>
            <button type="button" class="btn btn-outline" onclick="remove_inspection.close()">BATAL</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>