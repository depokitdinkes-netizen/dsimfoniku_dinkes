@props(['form_data'])

@php
    $data = $form_data['instansi-pemeriksa'] ?? '';
@endphp

<div class="input-group">
    <label for="instansi-pemeriksa">Instansi Pemeriksa</label>
    <select name="instansi-pemeriksa" id="instansi-pemeriksa" class="select select-bordered" required onchange="toggleInstansiLainnyaEdit()">
        <option value="" disabled>Pilih Instansi Pemeriksa</option>
        <option value="Dinas Kesehatan" @if($data == 'Dinas Kesehatan') selected @endif>Dinas Kesehatan</option>
        <option value="Puskesmas" @if($data == 'Puskesmas') selected @endif>Puskesmas</option>
        <option value="Lainnya" @if(!in_array($data, ['Dinas Kesehatan', 'Puskesmas', '']) && $data != '') selected @endif>Lainnya</option>
    </select>
</div>

<!-- Input manual untuk instansi lainnya -->
<div class="input-group" id="instansi-lainnya-group-edit" style="display: {{ !in_array($data, ['Dinas Kesehatan', 'Puskesmas', '']) && $data != '' ? 'block' : 'none' }};">
    <label for="instansi-lainnya-edit">Nama Instansi Lainnya</label>
    <input type="text" id="instansi-lainnya-edit" name="instansi-lainnya" class="input input-bordered w-full" placeholder="Masukkan nama instansi" value="{{ !in_array($data, ['Dinas Kesehatan', 'Puskesmas', '']) ? $data : '' }}" />
</div>

<script>
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
</script>
