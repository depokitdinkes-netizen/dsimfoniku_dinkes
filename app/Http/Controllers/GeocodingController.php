<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodingController extends Controller
{
    /**
     * Search for addresses using Nominatim
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query parameter is required'
            ], 400);
        }
        
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'DSIMfoniku-Dinkes/1.0 (https://dinkes-depok.id)',
                'Referer' => config('app.url')
            ])
            ->timeout(10)
            ->get('https://nominatim.openstreetmap.org/search', [
                'format' => 'json',
                'q' => $query,
                'limit' => 5,
                'countrycodes' => 'id',
                'addressdetails' => 1
            ]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from geocoding service'
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geocoding service error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reverse geocode coordinates to address
     */
    public function reverse(Request $request)
    {
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        
        if (empty($lat) || empty($lon)) {
            return response()->json([
                'success' => false,
                'message' => 'Latitude and longitude parameters are required'
            ], 400);
        }
        
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'DSIMfoniku-Dinkes/1.0 (https://dinkes-depok.id)',
                'Referer' => config('app.url')
            ])
            ->timeout(10)
            ->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lon,
                'zoom' => 18,
                'addressdetails' => 1
            ]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from geocoding service'
            ], $response->status());
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geocoding service error: ' . $e->getMessage()
            ], 500);
        }
    }
}
