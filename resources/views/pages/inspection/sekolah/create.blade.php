@extends('layouts.app')

@section('content')
<x-section.page-header title="Inspeksi / Penilaian Sekolah" />

<x-breadcrumb.create-inspection />

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
<div class="alert alert-error mx-3 sm:mx-6 mb-5">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <div class="font-semibold mb-2">Detail Error (dapat di-copy):</div>
            <textarea readonly class="w-full h-32 p-2 text-xs font-mono bg-red-50 border border-red-300 rounded resize-none" id="errorDetails">{{ session('error_details') }}</textarea>
            <button type="button" onclick="copyErrorDetails()" class="mt-2 btn btn-sm btn-outline btn-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span id="copyButtonText">Copy</span>
            </button>
        </div>
    </div>
</div>

<script>
function copyErrorDetails() {
    const textarea = document.getElementById('errorDetails');
    const button = document.getElementById('copyButtonText');
    
    textarea.select();
    textarea.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(textarea.value);
    
    button.textContent = 'Copied!';
    setTimeout(() => {
        button.textContent = 'Copy';
    }, 2000);
}
</script>
@endif

<form action="{{ route('sekolah.store') }}" method="POST" enctype="multipart/form-data">
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
                @case('jenis_sekolah')
                <div class="input-group">
                    <label for="jenis_sekolah">{{ $form_input['label'] }}</label>
                    <select name="{{ $form_input['name'] }}" id="jenis_sekolah" class="select select-bordered" required>
                        <option value="" disabled {{ old('jenis_sekolah') ? '' : 'selected' }}>Pilih Jenis Sekolah</option>
                        <option value="TK" {{ old('jenis_sekolah') == 'TK' ? 'selected' : '' }}>TK (Taman Kanak-Kanak)</option>
                        <option value="PAUD" {{ old('jenis_sekolah') == 'PAUD' ? 'selected' : '' }}>PAUD (Pendidikan Anak Usia Dini)</option>
                        <option value="SD" {{ old('jenis_sekolah') == 'SD' ? 'selected' : '' }}>SD (Sekolah Dasar)</option>
                        <option value="MI" {{ old('jenis_sekolah') == 'MI' ? 'selected' : '' }}>MI (Madrasah Ibtidaiyah)</option>
                        <option value="SMP" {{ old('jenis_sekolah') == 'SMP' ? 'selected' : '' }}>SMP (Sekolah Menengah Pertama)</option>
                        <option value="MTs" {{ old('jenis_sekolah') == 'MTs' ? 'selected' : '' }}>MTs (Madrasah Tsanawiyah)</option>
                        <option value="SMA" {{ old('jenis_sekolah') == 'SMA' ? 'selected' : '' }}>SMA (Sekolah Menengah Atas)</option>
                        <option value="MA" {{ old('jenis_sekolah') == 'MA' ? 'selected' : '' }}>MA (Madrasah Aliyah)</option>
                        <option value="SMK" {{ old('jenis_sekolah') == 'SMK' ? 'selected' : '' }}>SMK (Sekolah Menengah Kejuruan)</option>
                    </select>
                </div>
                @break

                @case('instansi-pemeriksa')
                <x-input.instansi-pemeriksa.create />
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
                        <option value="" disabled {{ old('status-operasi') ? '' : 'selected' }}>Pilih Status</option>
                        <option value="1" {{ old('status-operasi') == '1' ? 'selected' : '' }}>Masih Beroperasi</option>
                        <option value="0" {{ old('status-operasi') == '0' ? 'selected' : '' }}>Tidak Beroperasi</option>
                    </select>
                </div>
                @break

                @case('koordinat')
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <div class="join">
                        <input type="{{ $form_input['type'] }}" id="koordinat" name="koordinat" class="join-item w-full" placeholder="-6.324667, 106.891268" required value="{{ old('koordinat') }}" />
                        <button onclick="get_lat_long.showModal()" type="button" class="join-item btn btn-neutral">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                @break

                @default
                <div class="input-group">
                    <label for="{{ $form_input['name'] }}">{{ $form_input['label'] }}</label>
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" required value="{{ old($form_input['name']) }}" />
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
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-primary" value="{{ $form_input['score'] }}" checked />
                                <span class="label-text">Sesuai</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-2">
                                <input type="radio" name="{{ $form_input['name'] }}" class="radio checked:bg-error" value="0" />
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
                    <input type="{{ $form_input['type'] }}" id="{{ $form_input['name'] }}" name="{{ $form_input['name'] }}" required value="{{ old($form_input['name']) }}" />
                </div>
            </div>
            @endforeach

            <div id="catatan-lain" class="px-6 sm:px-8 pt-2">
                <label for="catatan-lain" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Hasil IKL</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="catatan-lain" id="catatan-lain" class="resize-none h-52" required placeholder="Catatan mengenai hasil inpeksi lingkungan kesehatan...">{{ old('catatan-lain') }}</textarea>
                </div>
            </div>

            <div id="rencana-tindak-lanjut" class="px-6 sm:px-8 pt-2">
                <label for="rencana-tindak-lanjut" class="badge badge-lg badge-neutral badge-outline font-semibold mb-5 mt-6">Rencana Tindak Lanjut</label>
            </div>

            <div class="px-6 sm:px-8">
                <div class="input-group">
                    <textarea name="rencana-tindak-lanjut" id="rencana-tindak-lanjut" class="resize-none h-52" required placeholder="Rencana untuk tindak lanjut kedepannya setelah inpeksi lingkungan kesehatan...">{{ old('rencana-tindak-lanjut') }}</textarea>
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

