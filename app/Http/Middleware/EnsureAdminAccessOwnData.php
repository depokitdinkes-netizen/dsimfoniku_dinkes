<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccessOwnData
{
    /**
     * Handle an incoming request.
     * Admin hanya bisa mengakses data yang dibuat oleh dirinya sendiri.
     * Superadmin bisa mengakses semua data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        // Jika SUPERADMIN, boleh akses semua
        if ($user->role === 'SUPERADMIN') {
            return $next($request);
        }
        
        // Jika ADMIN, perlu validasi lebih lanjut
        if ($user->role === 'ADMIN') {
            // Untuk route yang memerlukan ID (show, edit, update, destroy)
            $routeParameters = $request->route()->parameters();
            
            if (!empty($routeParameters)) {
                // Ambil ID dari parameter route
                $resourceId = array_values($routeParameters)[0];
                
                // Cek apakah data milik user yang login
                $modelClass = $this->getModelClass($request->route()->getName());
                
                if ($modelClass && class_exists($modelClass)) {
                    $model = $modelClass::find($resourceId);
                    
                    if (!$model || $model->user_id !== $user->id) {
                        abort(403, 'Access denied. You can only access your own data.');
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Get model class based on route name
     */
    private function getModelClass($routeName): ?string
    {
        $routeToModel = [
            'restoran.' => 'App\\Models\\FormIKL\\Restoran',
            'jasa-boga-katering.' => 'App\\Models\\FormIKL\\JasaBogaKatering',
            'rumah-makan.' => 'App\\Models\\FormIKL\\RumahMakan',
            'kantin.' => 'App\\Models\\FormIKL\\Kantin',
            'gerai-kantin.' => 'App\\Models\\FormIKL\\GeraiKantin',
            'depot-air-minum.' => 'App\\Models\\FormIKL\\DepotAirMinum',
            'sumur-gali.' => 'App\\Models\\FormIKL\\SumurGali',
            'sumur-bor-pompa.' => 'App\\Models\\FormIKL\\SumurBorPompa',
            'perpipaan.' => 'App\\Models\\FormIKL\\Perpipaan',
            'perpipaan-non-pdam.' => 'App\\Models\\FormIKL\\PerpipaanNonPdam',
            'perlindungan-mata-air.' => 'App\\Models\\FormIKL\\PerlindunganMataAir',
            'penyimpanan-air-hujan.' => 'App\\Models\\FormIKL\\PenyimpananAirHujan',
            'gerai-pangan-jajanan.' => 'App\\Models\\FormIKL\\GeraiPanganJajanan',
            'gerai-jajanan-keliling.' => 'App\\Models\\FormIKL\\GeraiJajananKeliling',
            'sekolah.' => 'App\\Models\\FormIKL\\Sekolah',
            'rumah-sakit.' => 'App\\Models\\FormIKL\\RumahSakit',
            'puskesmas.' => 'App\\Models\\FormIKL\\Puskesmas',
            'tempat-rekreasi.' => 'App\\Models\\FormIKL\\TempatRekreasi',
            'renang-pemandian.' => 'App\\Models\\FormIKL\\RenangPemandian',
            'akomodasi.' => 'App\\Models\\FormIKL\\Akomodasi',
            'akomodasi-lain.' => 'App\\Models\\FormIKL\\AkomodasiLain',
            'tempat-olahraga.' => 'App\\Models\\FormIKL\\TempatOlahraga',
            'pasar.' => 'App\\Models\\FormIKL\\Pasar',
            'pasar-internal.' => 'App\\Models\\FormIKL\\PasarInternal',
            'tempat-ibadah.' => 'App\\Models\\FormIKL\\TempatIbadah',
        ];

        foreach ($routeToModel as $prefix => $modelClass) {
            if (str_starts_with($routeName, $prefix)) {
                return $modelClass;
            }
        }

        return null;
    }
}
