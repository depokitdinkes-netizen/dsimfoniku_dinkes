@extends('layouts.app')

@section('content')
@use('Illuminate\Support\Facades\Storage')

<x-section.page-header title="Ubah Informasi / Penilaian Sekolah" />

@if($errors->any())
<div class="alert alert-error mx-3 sm:mx-6 mb-5">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <div class="font-semibold">Terdapat kesalahan dalam pengisian form:</div>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert alert-error mx-3 sm:mx-6 mb-5">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="whitespace-pre-line">{{ session('error') }}</div>
    </div>
</div>
@endif

@if(session('error_details'))
<div class="mx-3 sm:mx-6 mb-5">
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-red-800 mb-2">Detail Error (untuk debugging)</h3>
                <div class="relative">
                    <textarea 
                        id="errorDetails" 
                        readonly 
                        class="w-full h-32 p-3 text-xs font-mono bg-white border border-red-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        style="font-family: 'Courier New', monospace;">{{ session('error_details') }}</textarea>
                    <button 
                        type="button" 
                        onclick="copyErrorDetails()" 
                        class="absolute top-2 right-2 px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Copy
                    </button>
                </div>
                <p class="mt-2 text-xs text-red-600">Anda dapat meng-copy error details di atas untuk debugging atau melaporkan masalah.</p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/inspection/sekolah/copy-error-details.js') }}"></script>
@endif

@if(session('success'))
<div class="alert alert-success mx-3 sm:mx-6 mb-5">
    {{ session('success') }}
</div>
@endif

<x-breadcrumb.edit-inspection showRoute="{{ route('sekolah.show', ['sekolah' => $form_data['id']]) }}" />

<form action="{{ route('sekolah.update', ['sekolah' => $form_data['id']]) }}" method="POST" enctype="multipart/form-data">
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
                @case('jenis_sekolah')
                <div class="input-group">
                    <label for="jenis_sekolah">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="jenis_sekolah" class="select select-bordered" required>
                        <option value="" disabled>Pilih Jenis Sekolah</option>
                        <option value="TK" @if($form_data['jenis_sekolah'] == 'TK') selected @endif>TK (Taman Kanak-Kanak)</option>
                        <option value="PAUD" @if($form_data['jenis_sekolah'] == 'PAUD') selected @endif>PAUD (Pendidikan Anak Usia Dini)</option>
                        <option value="SD" @if($form_data['jenis_sekolah'] == 'SD') selected @endif>SD (Sekolah Dasar)</option>
                        <option value="MI" @if($form_data['jenis_sekolah'] == 'MI') selected @endif>MI (Madrasah Ibtidaiyah)</option>
                        <option value="SMP" @if($form_data['jenis_sekolah'] == 'SMP') selected @endif>SMP (Sekolah Menengah Pertama)</option>
                        <option value="MTs" @if($form_data['jenis_sekolah'] == 'MTs') selected @endif>MTs (Madrasah Tsanawiyah)</option>
                        <option value="SMA" @if($form_data['jenis_sekolah'] == 'SMA') selected @endif>SMA (Sekolah Menengah Atas)</option>
                        <option value="MA" @if($form_data['jenis_sekolah'] == 'MA') selected @endif>MA (Madrasah Aliyah)</option>
                        <option value="SMK" @if($form_data['jenis_sekolah'] == 'SMK') selected @endif>SMK (Sekolah Menengah Kejuruan)</option>
                    </select>
                </div>
                @break

                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.edit :data="$form_data['instansi-pemeriksa']" />
                @break

                @case('kecamatan')
                <div class="input-group">
                    <label for="kec">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kec" class="select select-bordered" required>
                        <option value="">Memuat kecamatan...</option>
                        @if($form_data['kecamatan'])
                        <option value="{{ $form_data['kecamatan'] }}" selected>{{ $form_data['kecamatan'] }}</option>
                        @endif
                    </select>
                </div>
                @break

                @case('kelurahan')
                <div class="input-group">
                    <label for="kel">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kel" class="select select-bordered" required>
                        <option value="">Memuat kelurahan...</option>
                        @if($form_data['kelurahan'])
                        <option value="{{ $form_data['kelurahan'] }}" selected>{{ $form_data['kelurahan'] }}</option>
                        @endif
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
                @break@case('koordinat')
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
                    <div class="flex gap-1 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <span class="badge badge-outline badge-primary ml-auto">+{{ $form_input['score'] }}</span>
                    </div>
                    <hr class="mt-3 mb-2" />
                    <div class="flex gap-5">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['score'] }}" @if(0 < $form_data[$form_input['name']]) checked @endif />
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
                <p class="font-semibold text-sm @if ($index > 0) mt-5 @endif">{{ $heading['label'] }}</p>
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
            <button class="btn btn-info btn-outline btn-block mt-5" name="action" value="duplicate" onclick="return validateDuplicate()">DUPLIKAT PENILAIAN</button>
        </div>

    </div>
</form>

<x-modal.get-lat-long />

<script>
    window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/inspection/sekolah/edit.js') }}"></script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
