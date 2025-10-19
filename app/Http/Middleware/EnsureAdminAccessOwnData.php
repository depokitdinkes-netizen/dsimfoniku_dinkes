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
     * SUPERADMIN: Bisa edit/delete/export PDF semua form termasuk yang dibuat guest
     * ADMIN: Bisa lihat semua history hasil inspeksi dan export raw excel, 
     *        tapi hanya bisa edit/delete/export PDF untuk form miliknya sendiri
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow guest access for create and store methods
        $routeName = $request->route()->getName();
        $method = $request->method();
        
        if (!Auth::check()) {
            // Block access to pasar eksternal forms for guests (pasar internal still allowed)
            if (str_starts_with($routeName, 'pasar.')) {
                abort(403, 'Unauthorized - Login required for pasar eksternal forms');
            }
            
            // Allow guest to access other create forms, store data, and show results (including pasar internal)
            if (str_ends_with($routeName, '.create') || 
                (str_ends_with($routeName, '.store') && $method === 'POST') ||
                str_ends_with($routeName, '.show')) {
                return $next($request);
            }
            
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        
        // Jika SUPERADMIN, boleh akses semua (termasuk edit/delete form guest)
        if ($user->role === 'SUPERADMIN') {
            return $next($request);
        }
        
        // Jika ADMIN, perlu validasi lebih lanjut
        if ($user->role === 'ADMIN') {
            $routeParameters = $request->route()->parameters();
            
            if (!empty($routeParameters)) {
                // Ambil parameter pertama dari route (bisa berupa ID atau model instance dari route model binding)
                $resourceParam = array_values($routeParameters)[0];
                
                // Cek apakah ini adalah operasi yang memerlukan ownership (edit, update, destroy, export PDF)
                $restrictedActions = ['edit', 'update', 'destroy'];
                $currentAction = explode('.', $routeName);
                $action = end($currentAction);
                
                // Cek juga untuk export PDF (parameter export=pdf)
                $isExportPdf = $request->has('export') && $request->get('export') === 'pdf';
                
                if (in_array($action, $restrictedActions) || ($action === 'index' && $isExportPdf)) {
                    $model = null;
                    
                    // Cek apakah parameter sudah berupa model instance (route model binding)
                    if (is_object($resourceParam) && method_exists($resourceParam, 'getKey')) {
                        // Sudah berupa model instance dari route model binding
                        $model = $resourceParam;
                    } else {
                        // Masih berupa ID, perlu di-query
                        $modelClass = $this->getModelClass($routeName);
                        
                        if ($modelClass && class_exists($modelClass)) {
                            // Pastikan resourceParam adalah integer/string yang valid
                            if (!$resourceParam || !is_numeric($resourceParam)) {
                                abort(400, 'Invalid resource ID.');
                            }
                            
                            $model = $modelClass::find($resourceParam);
                        }
                    }
                    
                    if (!$model) {
                        abort(404, 'Data not found.');
                    }
                    
                    // Pastikan model memiliki kolom user_id
                    if (!isset($model->user_id)) {
                        abort(500, 'Model does not have user_id field.');
                    }
                    
                    // Admin hanya bisa edit/delete/export PDF data miliknya sendiri
                    // Data guest (user_id = 3) atau data admin lain tidak bisa diedit/export PDF
                    // Gunakan loose comparison (!=) untuk menghindari masalah tipe data
                    if ((int)$model->user_id !== (int)$user->id) {
                        if ($isExportPdf) {
                            abort(403, 'Access denied. You can only export PDF for your own data.');
                        } else {
                            abort(403, 'Access denied. You can only edit/delete your own data. This form is view-only for you.');
                        }
                    }
                }
                // Untuk show dan index, admin bisa melihat semua data (view-only untuk yang bukan miliknya)
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
