<dialog id="export_history" class="modal">
    <form method="GET" action="" id="export-form" class="modal-box">
        @csrf
        @method('GET')

        <h3 class="font-bold text-lg">Export Histori</h3>

        <div class="flex flex-col gap-3 mt-6">

            <input type="hidden" name="export" value="excel" />

            <div class="input-group">
                <label for="form-type">Tipe Form*</label>
                <select name="form-type" id="form-type" class="select select-bordered" required>
                    <option value="" disabled selected>Pilih Tipe Form</option>
                    <option value="{{ route('akomodasi.index') }}">                 Akomodasi</option>
                    <option value="{{ route('akomodasi-lain.index') }}">            Akomodasi Lainnya</option>
                    <option value="{{ route('depot-air-minum.index') }}">           Depot Air Minum</option>
                    <option value="{{ route('tempat-olahraga.index') }}">           Gelanggang Olahraga</option>
                    <option value="{{ route('gerai-pangan-jajanan.index') }}">      Gerai Pangan Jajanan</option>
                    <option value="{{ route('gerai-jajanan-keliling.index') }}">    Gerai Pangan Jajanan Keliling</option>
                    <option value="{{ route('jasa-boga-katering.index') }}">        Jasa Boga/Katering</option>
                    <option value="{{ route('renang-pemandian.index') }}">          Kolam Renang</option>
                    <option value="{{ route('pasar.index') }}">                     Pasar</option>
                    <option value="{{ route('pasar-internal.index') }}">           Pasar Internal</option>
                    <option value="{{ route('puskesmas.index') }}">                 Puskesmas</option>
                    <option value="{{ route('restoran.index') }}">                  Restoran</option>
                    <option value="{{ route('rumah-makan.index') }}">               Rumah Makan</option>
                    <option value="{{ route('rumah-sakit.index') }}">               Rumah Sakit</option>
                    <option value="{{ route('penyimpanan-air-hujan.index') }}">     SAM Penyimpanan Air Hujan</option>
                    <option value="{{ route('perlindungan-mata-air.index') }}">     SAM Perlindungan Mata Air</option>
                    <option value="{{ route('perpipaan-non-pdam.index') }}">        SAM Perpipaan Non PDAM</option>
                    <option value="{{ route('perpipaan.index') }}">                 SAM Perpipaan PDAM</option>
                    <option value="{{ route('sumur-bor-pompa.index') }}">           SAM Sumur Bor dengan Pompa Tangan</option>
                    <option value="{{ route('sumur-gali.index') }}">                SAM Sumur Gali dengan Kerekan</option>
                    <option value="{{ route('sekolah.index') }}">                   Sekolah</option>
                    <option value="{{ route('kantin.index') }}">                    Sentra Kantin</option>
                    <option value="{{ route('stasiun.index') }}">                   Stasiun</option>
                    <option value="{{ route('tempat-rekreasi.index') }}">           Tempat Rekreasi</option>
                </select>
            </div>


            <div class="sm:col-span-2 mt-2">
                <button type="submit" class="btn btn-primary btn-block">EXPORT HISTORI <i class="ri-download-line"></i></button>
            </div>
        </div>

    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    $(document).ready(function() {
        $('#form-type').change(e => {
            if (e.target.value != "") {
                $('#export-form').attr('action', e.target.value);
            } else {
                $('#export-form').attr('action', '');
            }
        })
    })
</script>
