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

            @auth
            @if (Auth::user()->role == "SUPERADMIN")
            <div class="input-group">
                <label for="fullname">Nama Lengkap</label>
                <input type="text" id="fullname" name="fullname" class="input input-bordered w-full" placeholder="John Doe" value="{{ $user['fullname'] }}" required />
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="input input-bordered w-full" placeholder="johnDoe@example.com" value="{{ $user['email'] }}" required />
            </div>
            @else
            <div class="input-group">
                <label for="fullname">Nama Lengkap (Tidak dapat diubah)</label>
                <input type="text" value="{{ $user['fullname'] }}" class="input input-bordered w-full bg-gray-100" readonly />
                <input type="hidden" name="fullname" value="{{ $user['fullname'] }}" />
                <p class="text-xs text-gray-500 mt-1">⚠️ Nama lengkap hanya dapat diubah oleh SUPERADMIN</p>
            </div>

            <div class="input-group">
                <label for="email">Email (Tidak dapat diubah)</label>
                <input type="text" value="{{ $user['email'] }}" class="input input-bordered w-full bg-gray-100" readonly />
                <input type="hidden" name="email" value="{{ $user['email'] }}" />
                <p class="text-xs text-gray-500 mt-1">⚠️ Email hanya dapat diubah oleh SUPERADMIN</p>
            </div>
            @endif
            @endauth
            
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
                <label for="kec-admin">Kecamatan (Tidak dapat diubah)</label>
                <input type="text" value="{{ $user->userKelurahan->first()->kecamatan ?? 'Belum diset' }}" class="input input-bordered w-full bg-gray-100" readonly />
                <input type="hidden" name="kecamatan" value="{{ $user->userKelurahan->first()->kecamatan ?? '' }}" />
                <p class="text-xs text-gray-500 mt-1">⚠️ Kecamatan hanya dapat diubah oleh SUPERADMIN</p>
            </div>
            
            <div class="input-group" id="kelurahan-field-admin">
                <label for="kel-admin">Kelurahan (Tidak dapat diubah)</label>
                <div class="space-y-2">
                    @if($user->userKelurahan->count() > 0)
                        @foreach($user->userKelurahan as $userKel)
                        <div class="flex items-center gap-2">
                            <input type="text" value="{{ $userKel->kelurahan }}" class="input input-bordered input-sm w-full bg-gray-100" readonly />
                            <input type="hidden" name="kelurahan[]" value="{{ $userKel->kelurahan }}" />
                        </div>
                        @endforeach
                    @else
                        <input type="text" value="Belum diset" class="input input-bordered w-full bg-gray-100" readonly />
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">⚠️ Kelurahan hanya dapat diubah oleh SUPERADMIN</p>
            </div>
            @endif
            @endif
            @endauth
            
            @auth
            @if (Auth::user()->role == "SUPERADMIN")
            <div class="input-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="input input-bordered w-full" placeholder="************" />
            </div>
            @else
            <div class="input-group">
                <label for="password">Password (Tidak dapat diubah)</label>
                <input type="text" value="••••••••" class="input input-bordered w-full bg-gray-100" readonly />
                <p class="text-xs text-gray-500 mt-1">⚠️ Password hanya dapat diubah oleh SUPERADMIN</p>
            </div>
            @endif
            @endauth

            <!-- Dynamic Kop Surat Lines (max 10) -->
            <div class="sm:col-span-2 mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg">
                <h4 class="text-md font-semibold text-green-800 mb-3">✏️ Pengaturan Kop Surat</h4>
                <p class="text-xs text-green-600 mb-4">Atur teks dan ukuran untuk setiap baris kop surat. Maksimal 10 baris dapat ditambahkan.</p>
                
                <div id="kop-surat-container">
                    @for($i = 1; $i <= 10; $i++)
                        @php
                            $maxLines = ($user['role'] == 'SUPERADMIN') ? 4 : 10;
                            $requiredLines = ($user['role'] == 'SUPERADMIN') ? 4 : 4;
                            $hasContent = !empty($user["baris{$i}"]) || $i <= $requiredLines;
                            
                            // Hide lines beyond max for user role
                            if ($i > $maxLines) {
                                $hasContent = false;
                            }
                        @endphp
                        <div class="kop-line-group mb-4 {{ !$hasContent ? 'hidden' : '' }}" data-line="{{ $i }}">
                            <div class="flex items-center gap-2 mb-2">
                                <h5 class="font-medium text-gray-700">Baris {{ $i }}</h5>
                                @if($i > 4 && $user['role'] == 'ADMIN')
                                    <button type="button" onclick="removeKopLine({{ $i }})" class="btn btn-xs btn-error btn-outline">
                                        ✕ Hapus
                                    </button>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                <div class="md:col-span-1">
                                    <label for="sizebaris{{ $i }}" class="text-xs text-gray-600">Ukuran Font</label>
                                    <select id="sizebaris{{ $i }}" name="sizebaris{{ $i }}" class="select select-bordered select-sm w-full">
                                        <option value="8px" {{ ($user["sizebaris{$i}"] ?? '13px') == '8px' ? 'selected' : '' }}>8px</option>
                                        <option value="10px" {{ ($user["sizebaris{$i}"] ?? '13px') == '10px' ? 'selected' : '' }}>10px</option>
                                        <option value="12px" {{ ($user["sizebaris{$i}"] ?? '13px') == '12px' ? 'selected' : '' }}>12px</option>
                                        <option value="13px" {{ ($user["sizebaris{$i}"] ?? '13px') == '13px' ? 'selected' : '' }}>13px</option>
                                        <option value="14px" {{ ($user["sizebaris{$i}"] ?? '13px') == '14px' ? 'selected' : '' }}>14px</option>
                                        <option value="16px" {{ ($user["sizebaris{$i}"] ?? '13px') == '16px' ? 'selected' : '' }}>16px</option>
                                        <option value="18px" {{ ($user["sizebaris{$i}"] ?? '13px') == '18px' ? 'selected' : '' }}>18px</option>
                                        <option value="20px" {{ ($user["sizebaris{$i}"] ?? '13px') == '20px' ? 'selected' : '' }}>20px</option>
                                        <option value="22px" {{ ($user["sizebaris{$i}"] ?? '13px') == '22px' ? 'selected' : '' }}>22px</option>
                                        <option value="24px" {{ ($user["sizebaris{$i}"] ?? '13px') == '24px' ? 'selected' : '' }}>24px</option>
                                        <option value="25px" {{ ($user["sizebaris{$i}"] ?? '13px') == '25px' ? 'selected' : '' }}>25px</option>
                                        <option value="28px" {{ ($user["sizebaris{$i}"] ?? '13px') == '28px' ? 'selected' : '' }}>28px</option>
                                        <option value="30px" {{ ($user["sizebaris{$i}"] ?? '13px') == '30px' ? 'selected' : '' }}>30px</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label for="baris{{ $i }}" class="text-xs text-gray-600">Teks Baris {{ $i }}</label>
                                    <input type="text" 
                                           id="baris{{ $i }}" 
                                           name="baris{{ $i }}" 
                                           class="input input-bordered input-sm w-full" 
                                           {{ $i <= 4 ? 'required' : '' }}
                                           value="{{ $user["baris{$i}"] ?? '' }}" 
                                           placeholder="Masukkan teks untuk baris {{ $i }}..." />
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                
                <!-- Add Line Button -->
                @if($user['role'] == 'ADMIN')
                <div class="mt-4">
                    <button type="button" onclick="addKopLine()" id="add-kop-line-btn" class="btn btn-sm btn-success btn-outline">
                        ➕ Tambah Baris Kop Surat
                    </button>
                    <span class="text-xs text-gray-500 ml-2">Maksimal 10 baris</span>
                </div>
                @else
                <div class="mt-4">
                    <span class="text-xs text-gray-500">Super Admin terbatas 4 baris kop surat</span>
                </div>
                @endif
            </div>

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
<div data-window-var="userEditData" 
     data-user-kelurahan-json='@json($user->userKelurahan->toArray())'
     data-user-kelurahan="{{ $user['kelurahan'] ?? '' }}"
     data-user-kecamatan="{{ $user['kecamatan'] ?? '' }}"
     data-auth-user-role="{{ Auth::user()->role }}"
     data-kop-surat-preview-route="{{ route('kop-surat.preview.pdf') }}"
     data-csrf-token="{{ csrf_token() }}"
     style="display:none;">
</div>
<script src="{{ asset('js/user/edit.js') }}"></script>

@endsection
