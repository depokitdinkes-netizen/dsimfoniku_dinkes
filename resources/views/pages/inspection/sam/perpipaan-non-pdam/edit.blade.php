@extends('layouts.app')

@section('content')
<x-section.page-header title="Ubah Informasi / Penilaian SAM Perpipaan Non PDAM" />

<x-breadcrumb.edit-inspection showRoute="{{ route('perpipaan-non-pdam.show', ['perpipaan_non_pdam' => $form_data['id']]) }}" />

<form action="{{ route('perpipaan-non-pdam.update', ['perpipaan_non_pdam' => $form_data['id']]) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- INFORMASI UMUM -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6">
        <div class="bg-white p-6 sm:p-8 rounded-xl">
            <h1 class="font-bold text-xl">Informasi Umum</h1>
            <hr class="my-5" />
            <div class="grid grid-flow-row md:grid-cols-2 gap-5">
                @foreach ($informasi_umum as $form_input)

                @switch($form_input['name'])
                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.edit :data="$form_data['instansi-pemeriksa']" />
                @break

                @case('u001')
                <div class="input-group">
                    <label for="u001">{{ $form_input['label'] }}</label>
                    <select id="u001-select" class="select select-bordered" required>
                        <option value="" disabled>Pilih</option>
                        <option value="Pasar" @if($form_data['u001']=='Pasar' ) selected @endif>Pasar</option>
                        <option value="Sekolah" @if($form_data['u001']=='Sekolah' ) selected @endif>Sekolah</option>
                        <option value="Masyarakat" @if($form_data['u001']=='Masyarakat' ) selected @endif>Masyarakat</option>
                        <option value="Tempat Pengolahan Pangan" @if($form_data['u001']=='Tempat Pengolahan Pangan' ) selected @endif>Tempat Pengolahan Pangan</option>
                        <option value="Other" @if($form_data['u001']!='Pasar' && $form_data['u001']!='Sekolah' && $form_data['u001']!='Tempat Pengolahan Pangan' ) selected @endif>Yang Lain</option>
                    </select>
                    <input type="text" id="u001" name="u001" placeholder="Ketikan Kategori" class="@if($form_data['u001']=='Pasar' || $form_data['u001']=='Sekolah' || $form_data['u001']=='Tempat Pengolahan Pangan') hidden @endif" value="{{ $form_data['u001'] }}" required />

                    <script>
                        $(document).ready(function() {
                            $('#u001-select').change(function() {
                                $('#u001').val(this.value);

                                if (this.value == 'Other') {
                                    $('#u001').removeClass('hidden');
                                    $('#u001').prop('required', true);
                                    $('#u001').val('');
                                } else {
                                    $('#u001').addClass('hidden');
                                    $('#u001').removeAttr('required');
                                }
                            })
                        })
                    </script>
                </div>
                @break

                @case('sk-pengelola')
                <div class="input-group">
                    <label for="sk-pengelola-select">{{ $form_input['label'] }}</label>
                    <select id="sk-pengelola-select" class="select select-bordered" required>
                        <option value="" disabled>Pilih</option>
                        <option value="1" @if($form_data['sk-pengelola']) selected @endif>Ada</option>
                        <option value="-1" @if(!$form_data['sk-pengelola']) selected @endif>Tidak Ada</option>
                    </select>
                    <input type="number" id="sk-pengelola" name="sk-pengelola" placeholder="Ketikan SK" class="@if(!$form_data['sk-pengelola']) hidden @endif" value="{{ $form_data['sk-pengelola'] }}" />

                    <script>
                        $(document).ready(function() {
                            $('#sk-pengelola-select').change(function() {
                                if (this.value == 1) {
                                    $('#sk-pengelola').removeClass('hidden');
                                    $('#sk-pengelola').prop('required', true)
                                } else {
                                    $('#sk-pengelola').addClass('hidden');
                                    $('#sk-pengelola').val('');
                                    $('#sk-pengelola').removeAttr('required');
                                }
                            })
                        })
                    </script>
                </div>
                @break

                @case('kecamatan')
                <div class="input-group">
                    <label for="kec">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kec" class="select select-bordered" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                @break

                @case('kelurahan')
                <div class="input-group">
                    <label for="kel">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kel" class="select select-bordered" required>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                </div>
                @break

                @case('status-operasi')
                <div class="input-group">
                    <label for="status-operasi">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="status-operasi" class="select select-bordered" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option @if($form_data['status-operasi']) selected @endif value="1">Masih Beroperasi</option>
                        <option @if(!$form_data['status-operasi']) selected @endif value="0">Tidak Beroperasi</option>
                    </select>
                </div>
                @break
                @case('dokumen_slhs')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    @if($form_data['dokumen_slhs'])
                    <div class="mb-2">
                        <span class="text-sm text-gray-600">Link saat ini: </span>
                        <a href="{{ $form_data['dokumen_slhs'] }}" target="_blank" class="text-blue-600 hover:underline">
                            {{ $form_data['dokumen_slhs'] }}
                        </a>
                    </div>
                    @endif
                    <input type="url" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" placeholder="https://drive.google.com/... atau link dokumen lainnya" class="input input-bordered" />
                    <div class="text-sm text-gray-500 mt-1">Masukkan link dokumen SLHS (Google Drive, OneDrive, atau penyimpanan cloud lainnya)</div>
                </div>
                @break

                @case('slhs_issued_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" onchange="calculateSlhsExpireDate()" class="input input-bordered" />
                </div>
                @break

                @case('slhs_expire_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }} <span class="text-xs text-gray-500">(Auto dari Issued Date + 3 tahun)</span></label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" readonly class="input input-bordered bg-gray-100" />
                </div>
                @break


                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" class="join-item w-full" placeholder="-6.324667, 106.891268" required value="{{ $form_data['koordinat'] }}" required />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" />
                </div>
                @endswitch
                @endforeach
            </div>
        </div>
    </div>

    <!-- FORM PENILAIAN -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6 flex flex-wrap lg:flex-nowrap gap-5">
        <div>
            <div class="bg-white flex-grow pb-4 mb-6 rounded-xl">
                <div class="p-6 sm:p-8">
                    <h1 class="font-bold text-xl">Kualitas Fisik Air</h1>
                </div>

                @foreach ($kualitas_fa as $form_input)
                @if($form_input['type'] == 'checkbox')
                <div class="px-3 sm:px-8">
                    <div class="p-4 border rounded mb-3">
                        <div class="flex gap-1 font-medium">
                            <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        </div>
                        <hr class="mt-3 mb-2" />
                        <div class="flex gap-5">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="1" @if(0 < $form_data[$form_input['name']]) checked @endif />
                                    <span class="label-text">Sesuai</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="0" @if($form_data[$form_input['name']]==0) checked @endif />
                                    <span class="label-text">Tidak Sesuai</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                <div class="px-6 sm:px-8">
                    <hr class="my-6" />
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <label for="kfa005">{{ $kualitas_fa[4]['label'] }}</label>
                        <select name="kfa005" id="kfa005" class="select select-bordered w-full" required>
                            <option value="" disabled>Pilih</option>
                            <option value="0" @if($form_data['kfa005']==0) selected @endif>Tidak digunakan untuk air minum, hanya untuk MCK
                            </option>
                            <option value="1" @if($form_data['kfa005']==1) selected @endif>Ya, dengan cara direbus terlebih dahulu dan digunakan untuk masak
                            </option>
                            <option value="2" @if($form_data['kfa005']==2) selected @endif>Ya, dan langsung diminum dari keran
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bg-white flex-grow pb-4 rounded-xl">
                <div class="p-8">
                    <h1 class="font-bold text-xl">Data Khusus Penilaian Resiko</h1>
                </div>

                @foreach ($data_kpr as $index => $form_input)
                @switch($form_input['type'])

                @case('select')
                <div class="px-3 sm:px-8">
                    <div class="p-4 border rounded mb-3">
                        <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                            <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                            <span class="badge badge-outline badge-primary">+{{ $form_input['option'][1]['value'] }}</span>
                        </div>
                        <hr class="mt-3 mb-2" />
                        <div class="flex gap-5">
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="{{ $form_input['option'][0]['value'] }}" @if($form_data[$form_input['name']]==0) checked @endif />
                                    <span class="label-text">Tidak</span>
                                </label>
                            </div>
                            <div class="form-control">
                                <label class="label cursor-pointer justify-start gap-2">
                                    <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['option'][1]['value'] }}" @if(0 < $form_data[$form_input['name']]) checked @endif />
                                    <span class="label-text">Ya</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                @break

                @endswitch
                @endforeach

                <div id="catatan-lain" class="px-6 sm:px-8 pt-2">
                    <label for="catatan-lain" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Hasil IKL</label>
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <textarea name="catatan-lain" id="catatan-lain" class="resize-none h-52" required placeholder="Catatan mengenai hasil inpeksi lingkungan kesehatan...">{{ $form_data['catatan-lain'] }}</textarea>
                    </div>
                </div>

                <div id="rencana-tindak-lanjut" class="px-6 sm:px-8 pt-2">
                    <label for="rencana-tindak-lanjut" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Rencana Tindak Lanjut</label>
                </div>

                <div class="px-6 sm:px-8">
                    <div class="input-group">
                        <textarea name="rencana-tindak-lanjut" id="rencana-tindak-lanjut" class="resize-none h-52" required placeholder="Rencana untuk tindak lanjut kedepannya setelah inpeksi lingkungan kesehatan...">{{ $form_data['rencana-tindak-lanjut'] }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky top-5 h-fit w-full min-w-32 sm:w-fit md:min-w-72">

            <button class="btn btn-primary btn-block" name="action" value="update">SIMPAN PENILAIAN</button>
            <button class="btn btn-info btn-outline btn-block mt-5" name="action" value="duplicate">DUPLIKAT PENILAIAN</button>
        </div>

    </div>
</form>

<x-modal.get-lat-long />
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script>
    $(document).ready(function() {
        let kecVal = "{{ $form_data['kecamatan'] }}";
        let kelVal = "{{ $form_data['kelurahan'] }}";

        let checkKec = setInterval(function() {
            if (kecamatan.length > 0) {
                let options = "";

                kecamatan.forEach((el) => {
                    options += `<option value="${el.name}" ${kecVal == el.name && 'selected'}>${el.name}</option>`;
                });

                $("#kec").html('<option>Pilih Kelurahan</option>');
                $("#kec").html($("#kec").html() + options);

                let kecId = kecamatan.find((el) => el.name == kecVal).id;

                fetch(
                        `https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${kecId}.json`
                    )
                    .then((response) => response.json())
                    .then((villages) => {
                        let options = "";
                        villages.forEach((el) => {
                            options += `<option value="${el.name}" ${kelVal == el.name && 'selected'}>${el.name}</option>`;
                        });

                        $("#kel").html($("#kel").html() + options);
                    });

                clearInterval(checkKec);
            }
        }, 500)
    });
</script>

<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection

@section('script')
<script>
function calculateSlhsExpireDate() {
    const issuedDate = document.getElementById('slhs_issued_date').value;
    if (issuedDate) {
        const issued = new Date(issuedDate);
        const expired = new Date(issued);
        expired.setFullYear(expired.getFullYear() + 3);
        
        const expiredDateString = expired.toISOString().split('T')[0];
        document.getElementById('slhs_expire_date').value = expiredDateString;
    }
}

// Calculate expire date on page load if issued date exists
document.addEventListener('DOMContentLoaded', function() {
    const issuedDate = document.getElementById('slhs_issued_date').value;
    if (issuedDate) {
        calculateSlhsExpireDate();
    }
});
</script>
@endsection
