<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserKelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Jika admin, hanya tampilkan data diri sendiri
        if ($user->role === 'ADMIN') {
            $users = collect([$user]); // Hanya user yang login
        } else {
            // Superadmin bisa lihat semua user
            $query = User::with('userKelurahan');
            
            // Filter berdasarkan kelurahan jika ada parameter
            if ($request->has('kelurahan') && $request->kelurahan !== '') {
                $kelurahanFilter = $request->kelurahan;
                $query->where(function($q) use ($kelurahanFilter) {
                    // Cari di kolom kelurahan langsung (untuk user lama atau superadmin)
                    $q->where('kelurahan', $kelurahanFilter)
                      // Atau cari di relasi userKelurahan (untuk admin dengan multiple kelurahan)
                      ->orWhereHas('userKelurahan', function($subQuery) use ($kelurahanFilter) {
                          $subQuery->where('kelurahan', $kelurahanFilter);
                      });
                });
            }
            
            // Search berdasarkan nama atau email
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('fullname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            
            $users = $query->orderBy('created_at', 'desc')->get();
        }
        
        return view('pages.user.index', [
            'page_name' => 'user-management',
            'users' => $users,
            'selectedKelurahan' => $request->kelurahan ?? '',
            'searchQuery' => $request->search ?? ''
        ]);
    }

    public function create()
    {
        // Hanya superadmin yang bisa create user baru
        if (Auth::user()->role !== 'SUPERADMIN') {
            abort(403, 'Access denied. Only superadmin can create new users.');
        }
        
        abort(404);
    }

    public function store(Request $request)
    {
        // Hanya superadmin yang bisa create user baru
        if (Auth::user()->role !== 'SUPERADMIN') {
            abort(403, 'Access denied. Only superadmin can create new users.');
        }
        
        // Validasi request
        $rules = [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:ADMIN,SUPERADMIN',
        ];
        
        // Jika role adalah ADMIN, kelurahan dan kecamatan wajib diisi
        if ($request->role === 'ADMIN') {
            $rules['kelurahan'] = 'required|array|min:1';
            $rules['kelurahan.*'] = 'required|string';
            $rules['kecamatan'] = 'required|string';
            
            // Validasi kelurahan tidak boleh duplikat
            $request->validate($rules);
            
            $kelurahanList = $request->input('kelurahan', []);
            $uniqueKelurahan = array_unique(array_filter($kelurahanList));
            
            if (count($kelurahanList) !== count($uniqueKelurahan)) {
                return back()->withErrors(['kelurahan' => 'Kelurahan tidak boleh duplikat.'])->withInput();
            }
        } else {
            $request->validate($rules);
        }
        
        DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            
            // Hapus kelurahan dari data utama karena akan disimpan di tabel terpisah
            $kelurahanList = $data['kelurahan'] ?? [];
            $kecamatan = $data['kecamatan'] ?? null;
            unset($data['kelurahan']);
            unset($data['kecamatan']);

            $user = User::create($data);

            // Jika role ADMIN, simpan kelurahan di tabel user_kelurahan
            if ($request->role === 'ADMIN' && !empty($kelurahanList)) {
                foreach ($kelurahanList as $kelurahan) {
                    if (!empty($kelurahan)) {
                        UserKelurahan::create([
                            'user_id' => $user->id,
                            'kelurahan' => $kelurahan,
                            'kecamatan' => $kecamatan
                        ]);
                    }
                }
            }
        });

        return redirect(route('manajemen-user.index'))->with('success', 'User berhasil ditambahkan');
    }

    public function show()
    {
        abort(404);
    }

    public function edit(User $manajemen_user)
    {
        $currentUser = Auth::user();
        
        // Admin hanya bisa edit profil sendiri, Superadmin bisa edit semua
        if ($currentUser->role === 'ADMIN' && $currentUser->id !== $manajemen_user->id) {
            abort(403, 'Access denied. You can only edit your own profile.');
        }
        
        return view('pages.user.edit', [
            'page_name' => 'user',
            'user' => $manajemen_user,
            'is_own_profile' => $currentUser->id === $manajemen_user->id,
            'can_edit_role' => $currentUser->role === 'SUPERADMIN'
        ]);
    }

    public function update(Request $request, User $manajemen_user)
    {
        $currentUser = Auth::user();
        
        // Admin hanya bisa edit diri sendiri
        if ($currentUser->role === 'ADMIN' && $currentUser->id !== $manajemen_user->id) {
            abort(403, 'Access denied. You can only edit your own profile.');
        }
        
        // Validasi request
        $rules = [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $manajemen_user->id,
            'password' => 'nullable|string|min:6',
        ];
        
        // Jika superadmin yang edit dan role berubah jadi ADMIN, kelurahan dan kecamatan wajib diisi
        if ($currentUser->role === 'SUPERADMIN' && $request->role === 'ADMIN') {
            $rules['kelurahan'] = 'required|array|min:1';
            $rules['kelurahan.*'] = 'required|string';
            $rules['kecamatan'] = 'required|string';
        }
        
        // Jika user yang sedang diedit adalah ADMIN (edit profile sendiri), kelurahan dan kecamatan wajib diisi
        if ($manajemen_user->role === 'ADMIN') {
            $rules['kelurahan'] = 'required|array|min:1';
            $rules['kelurahan.*'] = 'required|string';
            $rules['kecamatan'] = 'required|string';
        }
        
        $request->validate($rules);
        
        // Validasi kelurahan tidak boleh duplikat jika ada
        if ($request->has('kelurahan') && is_array($request->kelurahan)) {
            $kelurahanList = $request->input('kelurahan', []);
            $uniqueKelurahan = array_unique(array_filter($kelurahanList));
            
            if (count($kelurahanList) !== count($uniqueKelurahan)) {
                return back()->withErrors(['kelurahan' => 'Kelurahan tidak boleh duplikat.'])->withInput();
            }
        }
        
        DB::transaction(function () use ($request, $manajemen_user, $currentUser) {
            $data = $request->all();
            
            // Admin tidak bisa mengubah role mereka sendiri
            if ($currentUser->role === 'ADMIN' && isset($data['role'])) {
                unset($data['role']);
            }

            if (!$data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            // Handle kelurahan for ADMIN role
            $kelurahanList = $data['kelurahan'] ?? [];
            $kecamatan = $data['kecamatan'] ?? null;
            unset($data['kelurahan']);
            unset($data['kecamatan']);

            // Update user data
            $manajemen_user->update($data);

            // Update kelurahan jika user adalah ADMIN atau berubah menjadi ADMIN
            if (($manajemen_user->role === 'ADMIN' || $request->role === 'ADMIN') && !empty($kelurahanList)) {
                // Hapus kelurahan lama
                UserKelurahan::where('user_id', $manajemen_user->id)->delete();
                
                // Tambah kelurahan baru
                foreach ($kelurahanList as $kelurahan) {
                    if (!empty($kelurahan)) {
                        UserKelurahan::create([
                            'user_id' => $manajemen_user->id,
                            'kelurahan' => $kelurahan,
                            'kecamatan' => $kecamatan
                        ]);
                    }
                }
            }
        });

        return redirect(route('manajemen-user.edit', ['manajemen_user' => $manajemen_user['id']]))->with('success', 'user berhasil diubah');
    }

    public function destroy(User $manajemen_user)
    {
        $currentUser = Auth::user();
        
        // Admin tidak bisa delete user (termasuk diri sendiri)
        // Hanya superadmin yang bisa delete user
        if ($currentUser->role !== 'SUPERADMIN') {
            abort(403, 'Access denied. Only superadmin can delete users.');
        }
        
        // Superadmin tidak bisa delete diri sendiri
        if ($currentUser->id === $manajemen_user->id) {
            return redirect(route('manajemen-user.index'))->with('error', 'Anda tidak dapat menghapus akun Anda sendiri. Silakan minta super admin lain untuk melakukan tindakan ini.');
        }

        // Jika user yang akan dihapus adalah SUPERADMIN, pastikan masih ada SUPERADMIN lain
        if ($manajemen_user->role === 'SUPERADMIN') {
            $superadminCount = User::where('role', 'SUPERADMIN')->count();
            if ($superadminCount <= 1) {
                return redirect(route('manajemen-user.index'))->with('error', 'Tidak dapat menghapus super admin terakhir. Sistem harus memiliki minimal satu super admin.');
            }
        }

        if (!$manajemen_user->delete()) {
            Log::warning('Failed to delete user', [
                'deleted_user_id' => $manajemen_user->id,
                'deleted_user_email' => $manajemen_user->email,
                'deleted_by' => $currentUser->id,
                'deleted_by_email' => $currentUser->email
            ]);
            return redirect(route('manajemen-user.edit', ['manajemen_user' => $manajemen_user['id']]))->with('error', 'user gagal dihapus');
        }

        // Log successful deletion for audit trail
        Log::info('User deleted successfully', [
            'deleted_user_id' => $manajemen_user->id,
            'deleted_user_email' => $manajemen_user->email,
            'deleted_user_role' => $manajemen_user->role,
            'deleted_by' => $currentUser->id,
            'deleted_by_email' => $currentUser->email,
            'timestamp' => now()
        ]);

        return redirect(route('manajemen-user.index'))->with('success', 'user berhasil dihapus');
    }
}
