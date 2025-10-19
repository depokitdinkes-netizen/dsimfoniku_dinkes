<div class="input-group">
    <label for="instansi-pemeriksa">Instansi Pemeriksa</label>
    <select id="instansi-pemeriksa" name="instansi-pemeriksa" class="select select-bordered" required onchange="toggleInstansiLainnya()">
        <option value="" disabled selected>Pilih</option>
        <option value="Dinas Kesehatan">Dinas Kesehatan</option>
        <option value="Puskesmas">Puskesmas</option>
        <option value="Lainnya">Lainnya</option>
    </select>
</div>

<!-- Input manual untuk instansi lainnya -->
<div class="input-group" id="instansi-lainnya-group" style="display: none;">
    <label for="instansi-lainnya">Nama Instansi Lainnya</label>
    <input type="text" id="instansi-lainnya" name="instansi-lainnya" class="input input-bordered w-full" placeholder="Masukkan nama instansi" />
</div>

@push('scripts')
<script src="{{ asset('js/instansi-pemeriksa.js') }}"></script>
@endpush
