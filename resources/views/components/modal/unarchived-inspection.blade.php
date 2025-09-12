<dialog id="unarchive_inspection" class="modal">
    <form id="ua-inspection-form" action="" method="POST" class="modal-box max-w-[27rem]">
        @method('DELETE')
        @csrf

        <h3 class="font-bold text-lg">Konfirmasi Pemulihan Hasil Inspeksi</h3>

        <p>Apakah anda yakin untuk memulihkan hasil inspeksi ini?</p>

        <div class="flex justify-end gap-1.5 mt-6">
            <button type="submit" class="btn btn-success">IYA</button>
            <button type="button" class="btn btn-outline" onclick="unarchive_inspection.close()">BATAL</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>