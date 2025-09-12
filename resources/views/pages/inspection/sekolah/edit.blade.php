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

<script>
function copyErrorDetails() {
    const textarea = document.getElementById('errorDetails');
    textarea.select();
    textarea.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.remove('bg-red-600', 'hover:bg-red-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-red-600', 'hover:bg-red-700');
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Gagal meng-copy. Silakan copy manual.');
    }
}
</script>
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

<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
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
        // Only validate for update action, not duplicate
        if (e.submitter && e.submitter.value === 'duplicate') {
            return true; // Allow duplicate without validation
        }
        
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
        const submitBtn = e.submitter;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading loading-spinner loading-sm"></span> Menyimpan...';
        }
        
        // Submit the form
        this.submit();
    });

    $(document).ready(function() {
        let kecVal = "{{ $form_data['kecamatan'] ?? '' }}";
        let kelVal = "{{ $form_data['kelurahan'] ?? '' }}";
        
        console.log('Edit form loaded with:', { kecVal, kelVal });

        // Function to populate kecamatan dropdown
        function populateKecamatan() {
            if (typeof kecamatan !== 'undefined' && kecamatan && kecamatan.length > 0) {
                console.log('Populating kecamatan with', kecamatan.length, 'items');
                
                let options = '<option value="">Pilih Kecamatan</option>';
                kecamatan.forEach((el) => {
                    let selected = (kecVal && kecVal == el.name) ? 'selected' : '';
                    options += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                    if (selected) {
                        console.log('Pre-selecting kecamatan:', el.name);
                    }
                });

                $("#kec").html(options);
                
                // Trigger change event to load kelurahan if kecamatan is pre-selected
                if (kecVal) {
                    console.log('Triggering kelurahan load for pre-selected kecamatan:', kecVal);
                    setTimeout(() => {
                        populateKelurahan(kecVal);
                    }, 100);
                }
                
                return true;
            }
            return false;
        }

        // Function to populate kelurahan dropdown
        function populateKelurahan(selectedKecamatan) {
            console.log('Loading kelurahan for:', selectedKecamatan, 'Expected kelurahan:', kelVal);
            
            let selectedKec = kecamatan.find((el) => el.name == selectedKecamatan);
            if (!selectedKec || !selectedKec.id) {
                console.error('Kecamatan not found:', selectedKecamatan);
                $("#kel").html('<option value="">Kecamatan tidak ditemukan</option>');
                return;
            }

            console.log('Found kecamatan ID:', selectedKec.id);

            // Show loading
            $("#kel").html('<option value="">Memuat kelurahan...</option>');
            $("#kel").prop('disabled', true);

            fetch(`https://dev4ult.github.io/api-wilayah-indonesia/api/villages/${selectedKec.id}.json`)
                .then((response) => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then((villages) => {
                    console.log('Villages loaded:', villages.length, 'items');
                    
                    let kelOptions = '<option value="">Pilih Kelurahan</option>';
                    villages.forEach((el) => {
                        let selected = (kelVal && kelVal == el.name) ? 'selected' : '';
                        kelOptions += `<option value="${el.name}" ${selected}>${el.name}</option>`;
                        if (selected) {
                            console.log('Pre-selecting kelurahan:', el.name);
                        }
                    });
                    
                    $("#kel").html(kelOptions);
                    $("#kel").prop('disabled', false);
                    console.log('Kelurahan dropdown populated successfully');
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
                    $("#kec").html(`<option value="">Pilih Kecamatan</option><option value="${kecVal}" selected>${kecVal}</option>`);
                }
                if (kelVal) {
                    $("#kel").html(`<option value="">Pilih Kelurahan</option><option value="${kelVal}" selected>${kelVal}</option>`);
                    $("#kel").prop('disabled', false);
                }
            }
        }, 15000);
        
        // Additional check for immediate display of existing values
        setTimeout(() => {
            if (kecVal && $("#kec option:selected").val() !== kecVal) {
                console.log('Ensuring kecamatan value is preserved:', kecVal);
                let hasOption = $("#kec option[value='" + kecVal + "']").length > 0;
                if (!hasOption) {
                    $("#kec").append(`<option value="${kecVal}" selected>${kecVal}</option>`);
                } else {
                    $("#kec").val(kecVal);
                }
            }
            
            if (kelVal && $("#kel option:selected").val() !== kelVal) {
                console.log('Ensuring kelurahan value is preserved:', kelVal);
                let hasOption = $("#kel option[value='" + kelVal + "']").length > 0;
                if (!hasOption) {
                    $("#kel").append(`<option value="${kelVal}" selected>${kelVal}</option>`);
                    $("#kel").prop('disabled', false);
                } else {
                    $("#kel").val(kelVal);
                }
            }
        }, 2000);
    });

    function validateDuplicate() {
        const kecamatan = document.getElementById('kec').value;
        const kelurahan = document.getElementById('kel').value;
        
        if (!kecamatan || kecamatan === '' || kecamatan === 'Pilih Kecamatan') {
            alert('Kecamatan harus dipilih sebelum melakukan duplikasi penilaian');
            return false;
        }
        
        if (!kelurahan || kelurahan === '' || kelurahan === 'Pilih Kelurahan') {
            alert('Kelurahan harus dipilih sebelum melakukan duplikasi penilaian');
            return false;
        }
        
        return true;
    }



    // Auto-calculate on page load if issued date already filled</script>
<script src="{{ asset('js/autosave-form.js') }}"></script>
@endsection
