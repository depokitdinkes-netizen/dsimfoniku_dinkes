@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-6 border-b flex items-center justify-between gap-5">
    @auth
    @if (Auth::user()->role == "SUPERADMIN")
    <h1 class="font-extrabold sm:text-xl">Manajemen User</h1>
    @else
    <h1 class="font-extrabold sm:text-xl">Profil Saya</h1>
    @endif
    @endauth
    <img src="{{ asset('logo/depok-city.png') }}" alt="depok city" class="h-6 sm:h-9 object-cover" />
</div>

<div class="p-3 sm:p-6">
    @auth
    @if (Auth::user()->role == "SUPERADMIN")
    <form method="GET" action="{{ route('manajemen-user.index') }}" class="mb-6">
        <div class="flex justify-end gap-3 flex-wrap">
            <div class="join">
                <button type="submit" class="btn btn-primary btn-square join-item">
                    <i class="ri-search-line"></i>
                </button>
                <input type="text" name="search" class="input input-bordered join-item w-full" placeholder="Cari user ..." value="{{ $searchQuery ?? '' }}" id="search-user" />
            </div>
            
            <div class="join">
                <select name="kelurahan" class="select select-bordered join-item" id="filter-kelurahan">
                    <option value="">Semua Kelurahan</option>
                    <option value="Abadijaya" {{ ($selectedKelurahan ?? '') == 'Abadijaya' ? 'selected' : '' }}>Abadijaya</option>
                    <option value="Sukatani" {{ ($selectedKelurahan ?? '') == 'Sukatani' ? 'selected' : '' }}>Sukatani</option>
                    <option value="Sukamaju" {{ ($selectedKelurahan ?? '') == 'Sukamaju' ? 'selected' : '' }}>Sukamaju</option>
                    <option value="Cilodong" {{ ($selectedKelurahan ?? '') == 'Cilodong' ? 'selected' : '' }}>Cilodong</option>
                    <option value="Kalimulya" {{ ($selectedKelurahan ?? '') == 'Kalimulya' ? 'selected' : '' }}>Kalimulya</option>
                    <option value="Serua" {{ ($selectedKelurahan ?? '') == 'Serua' ? 'selected' : '' }}>Serua</option>
                    <option value="Jatijajar" {{ ($selectedKelurahan ?? '') == 'Jatijajar' ? 'selected' : '' }}>Jatijajar</option>
                    <option value="Tapos" {{ ($selectedKelurahan ?? '') == 'Tapos' ? 'selected' : '' }}>Tapos</option>
                    <option value="Curug" {{ ($selectedKelurahan ?? '') == 'Curug' ? 'selected' : '' }}>Curug</option>
                    <option value="Bojongsari Baru" {{ ($selectedKelurahan ?? '') == 'Bojongsari Baru' ? 'selected' : '' }}>Bojongsari Baru</option>
                    <option value="Bojongsari Lama" {{ ($selectedKelurahan ?? '') == 'Bojongsari Lama' ? 'selected' : '' }}>Bojongsari Lama</option>
                    <option value="Duren Mekar" {{ ($selectedKelurahan ?? '') == 'Duren Mekar' ? 'selected' : '' }}>Duren Mekar</option>
                    <option value="Duren Seribu" {{ ($selectedKelurahan ?? '') == 'Duren Seribu' ? 'selected' : '' }}>Duren Seribu</option>
                    <option value="Kemiri Muka" {{ ($selectedKelurahan ?? '') == 'Kemiri Muka' ? 'selected' : '' }}>Kemiri Muka</option>
                    <option value="Beji" {{ ($selectedKelurahan ?? '') == 'Beji' ? 'selected' : '' }}>Beji</option>
                    <option value="Beji Timur" {{ ($selectedKelurahan ?? '') == 'Beji Timur' ? 'selected' : '' }}>Beji Timur</option>
                    <option value="Kukusan" {{ ($selectedKelurahan ?? '') == 'Kukusan' ? 'selected' : '' }}>Kukusan</option>
                    <option value="Pondok Cina" {{ ($selectedKelurahan ?? '') == 'Pondok Cina' ? 'selected' : '' }}>Pondok Cina</option>
                    <option value="Tanah Baru" {{ ($selectedKelurahan ?? '') == 'Tanah Baru' ? 'selected' : '' }}>Tanah Baru</option>
                    <option value="Tugu" {{ ($selectedKelurahan ?? '') == 'Tugu' ? 'selected' : '' }}>Tugu</option>
                    <option value="Cisalak" {{ ($selectedKelurahan ?? '') == 'Cisalak' ? 'selected' : '' }}>Cisalak</option>
                    <option value="Cisalak Pasar" {{ ($selectedKelurahan ?? '') == 'Cisalak Pasar' ? 'selected' : '' }}>Cisalak Pasar</option>
                    <option value="Cimanggis" {{ ($selectedKelurahan ?? '') == 'Cimanggis' ? 'selected' : '' }}>Cimanggis</option>
                    <option value="Harjamukti" {{ ($selectedKelurahan ?? '') == 'Harjamukti' ? 'selected' : '' }}>Harjamukti</option>
                    <option value="Mekarsari" {{ ($selectedKelurahan ?? '') == 'Mekarsari' ? 'selected' : '' }}>Mekarsari</option>
                    <option value="Pasir Gunung Selatan" {{ ($selectedKelurahan ?? '') == 'Pasir Gunung Selatan' ? 'selected' : '' }}>Pasir Gunung Selatan</option>
                    <option value="Cipayung" {{ ($selectedKelurahan ?? '') == 'Cipayung' ? 'selected' : '' }}>Cipayung</option>
                    <option value="Bojong Pondok Terong" {{ ($selectedKelurahan ?? '') == 'Bojong Pondok Terong' ? 'selected' : '' }}>Bojong Pondok Terong</option>
                    <option value="Pondok Terong" {{ ($selectedKelurahan ?? '') == 'Pondok Terong' ? 'selected' : '' }}>Pondok Terong</option>
                    <option value="Ratujaya" {{ ($selectedKelurahan ?? '') == 'Ratujaya' ? 'selected' : '' }}>Ratujaya</option>
                    <option value="Sawangan Baru" {{ ($selectedKelurahan ?? '') == 'Sawangan Baru' ? 'selected' : '' }}>Sawangan Baru</option>
                    <option value="Sawangan Lama" {{ ($selectedKelurahan ?? '') == 'Sawangan Lama' ? 'selected' : '' }}>Sawangan Lama</option>
                    <option value="Bedahan" {{ ($selectedKelurahan ?? '') == 'Bedahan' ? 'selected' : '' }}>Bedahan</option>
                    <option value="Pancoran Mas" {{ ($selectedKelurahan ?? '') == 'Pancoran Mas' ? 'selected' : '' }}>Pancoran Mas</option>
                    <option value="Depok" {{ ($selectedKelurahan ?? '') == 'Depok' ? 'selected' : '' }}>Depok</option>
                    <option value="Depok Jaya" {{ ($selectedKelurahan ?? '') == 'Depok Jaya' ? 'selected' : '' }}>Depok Jaya</option>
                    <option value="Mampang" {{ ($selectedKelurahan ?? '') == 'Mampang' ? 'selected' : '' }}>Mampang</option>
                    <option value="Rangkapan Jaya Baru" {{ ($selectedKelurahan ?? '') == 'Rangkapan Jaya Baru' ? 'selected' : '' }}>Rangkapan Jaya Baru</option>
                    <option value="Rangkapan Jaya" {{ ($selectedKelurahan ?? '') == 'Rangkapan Jaya' ? 'selected' : '' }}>Rangkapan Jaya</option>
                    <option value="Pasir Putih" {{ ($selectedKelurahan ?? '') == 'Pasir Putih' ? 'selected' : '' }}>Pasir Putih</option>
                    <option value="Cinangka" {{ ($selectedKelurahan ?? '') == 'Cinangka' ? 'selected' : '' }}>Cinangka</option>
                    <option value="Gandul" {{ ($selectedKelurahan ?? '') == 'Gandul' ? 'selected' : '' }}>Gandul</option>
                    <option value="Kelapa Dua" {{ ($selectedKelurahan ?? '') == 'Kelapa Dua' ? 'selected' : '' }}>Kelapa Dua</option>
                    <option value="Krukut" {{ ($selectedKelurahan ?? '') == 'Krukut' ? 'selected' : '' }}>Krukut</option>
                    <option value="Limo" {{ ($selectedKelurahan ?? '') == 'Limo' ? 'selected' : '' }}>Limo</option>
                    <option value="Meruyung" {{ ($selectedKelurahan ?? '') == 'Meruyung' ? 'selected' : '' }}>Meruyung</option>
                    <option value="Cimpaeun" {{ ($selectedKelurahan ?? '') == 'Cimpaeun' ? 'selected' : '' }}>Cimpaeun</option>
                    <option value="Cireunde" {{ ($selectedKelurahan ?? '') == 'Cireunde' ? 'selected' : '' }}>Cireunde</option>
                    <option value="Karang Tengah" {{ ($selectedKelurahan ?? '') == 'Karang Tengah' ? 'selected' : '' }}>Karang Tengah</option>
                    <option value="Sukamulya" {{ ($selectedKelurahan ?? '') == 'Sukamulya' ? 'selected' : '' }}>Sukamulya</option>
                    <option value="Pengasinan" {{ ($selectedKelurahan ?? '') == 'Pengasinan' ? 'selected' : '' }}>Pengasinan</option>
                    <option value="Ratu Jaya" {{ ($selectedKelurahan ?? '') == 'Ratu Jaya' ? 'selected' : '' }}>Ratu Jaya</option>
                    <option value="Jatimulya" {{ ($selectedKelurahan ?? '') == 'Jatimulya' ? 'selected' : '' }}>Jatimulya</option>
                </select>
                
                <a href="{{ route('manajemen-user.index') }}" class="btn btn-secondary join-item">
                    <i class="ri-refresh-line"></i>
                </a>
            </div>
            
            <button type="button" onclick="add_user.showModal()" class="btn btn-primary btn-outline">
                <span>TAMBAH USER</span>
                <i class="ri-add-line"></i>
            </button>
        </div>
    </form>
    @endif
    @endauth

    @auth
    @if (Auth::user()->role == "SUPERADMIN")
    <div class="mb-4">
        <div class="alert alert-info">
            <i class="ri-information-line"></i>
            <span>Menampilkan {{ count($users) }} user
                @if($selectedKelurahan || $searchQuery)
                    (filtered)
                @endif
            </span>
        </div>
    </div>
    @endif
    @endauth

    <div class="overflow-x-auto mb-2 bg-white rounded-lg">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th></th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Kelurahan</th>
                    @auth
                    @if (Auth::user()->role == "SUPERADMIN")
                    <th>Aksi</th>
                    @else
                    <th>Aksi</th>
                    @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @if (count($users) == 0)
                <tr>
                    <td colspan="6" class="text-center">Tidak ada user yang dapat ditampilkan</td>
                </tr>
                @endif
                @foreach ($users as $index => $user)
                <tr class="user-data-row">
                    <th>{{ $index + 1 }}</th>
                    <td>{{ $user['fullname'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['role'] }}</td>
                    <td>
                        @if($user['role'] === 'ADMIN' && $user->userKelurahan->count() > 0)
                            @foreach($user->userKelurahan as $index => $userKel)
                                <span class="badge badge-primary badge-sm mr-1 mb-1">{{ $userKel->kelurahan }}</span>
                                @if(($index + 1) % 3 === 0)<br>@endif
                            @endforeach
                        @else
                            {{ $user['kelurahan'] ?? '-' }}
                        @endif
                    </td>
                    @auth
                    @if (Auth::user()->role == "SUPERADMIN")
                    <td>
                        <div class="flex gap-2">
                            <a href="{{ route('manajemen-user.edit', ['manajemen_user' => $user['id']]) }}" class="btn btn-warning btn-outline btn-sm">Edit</a>
                            @if (Auth::user()->id !== $user['id'])
                                @php
                                    $canDelete = true;
                                    // Jika user yang akan dihapus adalah SUPERADMIN
                                    if ($user['role'] === 'SUPERADMIN') {
                                        $superadminCount = $users->where('role', 'SUPERADMIN')->count();
                                        $canDelete = $superadminCount > 1;
                                    }
                                @endphp
                                @if ($canDelete)
                                <button type="button" onclick="openDeleteModal('{{ $user['id'] }}', '{{ $user['fullname'] }}', '{{ $user['email'] }}', '{{ $user['role'] }}')" class="btn btn-error btn-outline btn-sm">Delete</button>
                                @endif
                            @endif
                        </div>
                    </td>
                    @else
                    <td>
                        <a href="{{ route('manajemen-user.edit', ['manajemen_user' => $user['id']]) }}" class="btn btn-warning btn-outline">Edit Profil</a>
                    </td>
                    @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@auth
@if (Auth::user()->role == "SUPERADMIN")
<x-modal.add-user />

<!-- Modal Delete User -->
<dialog id="delete_user_modal" class="modal">
    <form method="POST" action="" class="modal-box max-w-[28rem]" id="delete_user_form">
        @csrf
        @method('DELETE')

        <h3 class="font-bold text-lg text-error">⚠️ Konfirmasi Hapus User</h3>

        <div class="py-4">
            <p class="mb-2">Apakah Anda yakin ingin menghapus user:</p>
            <div class="bg-base-200 p-3 rounded-lg mb-3">
                <p class="font-semibold" id="user_name_to_delete"></p>
                <p class="text-sm opacity-70" id="user_email_to_delete"></p>
                <p class="text-sm font-medium" id="user_role_to_delete"></p>
            </div>
            <div class="alert alert-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 15c-.77.833.192 2.5 1.732 2.5z" /></svg>
                <span class="text-sm">Tindakan ini tidak dapat dibatalkan. Data user akan dihapus permanen.</span>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <button type="button" class="btn btn-outline" onclick="delete_user_modal.close()">Batal</button>
            <button type="submit" class="btn btn-error">Ya, Hapus User</button>
        </div>
    </form>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endif
@endauth

<script>
// Auto-submit form saat filter berubah
document.getElementById('filter-kelurahan').addEventListener('change', function() {
    this.form.submit();
});

// Search dengan delay untuk mengurangi request
let searchTimeout;
document.getElementById('search-user').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const form = this.form;
    
    searchTimeout = setTimeout(function() {
        form.submit();
    }, 500); // Submit setelah 500ms tidak ada perubahan
});

// Function untuk membuka modal delete
function openDeleteModal(userId, userName, userEmail, userRole) {
    const modal = document.getElementById('delete_user_modal');
    const form = document.getElementById('delete_user_form');
    const userNameSpan = document.getElementById('user_name_to_delete');
    const userEmailSpan = document.getElementById('user_email_to_delete');
    const userRoleSpan = document.getElementById('user_role_to_delete');
    
    // Set action URL untuk form menggunakan route helper Laravel
    form.action = "{{ url('manajemen-user') }}/" + userId;
    
    // Set informasi user yang akan dihapus
    userNameSpan.textContent = userName;
    userEmailSpan.textContent = userEmail;
    userRoleSpan.textContent = userRole;
    
    // Tampilkan modal
    modal.showModal();
}
</script>

@endsection
