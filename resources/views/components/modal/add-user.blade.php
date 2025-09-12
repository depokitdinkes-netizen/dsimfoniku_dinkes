<dialog id="add_user" class="modal">
    <form method="POST" action="{{ route('manajemen-user.store') }}" class="modal-box max-w-[35rem]" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <h3 class="font-bold text-lg">Form Tambah User</h3>

        <div class="grid grid-flow-row sm:grid-cols-2 gap-3 mt-6">

            <div class="input-group">
                <label for="fullname">Nama Lengkap*</label>
                <input type="text" id="fullname" name="fullname" class="input input-bordered w-full" placeholder="John Doe" required />
            </div>

            <div class="input-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full" placeholder="johnDoe@example.com" required />
            </div>

            <div class="input-group">
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" class="input input-bordered w-full" placeholder="***********" required />
            </div>

            <div class="input-group">
                <label for="role">Role*</label>
                <select name="role" id="role" class="select select-bordered w-full" required onchange="toggleKelurahanField()">
                    <option value="" disabled selected>Pilih Role</option>
                    <!-- <option value="USER">User</option> -->
                    <option value="ADMIN">Admin</option>
                    <option value="SUPERADMIN">Superadmin</option>
                </select>
            </div>

            <div class="input-group" id="kelurahan-field" style="display: none;">
                <label for="kecamatan">Kecamatan*</label>
                <select id="kec" class="select select-bordered w-full" required>
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>

            <div class="input-group" id="kelurahan-input-field" style="display: none;">
                <label for="kelurahan">Kelurahan*</label>
                <div id="kelurahan-container">
                    <div class="kelurahan-row flex gap-2 mb-2">
                        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select" required>
                            <option value="">Pilih Kelurahan</option>
                        </select>
                        <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanField()">+</button>
                    </div>
                </div>
                <!-- Hidden input untuk menyimpan kecamatan yang dipilih -->
                <input type="hidden" name="kecamatan" id="selected-kecamatan" />
                <!-- Peringatan untuk duplikasi -->
                <div id="kelurahan-duplicate-warning" class="alert alert-warning mt-2" style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    <span class="text-sm">Kelurahan tidak boleh duplikat!</span>
                </div>
            </div>

            <!-- Kop Surat Fields - Common for both roles -->
            @foreach ([
                ['name' => 'sizebaris1', 'label' => 'Size Kop Baris Pertama', 'default'=>'18px', 'both' => true],
                ['name' => 'baris1', 'label' => 'Teks Kop Baris Pertama', 'both' => true],
                ['name' => 'sizebaris2', 'label' => 'Size Kop Baris Kedua', 'default'=>'25px', 'both' => true],
                ['name' => 'baris2', 'label' => 'Teks Kop Baris Kedua', 'both' => true],
                ['name' => 'sizebaris3', 'label' => 'Size Kop Baris Ketiga', 'default'=>'25px', 'both' => true],
                ['name' => 'baris3', 'label' => 'Teks Kop Baris Ketiga', 'both' => true],
                ['name' => 'sizebaris4', 'label' => 'Size Kop Baris Keempat', 'default'=>'13px', 'both' => true],
                ['name' => 'baris4', 'label' => 'Teks Kop Baris Keempat', 'both' => true],
            ] as $item)
                <div class="input-group">
                    <label for="{{ $item['name'] }}">{{ $item['label'] }}*</label>
                    <input type="text" id="{{ $item['name'] }}" name="{{ $item['name'] }}" class="input input-bordered w-full" @isset($item['default']) value="{{$item['default']}}" @endisset required />
                </div>
            @endforeach
            
            <!-- Baris 5 - Only for ADMIN -->
            <div class="input-group admin-only-field" style="display: none;">
                <label for="sizebaris5">Size Kop Baris Kelima*</label>
                <input type="text" id="sizebaris5" name="sizebaris5" class="input input-bordered w-full" value="13px" />
            </div>
            <div class="input-group admin-only-field" style="display: none;">
                <label for="baris5">Teks Kop Baris Kelima*</label>
                <input type="text" id="baris5" name="baris5" class="input input-bordered w-full" />
            </div>

            <!-- Preview Kop Surat Section -->
            <div class="sm:col-span-2 mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                <h4 class="text-md font-semibold text-green-800 mb-2">📋 Preview Kop Surat</h4>
                <p class="text-xs text-green-600 mb-3">Lihat pratinjau kop surat dalam format PDF sebelum menambahkan user</p>
                <div class="flex gap-2">
                    <button type="button" onclick="previewKopSuratModal()" class="btn btn-accent btn-xs">
                        📄 Preview PDF
                    </button>
                </div>
            </div>

            <div class="sm:col-span-2 mt-2">
                <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
            </div>
        </div>

    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Include script untuk kecamatan-kelurahan -->
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>

