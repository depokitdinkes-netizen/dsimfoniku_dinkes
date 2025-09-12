@extends('layouts.app')

@section('content')
<x-section.page-header title="Inspeksi / Penilaian Depot Air Minum" />

<x-breadcrumb.create-inspection />

<!-- Error Alert -->
@if ($errors->any())
<div class="alert border-b-4 border-error fixed capitalize font-medium top-0 right-0 m-5 w-fit animate-fade-in z-20">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <h3 class="font-bold">Terdapat kesalahan pada form!</h3>
            <div class="text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    </div>
    <button type="button" class="close-alert btn btn-ghost btn-square btn-sm">
        <i class="ri-close-line"></i>
    </button>
</div>
@endif

<form action="{{ route('depot-air-minum.store') }}" method="POST" id="dam-form">
    @csrf
    @method('POST')
    <!-- INFORMASI UMUM -->
    <div class="px-3 pb-3 sm:px-6 sm:pb-6">
        <div class="bg-white p-6 sm:p-8 rounded-xl">
            <h1 class="font-bold text-xl">Informasi Umum</h1>
            <hr class="my-5" />
            <div class="grid grid-flow-row md:grid-cols-2 gap-5">
                @foreach ($informasi_umum as $form_input)

                @switch($form_input['name'])
                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.create />
                @break

                @case('kecamatan')
                <div class="input-group">
                    <label for="kec">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kec" class="select select-bordered @error($form_input['name']) select-error @enderror" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @case('kelurahan')
                <div class="input-group">
                    <label for="kel">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="kel" class="select select-bordered @error($form_input['name']) select-error @enderror" required>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @case('tujuan-ikl')
                <div class="input-group">
                    <label for="tujuan-ikl">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="tujuan-ikl" class="select select-bordered @error($form_input['name']) select-error @enderror" required>
                        <option value="">Pilih</option>
                        <option value="Program" {{ old($form_input['name']) == 'Program' ? 'selected' : '' }}>Program</option>
                        <option value="Perizinan SLHS" {{ old($form_input['name']) == 'Perizinan SLHS' ? 'selected' : '' }}>Perizinan SLHS</option>
                    </select>
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @case('status-operasi')
                <div class="input-group">
                    <label for="status-operasi">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="status-operasi" class="select select-bordered @error($form_input['name']) select-error @enderror" required>
                        <option value="" disabled {{ old($form_input['name']) ? '' : 'selected' }}>Pilih Status</option>
                        <option value="1" {{ old($form_input['name']) == '1' ? 'selected' : '' }}>Masih Beroperasi</option>
                        <option value="0" {{ old($form_input['name']) == '0' ? 'selected' : '' }}>Tidak Beroperasi</option>
                    </select>
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break
                @case('dokumen_slhs')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="url" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ old($form_input['name']) }}" placeholder="https://drive.google.com/... atau link dokumen lainnya" class="input input-bordered @error($form_input['name']) input-error @enderror" />
                    <div class="text-sm text-gray-500 mt-1">Masukkan link dokumen SLHS (Google Drive, OneDrive, atau penyimpanan cloud lainnya)</div>
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @case('slhs_issued_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ old($form_input['name']) }}" onchange="calculateSlhsExpireDate()" class="input input-bordered @error($form_input['name']) input-error @enderror" />
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @case('slhs_expire_date')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }} <span class="text-xs text-gray-500">(Auto dari Issued Date + 3 tahun)</span></label>
                    <input type="date" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ old($form_input['name']) }}" readonly class="input input-bordered bg-gray-100 @error($form_input['name']) input-error @enderror" />
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break


                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" class="join-item w-full @error('koordinat') input-error @enderror" placeholder="-6.324667, 106.891268" value="{{ old('koordinat') }}" required />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                    @error('koordinat')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" value="{{ old($form_input['name']) }}" class="input input-bordered @error($form_input['name']) input-error @enderror" @if(!in_array($form_input['name'], ['dokumen_slhs', 'slhs_issued_date', 'slhs_expire_date', 'u006'])) required @endif />
                    @error($form_input['name'])
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
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

            @case('h4')
            <div class="px-6 sm:px-8 pb-3 mt-4">
                <h4 class="text-base underline underline-offset-8">{{ $form_input['label'] }} :</h4>
            </div>
            @break

            @case('select')
            <div class="px-3 sm:px-8">
                <div class="p-4 border rounded mb-3">
                    <div class="flex flex-col-reverse sm:flex-row justify-between gap-2 font-medium">
                        <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                        <span class="badge badge-outline badge-error">+{{ $form_input['option'][1]['value'] }}</span>
                    </div>
                    <hr class="mt-3 mb-2" />
                    <div class="flex gap-5">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['option'][0]['value'] }}" checked />
                                <span class="label-text">{{ $form_input['option'][0]['label'] }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="{{ $form_input['option'][1]['value'] }}" />
                                <span class="label-text">{{ $form_input['option'][1]['label'] }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @break

            @default

            @endswitch
            @endforeach

            <div id="catatan-lain" class="px-6 sm:px-8 pt-2">
                <label for="catatan-lain" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Hasil IKL</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="catatan-lain" id="catatan-lain" class="resize-none h-52 @error('catatan-lain') textarea-error @enderror" required placeholder="Catatan mengenai hasil inpeksi lingkungan kesehatan...">{{ old('catatan-lain') }}</textarea>
                    @error('catatan-lain')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div id="rencana-tindak-lanjut" class="px-6 sm:px-8 pt-2">
                <label for="rencana-tindak-lanjut" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Rencana Tindak Lanjut</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="rencana-tindak-lanjut" id="rencana-tindak-lanjut" class="resize-none h-52 @error('rencana-tindak-lanjut') textarea-error @enderror" required placeholder="Rencana untuk tindak lanjut kedepannya setelah inpeksi lingkungan kesehatan...">{{ old('rencana-tindak-lanjut') }}</textarea>
                    @error('rencana-tindak-lanjut')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
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
            <button onclick="reminder_before_submit.showModal()" type="button" class="btn btn-primary btn-block">SUBMIT PENILAIAN</button>

            <x-modal.reminder-before-submit />
        </div>

    </div>
</form>

<x-modal.get-lat-long />

<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/autosave-form.js') }}"></script>

<script>
function calculateSlhsExpireDate() {
    const issuedDateInput = document.getElementById('slhs_issued_date');
    const expireDateInput = document.getElementById('slhs_expire_date');
    
    if (issuedDateInput && expireDateInput && issuedDateInput.value) {
        // Parse issued date
        const issuedDate = new Date(issuedDateInput.value);
        
        // Add 3 years
        const expireDate = new Date(issuedDate);
        expireDate.setFullYear(expireDate.getFullYear() + 3);
        
        // Format to YYYY-MM-DD
        const formattedDate = expireDate.toISOString().split('T')[0];
        
        // Set expire date
        expireDateInput.value = formattedDate;
    } else if (expireDateInput) {
        // Clear expire date if no issued date
        expireDateInput.value = '';
    }
}

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {
    calculateSlhsExpireDate();
});
</script>
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
