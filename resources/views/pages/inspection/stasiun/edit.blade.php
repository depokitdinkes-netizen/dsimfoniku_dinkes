@extends('layouts.app')

@section('content')
@use('Illuminate\Support\Facades\Storage')

<x-section.page-header title="Ubah Informasi / Penilaian Stasiun" />

@if($errors->any())
<div class="alert alert-error mx-3 sm:mx-6 mb-5">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div class="alert alert-error mx-3 sm:mx-6 mb-5">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success mx-3 sm:mx-6 mb-5">
    {{ session('success') }}
</div>
@endif

<x-breadcrumb.edit-inspection showRoute="{{ route('stasiun.show', ['stasiun' => $form_data['id']]) }}" />

<form action="{{ route('stasiun.update', ['stasiun' => $form_data['id']]) }}" method="POST" enctype="multipart/form-data">
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
                        <option value="" disabled>Pilih Status</option>
                        <option @if($form_data['status-operasi']) selected @endif value="1">Masih Beroperasi</option>
                        <option @if(!$form_data['status-operasi']) selected @endif value="0">Tidak Beroperasi</option>
                    </select>
                </div>
                @break

                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" value="{{ $form_data['koordinat'] }}" class="join-item w-full" placeholder="-6.324667, 106.891268" required />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                @break

                @case('kontak')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="text" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" pattern="[0-9]*" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" required />
                </div>
                @endswitch

                @endforeach
            </div>
        </div>
    </div>

    <!-- FORM PENILAIAN -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6 flex flex-wrap lg:flex-nowrap gap-5">
        <div class="bg-white flex-grow pb-4 rounded-xl">
            <div class="p-6 sm:p-8">
                <h1 class="font-bold text-xl">Formulir Penilaian</h1>
            </div>

            @foreach ($form_penilaian as $index => $form_input)
            @switch($form_input['type'])

            @case('h2')
            <div class="text-white bg-black/40 px-6 sm:px-8 py-4 mb-6 @if ($index > 0) mt-10 @endif">
                <h2 class="font-semibold text-lg relative">{{ $form_input['label'] }}</h2>
            </div>

            @break

            @case('h3')
            <div id="{{ $form_input['id'] }}" class="px-6 sm:px-8 pt-2">
                <h3 class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">{{ $form_input['label'] }}</h3>
            </div>
            @break

            @case('checkbox')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <span class="badge badge-outline badge-primary">+{{ $form_input['score'] }}</span>
                    </div>
                    <hr class="mt-3 mb-2" />
                    <div class="flex gap-5">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['score'] }}" @if($form_data[$form_input['name']]==$form_input['score']) checked @endif />
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
            @break

            @default

            @endswitch
            @endforeach

            <div class="text-white bg-black/40 px-6 sm:px-8 py-4 mb-6 mt-10">
                <h2 class="font-semibold text-lg relative">Hasil Pengukuran</h2>
            </div>

            @foreach ($hasil_pengukuran as $index => $form_input)
            <div class="px-6 sm:px-8 mt-5">
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ $form_data[$form_input['name']] }}" required />
                </div>
            </div>
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

        <div class="sticky top-5 h-fit min-w-72 w-full lg:w-fit">
            <div class="bg-white p-6 max-h-[30rem] overflow-y-auto hidden lg:block mb-5 rounded-xl">
                @foreach ($form_penilaian as $index => $heading)

                @switch($heading['type'])
                @case('h2')
                <p class="font-semibold text-sm @if ($index > 0)
                mt-5
            @endif">{{ $heading['label'] }}</p>
                @break
                @case('h3')
                <a href="#{{ $heading['id'] }}" class="text-blue-500 text-sm my-2 block ml-2 underline ">{{ $heading['label'] }}</a>
                @break
                @endswitch

                @endforeach

                <a href="#catatan-lain" class="text-blue-500 text-sm my-2 block ml-2 underline">Hasil IKL</a>
                <a href="#rencana-tindak-lanjut" class="text-blue-500 text-sm my-2 block ml-2 underline">Rencana Tindak Lanjut</a>
            </div>
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
        
        console.log('Edit form initialized with:', { kecVal, kelVal });

        // Function to populate kecamatan dropdown with existing value
        function populateKecamatan() {
            if (kecamatan.length > 0) {
                let options = '<option value="">Pilih Kecamatan</option>';
                
                kecamatan.forEach((el) => {
                    const selected = kecVal == el.name ? 'selected' : '';
                    options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                });
                
                $("#kec").html(options);
                $("#kec").prop('disabled', false);
                
                // If there's a pre-selected kecamatan, load its kelurahan
                if (kecVal) {
                    populateKelurahan(kecVal);
                }
                
                return true;
            }
            return false;
        }
        
        // Function to populate kelurahan dropdown
        function populateKelurahan(kecamatanName) {
            const selectedKec = kecamatan.find((el) => el.name === kecamatanName);
            
            if (!selectedKec) {
                console.error('Kecamatan not found:', kecamatanName);
                $("#kel").html('<option value="">Kecamatan tidak ditemukan</option>');
                $("#kel").prop('disabled', true);
                return;
            }
            
            $("#kel").html('<option value="">Memuat kelurahan...</option>');
            $("#kel").prop('disabled', true);
            
            fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((villages) => {
                    let options = '<option value="">Pilih Kelurahan</option>';
                    
                    villages.forEach((el) => {
                        const selected = kelVal == el.name ? 'selected' : '';
                        options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                    });
                    
                    $("#kel").html(options);
                    $("#kel").prop('disabled', false);
                    
                    console.log('Kelurahan loaded successfully:', villages.length, 'items');
                })
                .catch((error) => {
                    console.error('Error loading villages:', error);
                    $("#kel").html('<option value="">Gagal memuat kelurahan</option>');
                    $("#kel").prop('disabled', false);
                });
        }
        
        // Wait for kecamatan data to be loaded
        let checkKec = setInterval(function() {
            if (populateKecamatan()) {
                clearInterval(checkKec);
                console.log('Kecamatan data loaded and populated');
            } else {
                console.log('Waiting for kecamatan data...');
            }
        }, 500);
        
        // Handle kecamatan change event
        $("#kec").on('change', function() {
            let selectedValue = $(this).val();
            console.log('Kecamatan changed to:', selectedValue);
            
            if (selectedValue) {
                populateKelurahan(selectedValue);
            } else {
                $("#kel").html('<option value="">Pilih Kelurahan</option>');
                $("#kel").prop('disabled', true);
            }
        });
        
        // Fallback timeout - give more time for edit forms
        setTimeout(() => {
            if ($("#kec option").length <= 1) {
                console.warn('Kecamatan data still not loaded after 15 seconds, trying fallback');
                // Try to preserve existing values
                if (kecVal) {
                    $("#kec").html(`<option value="${kecVal}" selected>${kecVal}</option>`);
                }
                if (kelVal) {
                    $("#kel").html(`<option value="${kelVal}" selected>${kelVal}</option>`);
                }
            }
        }, 15000);
    });
</script>
@endsection
