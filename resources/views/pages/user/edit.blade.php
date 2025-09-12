@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    <h1 class="font-extrabold sm:text-xl">Ubah Informasi User</h1>
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />

</div>

<div class="px-3 sm:px-6 py-3">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a class="text-blue-500" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a class="text-blue-500" href="{{ route('manajemen-user.index') }}">List User</a></li>
            <li>Ubah Data User</li>
        </ul>
    </div>
</div>

<div class="px-3 sm:px-6 pb-6">
    <div class="p-8 bg-white">
        <form action="{{ route('manajemen-user.update', ['manajemen_user' => $user['id']]) }}" method="POST" class="grid grid-flow-row grid-cols-2 gap-5">
            @csrf
            @method('PUT')

            <div class="input-group">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" id="fullname" name="fullname" class="input input-bordered w-full" placeholder="John Doe" value="{{ $user['fullname'] }}" required />
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full" placeholder="johnDoe@example.com" value="{{ $user['email'] }}" required />
            </div>
            @auth
            @if (Auth::user()->role == "SUPERADMIN")
            <div class="input-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="select select-bordered w-full" required onchange="toggleKelurahanFieldEdit()">
                    <option value="" disabled>Pilih Role</option>
                    <option value="ADMIN" @if($user['role']=='ADMIN' ) selected @endif>Admin</option>
                    <option value="SUPERADMIN" @if($user['role']=='SUPERADMIN' ) selected @endif>Superadmin</option>
                </select>
            </div>
            
            <div class="input-group" id="kecamatan-field" style="display: {{ $user['role'] == 'ADMIN' ? 'block' : 'none' }};">
                <label for="kec">Kecamatan</label>
                <select name="kecamatan" id="kec" class="select select-bordered w-full">
                    <option value="" disabled selected>Pilih Kecamatan</option>
                    <!-- Options akan diisi oleh JavaScript -->
                </select>
            </div>
            
            <div class="input-group" id="kelurahan-field" style="display: {{ $user['role'] == 'ADMIN' ? 'block' : 'none' }};">
                <label for="kel">Kelurahan</label>
                <div id="kelurahan-container">
                    @if($user['role'] == 'ADMIN' && $user->userKelurahan->count() > 0)
                        @foreach($user->userKelurahan as $index => $userKel)
                        <div class="kelurahan-row flex gap-2 mb-2">
                            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
                                <option value="" disabled>Pilih Kelurahan</option>
                                <!-- Options akan diisi oleh JavaScript berdasarkan kecamatan yang dipilih -->
                            </select>
                            @if($index == 0)
                                <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanFieldEdit()">+</button>
                            @else
                                <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEdit(this)">-</button>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="kelurahan-row flex gap-2 mb-2">
                            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
                                <option value="" disabled selected>Pilih Kelurahan</option>
                                <!-- Options akan diisi oleh JavaScript berdasarkan kecamatan yang dipilih -->
                            </select>
                            <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanFieldEdit()">+</button>
                        </div>
                    @endif
                </div>
                <input type="hidden" name="kecamatan" id="selected-kecamatan" />
                <!-- Peringatan untuk duplikasi -->
                <div id="kelurahan-duplicate-warning" class="alert alert-warning mt-2" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    <span class="text-sm">Kelurahan tidak boleh duplikat!</span>
                </div>
            </div>
            @else
            <div class="input-group">
                <label for="role">Role</label>
                <input type="text" value="{{ $user['role'] }}" class="input input-bordered w-full" readonly />
                <input type="hidden" name="role" value="{{ $user['role'] }}" />
            </div>
            
            @if($user['role'] == 'ADMIN')
            <div class="input-group" id="kecamatan-field-admin">
                <label for="kec-admin">Kecamatan</label>
                <select name="kecamatan" id="kec-admin" class="select select-bordered w-full">
                    <option value="" disabled selected>Pilih Kecamatan</option>
                    <!-- Options akan diisi oleh JavaScript -->
                </select>
            </div>
            
            <div class="input-group" id="kelurahan-field-admin">
                <label for="kel-admin">Kelurahan</label>
                <div id="kelurahan-container-admin">
                    @if($user->userKelurahan->count() > 0)
                        @foreach($user->userKelurahan as $index => $userKel)
                        <div class="kelurahan-row flex gap-2 mb-2">
                            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select-admin">
                                <option value="" disabled>Pilih Kelurahan</option>
                                <!-- Options akan diisi oleh JavaScript berdasarkan kecamatan yang dipilih -->
                            </select>
                            @if($index == 0)
                                <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanFieldEditAdmin()">+</button>
                            @else
                                <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEditAdmin(this)">-</button>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="kelurahan-row flex gap-2 mb-2">
                            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select-admin">
                                <option value="" disabled selected>Pilih Kelurahan</option>
                                <!-- Options akan diisi oleh JavaScript berdasarkan kecamatan yang dipilih -->
                            </select>
                            <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanFieldEditAdmin()">+</button>
                        </div>
                    @endif
                </div>
                <input type="hidden" name="kecamatan" id="selected-kecamatan-admin" />
                <!-- Peringatan untuk duplikasi admin -->
                <div id="kelurahan-duplicate-warning-admin" class="alert alert-warning mt-2" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    <span class="text-sm">Kelurahan tidak boleh duplikat!</span>
                </div>
            </div>
            @endif
            @endif
            @endauth
            <div class="input-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="input input-bordered w-full" placeholder="************" />
            </div>

            @foreach ([
                ['name' => 'sizebaris1', 'label' => 'Size Kop Baris Pertama'],
                ['name' => 'baris1', 'label' => 'Teks Kop Baris Pertama'],
                ['name' => 'sizebaris2', 'label' => 'Size Kop Baris Kedua'],
                ['name' => 'baris2', 'label' => 'Teks Kop Baris Kedua'],
                ['name' => 'sizebaris3', 'label' => 'Size Kop Baris Ketiga'],
                ['name' => 'baris3', 'label' => 'Teks Kop Baris Ketiga'],
                ['name' => 'sizebaris4', 'label' => 'Size Kop Baris Keempat'],
                ['name' => 'baris4', 'label' => 'Teks Kop Baris Keempat'],
            ] as $item)
                <div class="input-group">
                    <label for="{{ $item['name'] }}">{{ $item['label'] }}</label>
                    <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}" class="input input-bordered w-full" required value="{{ $user[$item['name']] ?? '' }}" />
                </div>
            @endforeach
            
            @auth
            @if (Auth::user()->role == "SUPERADMIN")
                <!-- Baris 5 - Only show for ADMIN users -->
                @if($user['role'] == 'ADMIN')
                    <div class="input-group">
                        <label for="sizebaris5">Size Kop Baris Kelima</label>
                        <input type="text" id="sizebaris5" name="sizebaris5" class="input input-bordered w-full" value="{{ $user['sizebaris5'] ?? '13px' }}" />
                    </div>
                    <div class="input-group">
                        <label for="baris5">Teks Kop Baris Kelima</label>
                        <input type="text" id="baris5" name="baris5" class="input input-bordered w-full" value="{{ $user['baris5'] ?? '' }}" />
                    </div>
                @endif
            @else
                <!-- For ADMIN editing their own profile -->
                @if($user['role'] == 'ADMIN')
                    <div class="input-group">
                        <label for="sizebaris5">Size Kop Baris Kelima</label>
                        <input type="text" id="sizebaris5" name="sizebaris5" class="input input-bordered w-full" required value="{{ $user['sizebaris5'] ?? '13px' }}" />
                    </div>
                    <div class="input-group">
                        <label for="baris5">Teks Kop Baris Kelima</label>
                        <input type="text" id="baris5" name="baris5" class="input input-bordered w-full" required value="{{ $user['baris5'] ?? '' }}" />
                    </div>
                @endif
            @endif
            @endauth

            <!-- Preview Kop Surat Section -->
            <div class="sm:col-span-2 mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">📋 Preview Kop Surat</h3>
                <p class="text-sm text-blue-600 mb-4">Lihat pratinjau tampilan kop surat dalam format PDF sebelum menyimpan perubahan</p>
                <div class="flex gap-3">
                    <button type="button" onclick="previewKopSurat()" class="btn btn-accent btn-sm">
                        📄 Preview PDF
                    </button>
                </div>
            </div>

            @auth
            @if (Auth::user()->role == "SUPERADMIN")
            <button type="button" onclick="remove_user.showModal()" class="btn btn-error btn-outline">HAPUS USER</button>
            @endif
            @endauth

            <button type="submit" class="btn btn-primary btn-block">SIMPAN</button>
        </form>
    </div>
