<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KopSuratController extends Controller
{
    /**
     * Preview kop surat dalam format PDF
     */
    public function preview(Request $request)
    {
        // Validasi data dari request atau gunakan data user yang sedang login untuk semua baris (1-10)
        $kopData = [];
        
        // Default sizes untuk baris-baris tertentu
        $defaultSizes = [
            1 => '18px',
            2 => '25px', 
            3 => '25px',
            4 => '13px',
            5 => '13px',
            6 => '13px',
            7 => '13px',
            8 => '13px',
            9 => '13px',
            10 => '13px'
        ];
        
        // Loop untuk semua baris kop surat (1-10)
        for ($i = 1; $i <= 10; $i++) {
            $kopData["sizebaris{$i}"] = $request->input("sizebaris{$i}", 
                Auth::user()->{"sizebaris{$i}"} ?? $defaultSizes[$i]
            );
            $kopData["baris{$i}"] = $request->input("baris{$i}", 
                Auth::user()->{"baris{$i}"} ?? ''
            );
        }

        // Generate PDF preview dengan layout khusus untuk kop surat
        $pdf = Pdf::loadView('kop-surat-preview', compact('kopData'));
        
        // Return PDF untuk preview di browser
        return $pdf->stream('preview-kop-surat.pdf');
    }
}
