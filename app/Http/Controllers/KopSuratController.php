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
        // Validasi data dari request atau gunakan data user yang sedang login
        $kopData = [
            'sizebaris1' => $request->input('sizebaris1', Auth::user()->sizebaris1 ?? '18px'),
            'baris1' => $request->input('baris1', Auth::user()->baris1 ?? ''),
            'sizebaris2' => $request->input('sizebaris2', Auth::user()->sizebaris2 ?? '25px'),
            'baris2' => $request->input('baris2', Auth::user()->baris2 ?? ''),
            'sizebaris3' => $request->input('sizebaris3', Auth::user()->sizebaris3 ?? '25px'),
            'baris3' => $request->input('baris3', Auth::user()->baris3 ?? ''),
            'sizebaris4' => $request->input('sizebaris4', Auth::user()->sizebaris4 ?? '13px'),
            'baris4' => $request->input('baris4', Auth::user()->baris4 ?? ''),
            'sizebaris5' => $request->input('sizebaris5', Auth::user()->sizebaris5 ?? '13px'),
            'baris5' => $request->input('baris5', Auth::user()->baris5 ?? ''),
        ];

        // Generate PDF preview dengan layout khusus untuk kop surat
        $pdf = Pdf::loadView('kop-surat-preview', compact('kopData'));
        
        // Return PDF untuk preview di browser
        return $pdf->stream('preview-kop-surat.pdf');
    }
}