</div>

@auth
@if (Auth::user()->role == "SUPERADMIN")
<x-modal.remove-user userId="{{ $user['id'] }}" />
@endif
@endauth

<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script>
function toggleKelurahanFieldEdit() {
    const roleSelect = document.getElementById('role');
    const kecamatanField = document.getElementById('kecamatan-field');
    const kelurahanField = document.getElementById('kelurahan-field');
    const kecamatanInput = document.getElementById('kec');
    const kelurahanInputs = document.querySelectorAll('.kelurahan-select');
    
    // Show/hide kecamatan and kelurahan fields based on role
    if (roleSelect.value === 'ADMIN') {
        kecamatanField.style.display = 'block';
        kelurahanField.style.display = 'block';
        kecamatanInput.required = true;
        kelurahanInputs.forEach(input => {
            input.required = true;
        });
    } else {
        kecamatanField.style.display = 'none';
        kelurahanField.style.display = 'none';
        kecamatanInput.required = false;
        kelurahanInputs.forEach(input => {
            input.required = false;
            input.value = '';
        });
        kecamatanInput.value = '';
        
        // Reset kelurahan container
        resetKelurahanContainerEdit();
    }
    
    // Handle baris5 fields visibility - show only for ADMIN role
    const sizeBarisFields = document.querySelectorAll('input[name="sizebaris5"]');
    const barisFields = document.querySelectorAll('input[name="baris5"]');
    
    if (roleSelect.value === 'ADMIN') {
        // Show baris5 fields for ADMIN
        sizeBarisFields.forEach(field => {
            if (field.closest('.input-group')) {
                field.closest('.input-group').style.display = 'block';
                field.required = true;
            }
        });
        barisFields.forEach(field => {
            if (field.closest('.input-group')) {
                field.closest('.input-group').style.display = 'block';
                field.required = true;
            }
        });
    } else {
        // Hide baris5 fields for SUPERADMIN
        sizeBarisFields.forEach(field => {
            if (field.closest('.input-group')) {
                field.closest('.input-group').style.display = 'none';
                field.required = false;
                field.value = '';
            }
        });
        barisFields.forEach(field => {
            if (field.closest('.input-group')) {
                field.closest('.input-group').style.display = 'none';
                field.required = false;
                field.value = '';
            }
        });
    }
}