<script>
// Function to show error toast
function showErrorToast(message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'alert border-b-4 border-error fixed capitalize font-medium top-0 right-0 m-5 w-fit animate-fade-in z-20 max-w-md';
    toast.innerHTML = `
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-left text-sm normal-case">${message}</div>
        </div>
        <button type="button" class="close-alert btn btn-ghost btn-square btn-sm">
            <i class="ri-close-line"></i>
        </button>
    `;
    
    // Add to body
    document.body.appendChild(toast);
    
    // Add click handler for close button
    const closeBtn = toast.querySelector('.close-alert');
    closeBtn.addEventListener('click', function() {
        toast.remove();
    });
    
    // Auto remove after 8 seconds (longer for detailed message)
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 8000);
}

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const requiredFields = [
        { name: 'subjek', label: 'Nama Sekolah' },
        { name: 'jenis_sekolah', label: 'Jenis Sekolah' },
        { name: 'alamat', label: 'Alamat' },
        { name: 'kecamatan', label: 'Kecamatan' },
        { name: 'kelurahan', label: 'Kelurahan' },
        { name: 'pengelola', label: 'Kepala Sekolah/NIP' },
        { name: 'kontak', label: 'Kontak yang Dapat Dihubungi' },
        { name: 'u004', label: 'Jumlah Siswa' },
        { name: 'u005', label: 'Jumlah Guru' },
        { name: 'u006', label: 'Nomor Pokok Sekolah Nasional' },
        { name: 'nama-pemeriksa', label: 'Nama Pemeriksa' },
        { name: 'instansi-pemeriksa', label: 'Instansi Pemeriksa' },
        { name: 'tanggal-penilaian', label: 'Tanggal Penilaian' },
        { name: 'koordinat', label: 'Titik GPS' },
        { name: 'status-operasi', label: 'Status Operasi' },
        { name: 'catatan-lain', label: 'Hasil IKL' },
        { name: 'rencana-tindak-lanjut', label: 'Rencana Tindak Lanjut' }
    ];

    // Hasil pengukuran fields
    const hasilPengukuranFields = [
        { name: 'hpp001', label: 'Hasil Pengukuran Pencahayaan di Ruang Kelas' },
        { name: 'hpp002', label: 'Hasil Pengukuran Pencahayaan di Ruang Perpustakaan' },
        { name: 'hpp003', label: 'Hasil Pengukuran Pencahayaan di Ruang Laboratorium' },
        { name: 'hpp004', label: 'Hasil Pengukuran Kelembaban' },
        { name: 'hpp005', label: 'Hasil Pengukuran Kebisingan' },
        { name: 'hpp006', label: 'Hasil Pengukuran PM 2,5' },
        { name: 'hpp007', label: 'Hasil Pengukuran PM10' }
    ];

    let hasErrors = false;
    let errorMessages = [];
    
    // Clear previous error states
    document.querySelectorAll('.input-error, .select-error, .textarea-error').forEach(el => {
        el.classList.remove('input-error', 'select-error', 'textarea-error');
    });
    document.querySelectorAll('.text-error').forEach(el => el.remove());

    // Validate required fields
    [...requiredFields, ...hasilPengukuranFields].forEach(field => {
        const element = document.getElementById(field.name) || document.querySelector(`[name="${field.name}"]`);
        if (element) {
            const value = element.value.trim();
            if (!value || value === '' || value === 'Pilih Kecamatan' || value === 'Pilih Kelurahan' || value === 'Pilih Jenis Sekolah' || value === 'Pilih Status') {
                hasErrors = true;
                errorMessages.push(field.label);
                
                // Add error class based on element type
                if (element.tagName === 'SELECT') {
                    element.classList.add('select-error');
                } else if (element.tagName === 'TEXTAREA') {
                    element.classList.add('textarea-error');
                } else {
                    element.classList.add('input-error');
                }
                
                // Add error message
                if (!element.parentNode.querySelector('.text-error')) {
                    const errorSpan = document.createElement('span');
                    errorSpan.className = 'text-error text-xs';
                    errorSpan.textContent = `${field.label} harus diisi`;
                    element.parentNode.appendChild(errorSpan);
                }
            }
        }
    });

    // Validate koordinat format (lat, long)
    const koordinat = document.getElementById('koordinat');
    if (koordinat && koordinat.value) {
        const koordinatPattern = /^-?\d+\.?\d*,\s*-?\d+\.?\d*$/;
        if (!koordinatPattern.test(koordinat.value.trim())) {
            hasErrors = true;
            errorMessages.push('Format Koordinat tidak valid');
            koordinat.classList.add('input-error');
            if (!koordinat.parentNode.querySelector('.text-error')) {
                const errorSpan = document.createElement('span');
                errorSpan.className = 'text-error text-xs';
                errorSpan.textContent = 'Format koordinat harus: latitude, longitude (contoh: -6.324667, 106.891268)';
                koordinat.parentNode.appendChild(errorSpan);
            }
        }
    }

    if (hasErrors) {
        // Scroll to first error
        const firstError = document.querySelector('.input-error, .select-error, .textarea-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Create detailed error message
        let errorMessage = 'Field yang belum diisi:\n';
        errorMessages.forEach((field, index) => {
            errorMessage += `${index + 1}. ${field}\n`;
        });
        
        // Show toast with specific errors
        showErrorToast(errorMessage.replace(/\n/g, '<br>'));
        return false;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Menyimpan...';
    }
    
    // Submit the form
    this.submit();
});

// Auto-calculate on page load if issued date already filled
document.addEventListener('DOMContentLoaded', function() {});
</script>

<x-modal.get-lat-long />

<script src="{{ asset('js/fallbackData.js') }}"></script>
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
