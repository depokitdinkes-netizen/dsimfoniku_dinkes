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
                <select name="role" id="role" class="select select-bordered w-full" required>
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

            <!-- Dynamic Kop Surat Lines (max 10) -->
            <div class="sm:col-span-2 mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                <h4 class="text-md font-semibold text-green-800 mb-3">✏️ Pengaturan Kop Surat</h4>
                <p class="text-xs text-green-600 mb-4">Atur teks dan ukuran untuk setiap baris kop surat. Maksimal 10 baris dapat ditambahkan.</p>
                
                <div id="kop-surat-container-modal">
                    @for($i = 1; $i <= 10; $i++)
                        @php
                            $isRequired = $i <= 4; // First 4 lines are required
                            $defaultSize = match($i) {
                                1 => '18px',
                                2, 3 => '25px',
                                default => '13px'
                            };
                            // Show first 4 always, others hidden (will be shown/hidden by role JS)
                            $isHidden = $i > 4;
                        @endphp
                        <div class="kop-line-group-modal mb-4 {{ $isHidden ? 'hidden' : '' }}" data-line="{{ $i }}" data-role-visibility="admin">
                            <div class="flex items-center gap-2 mb-2">
                                <h5 class="font-medium text-gray-700">Baris {{ $i }} {{ $isRequired ? '*' : '' }}</h5>
                                <button type="button" onclick="removeKopLineModal({{ $i }})" class="btn btn-xs btn-error btn-outline remove-btn-modal" data-line="{{ $i }}" style="{{ $i <= 4 ? 'display: none;' : '' }}">
                                    ✕ Hapus
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div class="md:col-span-1">
                                    <label for="sizebaris{{ $i }}" class="text-xs text-gray-600">Ukuran Font</label>
                                    <select id="sizebaris{{ $i }}" name="sizebaris{{ $i }}" class="select select-bordered select-sm w-full">
                                        <option value="8px">8px</option>
                                        <option value="10px">10px</option>
                                        <option value="12px">12px</option>
                                        <option value="13px" {{ $defaultSize == '13px' ? 'selected' : '' }}>13px</option>
                                        <option value="14px">14px</option>
                                        <option value="16px">16px</option>
                                        <option value="18px" {{ $defaultSize == '18px' ? 'selected' : '' }}>18px</option>
                                        <option value="20px">20px</option>
                                        <option value="22px">22px</option>
                                        <option value="24px">24px</option>
                                        <option value="25px" {{ $defaultSize == '25px' ? 'selected' : '' }}>25px</option>
                                        <option value="28px">28px</option>
                                        <option value="30px">30px</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="baris{{ $i }}" class="text-xs text-gray-600">Teks Baris {{ $i }} {{ $isRequired ? '*' : '' }}</label>
                                    <input type="text"
                                           id="baris{{ $i }}"
                                           name="baris{{ $i }}"
                                           class="input input-bordered input-sm w-full"
                                           {{ $isRequired ? 'required' : '' }}
                                           placeholder="Masukkan teks untuk baris {{ $i }}..." />
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <!-- Add Line Button -->
                <div class="mt-4">
                    <button type="button" onclick="addKopLineModal()" id="add-kop-line-btn-modal" class="btn btn-sm btn-success btn-outline">
                        ➕ Tambah Baris Kop Surat
                    </button>
                    <span class="text-xs text-gray-500 ml-2" id="kop-limit-text">Maksimal 10 baris (Admin) / 4 baris (Super Admin)</span>
                </div>
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
        @csrf
        <button>close</button>
    </form>
</dialog>

<!-- Include script untuk kecamatan-kelurahan -->
<script src="{{ asset('js/getDistrictsAndVillages.js') }}"></script>
<script src="{{ asset('js/modal/add-user.js') }}"></script>