function addKelurahanFieldEdit() {
    const container = document.getElementById('kelurahan-container');
    const newRow = document.createElement('div');
    newRow.className = 'kelurahan-row flex gap-2 mb-2';
    
    newRow.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select" required>
            <option value="">Pilih Kelurahan</option>
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEdit(this)">-</button>
    `;
    
    container.appendChild(newRow);
    updateKelurahanOptionsEdit();
    
    // Cek duplikasi setelah menambah field
    checkKelurahanDuplicatesEdit();
}

function removeKelurahanFieldEdit(button) {
    const container = document.getElementById('kelurahan-container');
    const rows = container.querySelectorAll('.kelurahan-row');
    
    if (rows.length > 1) {
        button.closest('.kelurahan-row').remove();
        updateKelurahanOptionsEdit();
        
        // Cek duplikasi setelah menghapus field
        checkKelurahanDuplicatesEdit();
    }
}

function addKelurahanFieldEditAdmin() {
    const container = document.getElementById('kelurahan-container-admin');
    const newRow = document.createElement('div');
    newRow.className = 'kelurahan-row flex gap-2 mb-2';
    
    newRow.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select-admin" required>
            <option value="">Pilih Kelurahan</option>
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanFieldEditAdmin(this)">-</button>
    `;
    
    container.appendChild(newRow);
    updateKelurahanOptionsEditAdmin();
    
    // Cek duplikasi setelah menambah field
    checkKelurahanDuplicatesEditAdmin();
}

