<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserKelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserKelurahanController extends Controller
{
    /**
     * Get kecamatan and kelurahan for the logged-in user
     */
    public function getUserKelurahan(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $user = Auth::user();
        
        // If user is SUPERADMIN, return empty array (they have access to all)
        if ($user->role === 'SUPERADMIN') {
            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'is_superadmin' => true,
                    'kelurahan_kecamatan' => [] // Superadmin has no restrictions
                ],
                'message' => 'Superadmin has access to all kelurahan/kecamatan'
            ]);
        }

        // Get kelurahan and kecamatan for this user
        $userKelurahan = UserKelurahan::where('user_id', $user->id)->get();

        // Group by kecamatan
        $groupedData = [];
        foreach ($userKelurahan as $item) {
            $kecamatan = $item->kecamatan;
            $kelurahan = $item->kelurahan;
            
            if (!isset($groupedData[$kecamatan])) {
                $groupedData[$kecamatan] = [];
            }
            
            if (!in_array($kelurahan, $groupedData[$kecamatan])) {
                $groupedData[$kecamatan][] = $kelurahan;
            }
        }

        // Get unique kecamatan list
        $kecamatanList = array_keys($groupedData);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'role' => $user->role,
                'is_superadmin' => false,
                'kecamatan' => $kecamatanList,
                'kelurahan_by_kecamatan' => $groupedData,
                'raw_data' => $userKelurahan
            ],
            'message' => 'User kelurahan retrieved successfully'
        ]);
    }
}