<script>
function toggleKelurahanField() {
    const roleSelect = document.getElementById('role');
    const kelurahanField = document.getElementById('kelurahan-field');
    const kelurahanInputField = document.getElementById('kelurahan-input-field');
    const kelurahanSelects = document.querySelectorAll('.kelurahan-select');
    const kecamatanHidden = document.getElementById('selected-kecamatan');
    const adminOnlyFields = document.querySelectorAll('.admin-only-field');
    const sizebaris5 = document.getElementById('sizebaris5');
    const baris5 = document.getElementById('baris5');
    
    if (roleSelect.value === 'ADMIN') {
        kelurahanField.style.display = 'block';
        kelurahanInputField.style.display = 'block';
        kelurahanSelects.forEach(select => {
            select.required = true;
        });
        
        // Show baris 5 fields for ADMIN
        adminOnlyFields.forEach(field => {
            field.style.display = 'block';
        });
        sizebaris5.required = true;
        baris5.required = true;
    } else {
        kelurahanField.style.display = 'none';
        kelurahanInputField.style.display = 'none';
        kelurahanSelects.forEach(select => {
            select.required = false;
            select.value = '';
        });
        kecamatanHidden.value = '';
        
        // Reset kecamatan dropdown juga
        const kecSelect = document.getElementById('kec');
        if (kecSelect) {
            kecSelect.value = '';
        }
        
        // Reset kelurahan container to single field
        resetKelurahanContainer();
        
        // Hide baris 5 fields for SUPERADMIN
        adminOnlyFields.forEach(field => {
            field.style.display = 'none';
        });
        sizebaris5.required = false;
        baris5.required = false;
        sizebaris5.value = '';
        baris5.value = '';
    }
}

function addKelurahanField() {
    const container = document.getElementById('kelurahan-container');
    const kelurahanRow = document.createElement('div');
    kelurahanRow.className = 'kelurahan-row flex gap-2 mb-2';
    kelurahanRow.innerHTML = `
        <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select">
            <option value="" disabled selected>Pilih Kelurahan</option>
        </select>
        <button type="button" class="btn btn-error btn-sm remove-kelurahan-btn" onclick="removeKelurahanField(this)">-</button>
    `;
    container.appendChild(kelurahanRow);
    
    // Update opsi untuk semua select kelurahan
    updateKelurahanOptions();
    
    // Cek duplikasi setelah menambah field
    checkKelurahanDuplicates();
}

function removeKelurahanField(button) {
    button.closest('.kelurahan-row').remove();
    
    // Update opsi untuk semua select kelurahan
    updateKelurahanOptions();
    
    // Cek duplikasi setelah menghapus field
    checkKelurahanDuplicates();
}

function resetKelurahanContainer() {
    const container = document.getElementById('kelurahan-container');
    container.innerHTML = `
        <div class="kelurahan-row flex gap-2 mb-2">
            <select name="kelurahan[]" class="select select-bordered w-full kelurahan-select" required>
                <option value="">Pilih Kelurahan</option>
            </select>
            <button type="button" class="btn btn-success btn-sm add-kelurahan-btn" onclick="addKelurahanField()">+</button>
        </div>
    `;
}

function updateKelurahanOptions() {
    // Get current kelurahan options from the API loaded data
    // Wait for kelurahan data to be loaded first
    setTimeout(() => {
        const firstSelect = document.querySelector('.kelurahan-select');
        if (!firstSelect || firstSelect.options.length <= 1) {
            console.log('Kelurahan options not loaded yet, retrying...');
            setTimeout(updateKelurahanOptions, 500);
            return;
        }
        
        // Get all selected kelurahan values to exclude them from other dropdowns
        const selectedKelurahan = [];
        document.querySelectorAll('.kelurahan-select').forEach(select => {
            if (select.value && select.value !== '') {
                selectedKelurahan.push(select.value);
            }
        });
        
        // Check for duplicates and show warning
        checkKelurahanDuplicates();
        
        const allOptions = Array.from(firstSelect.options).map(option => ({
            value: option.value,
            text: option.text
        }));
        
        // Update all kelurahan selects
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
                    updateKelurahanOptions();
                    checkKelurahanDuplicates();
                });
                select.setAttribute('data-listener-added', 'true');
            }
        });
    }, 100);
}

function checkKelurahanDuplicates() {
    const selectedValues = [];
    const warningDiv = document.getElementById('kelurahan-duplicate-warning');
    const submitButton = document.querySelector('button[type="submit"]');
    
    document.querySelectorAll('.kelurahan-select').forEach(select => {
        if (select.value && select.value !== '') {
            selectedValues.push(select.value);
        }
    });
    
    // Check for duplicates
    const hasDuplicates = selectedValues.length !== new Set(selectedValues).size;
    
    if (hasDuplicates) {
        warningDiv.style.display = 'block';
        submitButton.disabled = true;
        submitButton.classList.add('btn-disabled');
    } else {
        warningDiv.style.display = 'none';
        submitButton.disabled = false;
        submitButton.classList.remove('btn-disabled');
    }
}

// Tambahkan event listener untuk menyimpan nilai kecamatan yang dipilih
$(document).ready(function() {
    $('#kec').on('change', function() {
        const selectedKecamatan = $(this).val();
        $('#selected-kecamatan').val(selectedKecamatan);
        
        // Update all kelurahan options when kecamatan changes
        setTimeout(updateKelurahanOptions, 1000);
    });
});

// Function untuk preview kop surat dari modal
function previewKopSuratModal() {
    // Ambil data dari form modal
    const modal = document.getElementById('add_user');
    const formData = {
        sizebaris1: modal.querySelector('#sizebaris1').value || '18px',
        baris1: modal.querySelector('#baris1').value || '',
        sizebaris2: modal.querySelector('#sizebaris2').value || '25px',
        baris2: modal.querySelector('#baris2').value || '',
        sizebaris3: modal.querySelector('#sizebaris3').value || '25px',
        baris3: modal.querySelector('#baris3').value || '',
        sizebaris4: modal.querySelector('#sizebaris4').value || '13px',
        baris4: modal.querySelector('#baris4').value || '',
    };
    
    // Tambahkan baris 5 jika field tersedia
    const sizebaris5Input = modal.querySelector('#sizebaris5');
    const baris5Input = modal.querySelector('#baris5');
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