function removeKelurahanFieldEditAdmin(button) {
    const container = document.getElementById('kelurahan-container-admin');
    const rows = container.querySelectorAll('.kelurahan-row');
    
    if (rows.length > 1) {
        button.closest('.kelurahan-row').remove();
        updateKelurahanOptionsEditAdmin();
        
        // Cek duplikasi setelah menghapus field
        checkKelurahanDuplicatesEditAdmin();
    }
}

function resetKelurahanContainerEdit() {
    const container = document.getElementById('kelurahan-container');
    if (container) {
        container.innerHTML = `
            <div class="kelurahan-row flex gap-2 mb-2">
                <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
                    <option value="" disabled selected>Pilih Kelurahan</option>
                </select>
                <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanFieldEdit()">+</button>
            </div>
        `;
    }
}

function updateKelurahanOptionsEdit() {
    setTimeout(() => {
        const firstSelect = document.querySelector('.kelurahan-select');
        if (!firstSelect || firstSelect.options.length <= 1) {
            console.log('Kelurahan options not loaded yet, retrying...');
            setTimeout(updateKelurahanOptionsEdit, 500);
            return;
        }
        
        // Get all selected kelurahan values to exclude them from other dropdowns
        const selectedKelurahan = [];
        document.querySelectorAll('.kelurahan-select').forEach(select => {
            if (select.value && select.value !== '') {
                selectedKelurahan.push(select.value);
            }
        });
        
        const allOptions = Array.from(firstSelect.options).map(option => ({
            value: option.value,
            text: option.text
        }));
        
        document.querySelectorAll('.kelurahan-select').forEach(select => {
            const currentValue = select.value;
            
            // Hanya rebuild jika select kosong atau hanya punya placeholder
            if (select.options.length <= 1) {
                select.innerHTML = '';
                allOptions.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.text = option.text;
                    select.appendChild(optionElement);
                });
            }
            
            // Update visibility/availability dari existing options
            Array.from(select.options).forEach(option => {
                if (option.value === '') return; // Skip placeholder
                
                // Hide/show option berdasarkan apakah sudah dipilih di select lain
                if (selectedKelurahan.includes(option.value) && currentValue !== option.value) {
                    option.style.display = 'none';
                    option.disabled = true;
                } else {
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
            
            // Restore nilai yang sudah dipilih sebelumnya
            if (currentValue) {
                select.value = currentValue;
            }
            
            // Add event listener untuk change event (hanya jika belum ada)
            if (!select.hasAttribute('data-listener-added')) {
                select.addEventListener('change', function() {
                    updateKelurahanOptionsEdit();
                    checkKelurahanDuplicatesEdit();
                });
                select.setAttribute('data-listener-added', 'true');
            }
        });
        
        // Cek duplikasi setelah update opsi
        checkKelurahanDuplicatesEdit();
    }, 100);
}

function checkKelurahanDuplicatesEdit() {
    const selects = document.querySelectorAll('.kelurahan-select');
    const selectedValues = [];
    let hasDuplicate = false;
    
    selects.forEach(select => {
        if (select.value) {
            if (selectedValues.includes(select.value)) {
                hasDuplicate = true;
            }
            selectedValues.push(select.value);
        }
    });
    
    const warningDiv = document.getElementById('kelurahan-duplicate-warning');
    const submitButton = document.querySelector('button[type="submit"]');
    
    if (hasDuplicate) {
        if (warningDiv) warningDiv.style.display = 'block';
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('btn-disabled');
        }
    } else {
        if (warningDiv) warningDiv.style.display = 'none';
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('btn-disabled');
        }
    }
}

