<dialog id="reminder_before_submit" class="modal">
    <div class="modal-box" enctype="multipart/form-data">
        <h3 class="font-bold text-lg">Konfirmasi Submit Inspeksi</h3>

        <p>Apakah anda yakin sudah mengisi semua inspeksi kesehatan lingkungan</p>
        <hr class="my-2" />
        <p class="text-gray-400">Reminder: Jangan lupa ambil foto Geotagging</p>

        <div class="flex justify-end mt-6">
            <button onclick="document.getElementById('reminder_before_submit').close()" type="submit" class="btn btn-primary">SUBMIT PENILAIAN</button>
        </div>

    </div>
    <div class="modal-backdrop">
        <button onclick="document.getElementById('reminder_before_submit').close()" type="button">close</button>
    </div>
</dialog>