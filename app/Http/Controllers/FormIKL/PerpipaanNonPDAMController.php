<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\PerpipaanNonPdam;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
class PerpipaanNonPDAMController extends Controller {
    protected function informasiUmum() {
        return [
            Form::input('text', 'Nama Sarana', 'subjek'),
            Form::input('select', 'Kategori SAM', 'u001'),
            Form::input('text', 'Nama Instansi / Pemilik Sarana', 'pengelola'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('select', 'SK Pengelola dari Kelurahan', 'sk-pengelola'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Upload Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function kualitasFA() {
        return [
            Form::checkbox('Tidak Keruh', 'kfa001'),
            Form::checkbox('Tidak Berbau', 'kfa002'),
            Form::checkbox('Tidak Berasa', 'kfa003'),
            Form::checkbox('Tidak Berwarna', 'kfa004'),
            Form::select('Apakah air digunakan juga sebagai bahan baku air minum?', 'kfa005', 2),
        ];
    }

    protected function dataKPR() {
        return [
            Form::select('Apakah bak/tangki tidak tertutup?', 'int001'),
            Form::select('Apakah bak/ tangki bagian atas ada retakan atau kebocoran?', 'int002'),
            Form::select('Apakah di atas bak/ tangki kotor, banyak debu dan berlumut?', 'int003'),
            Form::select('Apakah slang penyalur dalam kondisi kotor?', 'int004'),
            Form::select('Apakah kran air dan terminal air bocor atau rusak ?', 'int005'),
            Form::select('Apakah bak/tangki tidak tertutup?', 'int006'),
            Form::select('Tangki mobil dikuras lebih dari 1 bulan sekali', 'int007'),
            Form::select('Apakah ada titik kebocoran antara sumber dan reservoir?', 'int008'),
            Form::select('Apakah ada kotak pemecah tekanan, apakah kotak ditutup?', 'int009'),
            Form::select('Jika ada reservoir: Apakah lubang inspeksi tertutup secara tidak saniter?', 'int010'),
            Form::select('Apakah lubang inspeksi tertutup secara tidak saniter?', 'int011'),
            Form::select('Apakah ada pipa udara yang tidak tertutup secara saniter?', 'int012'),
            Form::select('Apakah reservoir retak atau bocor?', 'int013'),
            Form::select('Apakah ada kebocoran di sistem distribusi?', 'int014'),
            Form::select('Apakah area sekitar keran umum tidak dipagari (dinding batu kering dan/pagar yang tidak lengkap)?', 'int015'),
            Form::select('Apakah air terakumulasi di bawah keran sehingga memerlukan perbaikan saluran pembuangan?', 'int016'),
            Form::select('Apakah ada ekskreta (tinja dan urin) dalam radius 10 meter dari keran?', 'int017'),
        ];
    }

    protected function kualitasFAName() {
        return [
            'kfa001',
            'kfa002',
            'kfa003',
            'kfa004',
            'kfa005',
        ];
    }

    protected function dataKPRName() {
        return [
            'int001',
            'int002',
            'int003',
            'int004',
            'int005',
            'int006',
            'int007',
            'int008',
            'int009',
            'int010',
            'int011',
            'int012',
            'int013',
            'int014',
            'int015',
            'int016',
            'int017',
        ];
    }

    protected function formPenilaianName() {
        return array_merge($this->dataKPRName());
    }

    public function index(Request $request) {
        switch ($request->export) {
            case 'pdf':
                $item = PerpipaanNonPdam::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Perpipaan Non PDAM',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Perpipaan Non PDAM', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::SAM_TUJUH_BELAS)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_PERPIPAAN_NON_PDAM_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return PerpipaanNonPdam::withTrashed()->get()->map(function ($item) {
                            return collect($item->toArray())->map(function ($value) {
                                if ($value === null || $value === '') {
                                    return '';
                                }
                                return $value === 0 ? '0' : $value;
                            })->toArray();
                        });
                    }

                    public function headings(): array {
                        return [
                            'Id',
                            'User ID',
                            'Nama Subjek',
                            'Nama Pengelola',
                            'Alamat',
                            'Kelurahan',
                            'Kecamatan',
                            'Kontak',
                            'SK Pengelola dari Kelurahan',
                            'Status Operasi',

                            'Koordinat',
                            'Nama Pemeriksa',
                            'Instansi Pemeriksa',
                            'Tanggal Penilaian',
                            'Skor',
                            'Hasil IKL',
                            'Rencana Tindak Lanjut',
                            'Dibuat',
                            'Diperbarui',
                            'Dihapus',

                            'Kategori SAM',
                            'Tidak Keruh',
                            'Tidak Berbau',
                            'Tidak Berasa',
                            'Tidak Berwarna',
                            'Apakah air digunakan juga sebagai bahan baku air minum?',

                            'Apakah bak/tangki tidak tertutup?',
                            'Apakah bak/ tangki bagian atas ada retakan atau kebocoran?',
                            'Apakah di atas bak/ tangki kotor, banyak debu dan berlumut?',
                            'Apakah slang penyalur dalam kondisi kotor?',
                            'Apakah kran air dan terminal air bocor atau rusak ?',
                            'Apakah bak/tangki tidak tertutup?',
                            'Tangki mobil dikuras lebih dari 1 bulan sekali',
                            'Apakah ada titik kebocoran antara sumber dan reservoir?',
                            'Apakah ada kotak pemecah tekanan, apakah kotak ditutup?',
                            'Jika ada reservoir: Apakah lubang inspeksi tertutup secara tidak saniter?',
                            'Apakah lubang inspeksi tertutup secara tidak saniter?',
                            'Apakah ada pipa udara yang tidak tertutup secara saniter?',
                            'Apakah reservoir retak atau bocor?',
                            'Apakah ada kebocoran di sistem distribusi?',
                            'Apakah area sekitar keran umum tidak dipagari (dinding batu kering dan/pagar yang tidak lengkap)?',
                            'Apakah air terakumulasi di bawah keran sehingga memerlukan perbaikan saluran pembuangan?',
                            'Apakah ada ekskreta (tinja dan urin) dalam radius 10 meter dari keran?',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_PERPIPAAN_NON_PDAM_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.perpipaan-non-pdam.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'kualitas_fa' => $this->kualitasFA(),
            'data_kpr' => $this->dataKPR(),
        ]);
    }

    public function store(Request $request) {
        try {
            // Check if user is authenticated or guest
            $isGuest = !Auth::check();
            
            if ($isGuest) {
                Log::info('Guest user attempting to store Perpipaan Non PDAM data');
            }

            // Validasi input komprehensif
            $validatedData = $request->validate([
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'koordinat' => 'required|string|max:255',
                'dokumen_slhs' => 'nullable|url|max:2048',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
                'status-operasi' => 'nullable|string',
                'kontak' => 'nullable|numeric',
                'nama-pemeriksa' => 'nullable|string|max:255',
                'instansi-pemeriksa' => 'nullable|string|max:255',
                'tanggal-penilaian' => 'nullable|date',
                'catatan-lain' => 'nullable|string|max:1000',
                'rencana-tindak-lanjut' => 'nullable|string|max:1000',
                'tujuan-ikl' => 'nullable|string|max:255',
            ], [
                'subjek.required' => 'Nama perpipaan non PDAM wajib diisi.',
                'subjek.max' => 'Nama perpipaan non PDAM maksimal 255 karakter.',
                'pengelola.required' => 'Nama pengelola wajib diisi.',
                'pengelola.max' => 'Nama pengelola maksimal 255 karakter.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.max' => 'Alamat maksimal 500 karakter.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'koordinat.required' => 'Koordinat wajib diisi.',
                'dokumen_slhs.url' => 'Link dokumen SLHS harus berupa URL yang valid.',
                'dokumen_slhs.max' => 'Link dokumen SLHS maksimal 2048 karakter.',
                'slhs_issued_date.date' => 'Format tanggal terbit SLHS tidak valid.',
                'slhs_expire_date.date' => 'Format tanggal berakhir SLHS tidak valid.',
                'slhs_expire_date.after_or_equal' => 'Tanggal berakhir SLHS harus setelah tanggal terbit.',
            ]);

            Log::info('Perpipaan Non PDAM form submission started', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola'),
                'is_guest' => $isGuest
            ]);

            $data = $request->all();
            
            // Set user_id: 3 untuk guest, Auth::id() untuk user yang login
            $data['user_id'] = $isGuest ? 3 : Auth::id();
            
            // Handle custom instansi pemeriksa input
            if (!empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // Auto-calculate expire date from issued date + 3 years
            if (!empty($data['slhs_issued_date'])) {
                $issuedDate = \Carbon\Carbon::parse($data['slhs_issued_date']);
                $data['slhs_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
            }
            
            foreach ($this->kualitasFAName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

            $insert = PerpipaanNonPdam::create($data);

            if (!$insert) {
                Log::error('Failed to create Perpipaan Non PDAM record', [
                    'user_id' => $isGuest ? 3 : Auth::id(),
                    'is_guest' => $isGuest,
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi SAM Perpipaan Non PDAM gagal dibuat, silahkan coba lagi.');
            }

            Log::info('Perpipaan Non PDAM record created successfully', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            return redirect(route('perpipaan-non-pdam.show', ['perpipaan_non_pdam' => $insert->id]))->with('success', 'Penilaian/inspeksi SAM Perpipaan Non PDAM berhasil dibuat.');

        } catch (ValidationException $e) {
            $isGuest = !Auth::check();
            Log::warning('Perpipaan Non PDAM form validation failed', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            $isGuest = !Auth::check();
            Log::error('Unexpected error during Perpipaan Non PDAM form submission', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    public function show(PerpipaanNonPdam $perpipaanNonPdam) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $perpipaanNonPdam,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Perpipaan Non PDAM',
            'edit_route' => route('perpipaan-non-pdam.edit', ['perpipaan_non_pdam' => $perpipaanNonPdam['id']]),
            'destroy_route' => route('perpipaan-non-pdam.destroy', ['perpipaan_non_pdam' => $perpipaanNonPdam['id']]),
            'export_route' => route(
                'perpipaan-non-pdam.index',
                [
                    'export' => 'pdf',
                    'id' => $perpipaanNonPdam['id'],
                ]
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerpipaanNonPdam $perpipaanNonPdam) {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return view('pages.inspection.sam.perpipaan-non-pdam.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'kualitas_fa' => $this->kualitasFA(),
            'data_kpr' => $this->dataKPR(),
            'form_data' => $perpipaanNonPdam,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerpipaanNonPdam $perpipaanNonPdam) {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url|max:2048', // Validasi sebagai URL
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // Auto-calculate expire date from issued date + 3 years
        if (!empty($data['slhs_issued_date'])) {
            $issuedDate = \Carbon\Carbon::parse($data['slhs_issued_date']);
            $data['slhs_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
        }

        $data['sk-pengelola'] = $request->input('sk-pengelola') ? $request->input('sk-pengelola') : null;

        foreach ($this->kualitasFAName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

        if ($data['action'] == 'duplicate') {
            $data['u001'] = Auth::id();
            
            // Preserve essential fields from original if empty
            if (empty($data['kelurahan'])) {
                $data['kelurahan'] = $perpipaanNonPdam->kelurahan;
            }
            if (empty($data['kecamatan'])) {
                $data['kecamatan'] = $perpipaanNonPdam->kecamatan;
            }
            if (empty($data['subjek'])) {
                $data['subjek'] = $perpipaanNonPdam->subjek;
            }
            if (empty($data['alamat'])) {
                $data['alamat'] = $perpipaanNonPdam->alamat;
            }
            
            $insert = PerpipaanNonPdam::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Perpipaan Non PDAM gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('perpipaan-non-pdam.show', ['perpipaan_non_pdam' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Perpipaan Non PDAM berhasil dibuat');
        }

        $update = $perpipaanNonPdam->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Perpipaan Non PDAM gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('perpipaan-non-pdam.show', ['perpipaan_non_pdam' => $perpipaanNonPdam['id']]))->with('success', 'form informasi dan penilaian SAM Perpipaan Non PDAM berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $perpipaanNonPdam = PerpipaanNonPdam::where('id', $id)->withTrashed()->first();

        if ($perpipaanNonPdam['deleted_at']) {
            $perpipaanNonPdam->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $perpipaanNonPdam->destroy($perpipaanNonPdam['id']);

        if (!$destroy) {
            return redirect(route('perpipaan-non-pdam.show', ['perpipaan_non_pdam' => $perpipaanNonPdam['id']]))->with('error', 'form informasi dan penilaian SAM Perpipaan Non PDAM gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Perpipaan Non PDAM berhasil dihapus');
    }
}