function updateKelurahanOptionsEditAdmin() {
    setTimeout(() => {
        const firstSelect = document.querySelector('.kelurahan-select-admin');
        if (!firstSelect || firstSelect.options.length <= 1) {
            console.log('Kelurahan admin options not loaded yet, retrying...');
            setTimeout(updateKelurahanOptionsEditAdmin, 500);
            return;
        }
        
        // Get all selected kelurahan values to exclude them from other dropdowns
        const selectedKelurahan = [];
        document.querySelectorAll('.kelurahan-select-admin').forEach(select => {
            if (select.value && select.value !== '') {
                selectedKelurahan.push(select.value);
            }
        });
        
        const allOptions = Array.from(firstSelect.options).map(option => ({
            value: option.value,
            text: option.text
        }));
        
        document.querySelectorAll('.kelurahan-select-admin').forEach(select => {
            const currentValue = select.value;
            
            // Hanya rebuild jika select kosong atau hanya punya placeholder
            if (select.options.length <= 1) {
                select.innerHTML = '';
                allOptions.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.text = option.text;
                    select.appendChild(optionElement);
                });
            }
            
            // Update visibility/availability dari existing options
            Array.from(select.options).forEach(option => {
                if (option.value === '') return; // Skip placeholder
                
                // Hide/show option berdasarkan apakah sudah dipilih di select lain
                if (selectedKelurahan.includes(option.value) && currentValue !== option.value) {
                    option.style.display = 'none';
                    option.disabled = true;
                } else {
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
            
            // Restore nilai yang sudah dipilih sebelumnya
            if (currentValue) {
                select.value = currentValue;
            }
            
            // Add event listener untuk change event (hanya jika belum ada)
            if (!select.hasAttribute('data-listener-added-admin')) {
                select.addEventListener('change', function() {
                    updateKelurahanOptionsEditAdmin();
                    checkKelurahanDuplicatesEditAdmin();
                });
                select.setAttribute('data-listener-added-admin', 'true');
            }
        });
        
        // Cek duplikasi setelah update opsi
        checkKelurahanDuplicatesEditAdmin();
    }, 100);
}

function checkKelurahanDuplicatesEditAdmin() {
    const selects = document.querySelectorAll('.kelurahan-select-admin');
    const selectedValues = [];
    let hasDuplicate = false;
    
    selects.forEach(select => {
        if (select.value) {
            if (selectedValues.includes(select.value)) {
                hasDuplicate = true;
            }
            selectedValues.push(select.value);
        }
    });
    
    const warningDiv = document.getElementById('kelurahan-duplicate-warning-admin');
    const submitButton = document.querySelector('button[type="submit"]');
    
    if (hasDuplicate) {
        if (warningDiv) warningDiv.style.display = 'block';
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('btn-disabled');
        }
    } else {
        if (warningDiv) warningDiv.style.display = 'none';
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('btn-disabled');
        }
    }
}

