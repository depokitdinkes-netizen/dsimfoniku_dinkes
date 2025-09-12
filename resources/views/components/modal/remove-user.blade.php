<dialog id="remove_user" class="modal">
    <form method="POST" action="{{ route('manajemen-user.destroy', ['manajemen_user' => $userId]) }}" class="modal-box max-w-[26rem]">
        @csrf
        @method('DELETE')

        <h3 class="font-bold text-lg">Konfirmasi Hapus User</h3>

        <p>Apakah anda yakin untuk menghapus user ini?</p>

        <div class="flex justify-end gap-1.5 mt-6">
            <button type="submit" class="btn btn-error">IYA</button>
            <button type="button" class="btn btn-outline" onclick="remove_user.close()">BATAL</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>