// Initialize districts on page load
$(document).ready(function() {
    // Wait for the getDistrictsAndVillages.js to initialize
    setTimeout(function() {
        // Event handler untuk perubahan kecamatan (superadmin edit admin)
        $('#kec').off('change.edit').on('change.edit', function() {
            const selectedKecamatan = $(this).val();
            
            // Set hidden input untuk kecamatan
            $('#selected-kecamatan').val(selectedKecamatan);
            
            // Update all kelurahan options when kecamatan changes
            setTimeout(updateKelurahanOptionsEdit, 1500);
        });
        
        // Event handler untuk perubahan kecamatan (admin edit sendiri)
        $('#kec-admin').off('change.edit-admin').on('change.edit-admin', function() {
            const selectedKecamatan = $(this).val();
            
            // Set hidden input untuk kecamatan
            $('#selected-kecamatan-admin').val(selectedKecamatan);
            
            // Update all kelurahan options when kecamatan changes
            setTimeout(updateKelurahanOptionsEditAdmin, 1500);
        });
        
        // Add event listener for kelurahan change to update other dropdowns
        $(document).off('change.kelurahan-edit', '.kelurahan-select').on('change.kelurahan-edit', '.kelurahan-select', function() {
            updateKelurahanOptionsEdit();
        });
        
        $(document).off('change.kelurahan-edit-admin', '.kelurahan-select-admin').on('change.kelurahan-edit-admin', '.kelurahan-select-admin', function() {
            updateKelurahanOptionsEditAdmin();
        });
        
        // Jika user sudah memiliki kelurahan, set default value
        @if(isset($user['kelurahan']) && isset($user['kecamatan']))
            // Set kecamatan terlebih dahulu
            setTimeout(function() {
                // Cari kecamatan berdasarkan kelurahan yang sudah ada
                @if (Auth::user()->role == "SUPERADMIN")
                    findAndSetKecamatanByKelurahan('{{ $user['kelurahan'] }}', '{{ $user['kecamatan'] ?? '' }}');
                @else
                    findAndSetKecamatanByKelurahanAdmin('{{ $user['kelurahan'] }}', '{{ $user['kecamatan'] ?? '' }}');
                @endif
            }, 1000);
        @elseif($user->userKelurahan->count() > 0)
            // Handle multiple kelurahan
            setTimeout(function() {
                const userKelurahan = @json($user->userKelurahan->toArray());
                @if (Auth::user()->role == "SUPERADMIN")
                    findAndSetMultipleKelurahan(userKelurahan);
                @else
                    findAndSetMultipleKelurahanAdmin(userKelurahan);
                @endif
            }, 1000);
        @endif
    }, 500);
    
    toggleKelurahanFieldEdit();
});

// Function untuk mencari dan set multiple kelurahan
function findAndSetMultipleKelurahan(userKelurahan) {
    if (userKelurahan.length === 0) return;
    
    const kecamatanValue = userKelurahan[0].kecamatan;
    
    // Set hidden input untuk kecamatan
    $('#selected-kecamatan').val(kecamatanValue);
    
    // Cari dan set kecamatan
    $('#kec option').each(function() {
        if ($(this).val() === kecamatanValue || $(this).text() === kecamatanValue) {
            $(this).prop('selected', true);
            $('#kec').trigger('change');
            
            // Setelah kelurahan dimuat, set semua kelurahan yang dipilih
            setTimeout(function() {
                $('.kelurahan-select').each(function(index) {
                    if (userKelurahan[index]) {
                        const kelurahanValue = userKelurahan[index].kelurahan;
                        $(this).find('option').each(function() {
                            if ($(this).val() === kelurahanValue || $(this).text() === kelurahanValue) {
                                $(this).prop('selected', true);
                                return false;
                            }
                        });
                    }
                });
            }, 1500);
            
            return false; // break loop
        }
    });
}

// Function untuk mencari dan set multiple kelurahan (Admin edit sendiri)
function findAndSetMultipleKelurahanAdmin(userKelurahan) {
    if (userKelurahan.length === 0) return;
    
    const kecamatanValue = userKelurahan[0].kecamatan;
    
    // Set hidden input untuk kecamatan
    $('#selected-kecamatan-admin').val(kecamatanValue);
    
    // Cari dan set kecamatan
    $('#kec-admin option').each(function() {
        if ($(this).val() === kecamatanValue || $(this).text() === kecamatanValue) {
            $(this).prop('selected', true);
            $('#kec-admin').trigger('change');
            
            // Setelah kelurahan dimuat, set semua kelurahan yang dipilih
            setTimeout(function() {
                $('.kelurahan-select-admin').each(function(index) {
                    if (userKelurahan[index]) {
                        const kelurahanValue = userKelurahan[index].kelurahan;
                        $(this).find('option').each(function() {
                            if ($(this).val() === kelurahanValue || $(this).text() === kelurahanValue) {
                                $(this).prop('selected', true);
                                return false;
                            }
                        });
                    }
                });
            }, 1500);
            
            return false; // break loop
        }
    });
}

// Function untuk mencari dan set kecamatan berdasarkan kelurahan
function findAndSetKecamatanByKelurahan(kelurahanValue, kecamatanValue) {
    // Jika kecamatan sudah ada, set langsung
    if (kecamatanValue) {
        // Set hidden input untuk kecamatan
        $('#selected-kecamatan').val(kecamatanValue);
        
        // Cari dan set kecamatan
        $('#kec option').each(function() {
            if ($(this).val() === kecamatanValue || $(this).text() === kecamatanValue) {
                $(this).prop('selected', true);
                $('#kec').trigger('change');
                
                // Setelah kelurahan dimuat, set kelurahan yang dipilih
                setTimeout(function() {
                    $('.kelurahan-select').first().find('option').each(function() {
                        if ($(this).val() === kelurahanValue || $(this).text() === kelurahanValue) {
                            $(this).prop('selected', true);
                            return false; // break loop
                        }
                    });
                }, 1000);
                
                return false; // break loop
            }
        });
    }
}

// Function untuk mencari dan set kecamatan berdasarkan kelurahan (Admin edit sendiri)
function findAndSetKecamatanByKelurahanAdmin(kelurahanValue, kecamatanValue) {
    // Jika kecamatan sudah ada, set langsung
    if (kecamatanValue) {
        // Set hidden input untuk kecamatan
        $('#selected-kecamatan-admin').val(kecamatanValue);
        
        // Cari dan set kecamatan
        $('#kec-admin option').each(function() {
            if ($(this).val() === kecamatanValue || $(this).text() === kecamatanValue) {
                $(this).prop('selected', true);
                $('#kec-admin').trigger('change');
                
                // Setelah kelurahan dimuat, set kelurahan yang dipilih
                setTimeout(function() {
                    $('.kelurahan-select-admin').first().find('option').each(function() {
                        if ($(this).val() === kelurahanValue || $(this).text() === kelurahanValue) {
                            $(this).prop('selected', true);
                            return false; // break loop
                        }
                    });
                }, 1000);
                
                return false; // break loop
            }
        });
    }
}

// Function untuk preview kop surat
function previewKopSurat() {
    // Ambil data dari form
    const formData = {
        sizebaris1: document.getElementById('sizebaris1').value || '18px',
        baris1: document.getElementById('baris1').value || '',
        sizebaris2: document.getElementById('sizebaris2').value || '25px',
        baris2: document.getElementById('baris2').value || '',
        sizebaris3: document.getElementById('sizebaris3').value || '25px',
        baris3: document.getElementById('baris3').value || '',
        sizebaris4: document.getElementById('sizebaris4').value || '13px',
        baris4: document.getElementById('baris4').value || '',
    };
    
    // Tambahkan baris 5 jika user adalah ADMIN
    const sizebaris5Input = document.getElementById('sizebaris5');
    const baris5Input = document.getElementById('baris5');
    if (sizebaris5Input && baris5Input) {
        formData.sizebaris5 = sizebaris5Input.value || '13px';
        formData.baris5 = baris5Input.value || '';
    }
    
    // Buat URL dengan parameter
    const params = new URLSearchParams(formData);
    const url = `{{ route("kop-surat.preview.pdf") }}?${params.toString()}`;
    
    // Buka PDF di tab baru
    window.open(url, '_blank');
}
</script>

@endsection
