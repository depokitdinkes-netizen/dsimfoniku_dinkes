<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\PenyimpananAirHujan;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PenyimpananAirHujanController extends Controller {
    protected function informasiUmum() {
        return [
            Form::input('text', 'Nama Sarana', 'subjek'),
            Form::input('text', 'Nama Pemilik Sarana', 'pengelola'),
            Form::input('text', 'Kode Sarana', 'u002'),
            Form::input('text', 'Desa', 'u003'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function dataKPR() {
        return [
            Form::select('Apakah ada kontaminan yang terlihat (misalnya tanaman atau kotoran hewan) di atap atau talang air hujan?', 'int001'),
            Form::select('Apakah atap atau saluran air hujan kemiringannya tidak memadai sehingga menjadi kolam genangan air?', 'int002'),
            Form::select('Apakah ada tumbuhan/tanaman yang menutupi atap/area penangkap air/talang air?', 'int003'),
            Form::select('Apakah kotak saringan tidak ada atau tidak memadai untuk mencegah serpihan masuk ke tangki penampungan?', 'int004'),
            Form::select('Apakah sistem pembilasan tidak ada atau tidak berfungsi dengan baik untuk mencegah kotoran masuk ke tangki penyimpanan?', 'int005'),
            Form::select('Apakah terlihat adanya kontaminan yang terlihat didalam tangki penyimpanan (misal hewan dan/atau kotorannya, akumulasi sedimen?', 'int006'),
            Form::select('Apakah ada titik masuk ke tangki penyimpanan air hujan yang tidak ditutup secara memadai?Apakah ada titik masuk ke tangki penyimpanan air hujan yang tidak ditutup secara memadai?', 'int007'),
            Form::select('Apakah kran tangki air bocor atau rusak?', 'int008'),
            Form::select('Apakah pipa peluap tidak ditutup secara memadai untuk mencegah masuknya kontaminan masuk ke tangki penyimpanan?', 'int009'),
            Form::select('Apakah ada genangan air di area penampungan?', 'int010'),
            Form::select('Apakah pagar atau pelindung tidak ada atau tidak memadai untuk mencegah masuknya kontaminan ke dalam area penampungan?', 'int011'),
            Form::select('Bisakah tanda-tanda pencemaran lain yang dapat dilihat dalam jarak 15 meter dari tangki penyimpanan?', 'int012'),
            Form::select('Apakah ada kegiatan lokal seperti industri atau pertanian yang dapat mengotori area atap?', 'int013'),
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
        ];
    }

    protected function formPenilaianName() {
        return $this->dataKPRName();
    }

    public function index(Request $request) {
        switch ($request->export) {
            case 'pdf':
                $item = PenyimpananAirHujan::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Penyimpanan Air Hujan',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Penyimpanan Air Hujan', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::SAM_TIGA_BELAS)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_PENYIMPANAN_AIR_HUJAN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return PenyimpananAirHujan::withTrashed()->get()->map(function ($item) {
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

                            'Kode Sarana',
                            'Desa',

                            'Apakah ada kontaminan yang terlihat (misalnya tanaman atau kotoran hewan) di atap atau talang air hujan?',
                            'Apakah atap atau saluran air hujan kemiringannya tidak memadai sehingga menjadi kolam genangan air?',
                            'Apakah ada tumbuhan/tanaman yang menutupi atap/area penangkap air/talang air?',
                            'Apakah kotak saringan tidak ada atau tidak memadai untuk mencegah serpihan masuk ke tangki penampungan?',
                            'Apakah sistem pembilasan tidak ada atau tidak berfungsi dengan baik untuk mencegah kotoran masuk ke tangki penyimpanan?',
                            'Apakah terlihat adanya kontaminan yang terlihat didalam tangki penyimpanan (misal hewan dan/atau kotorannya, akumulasi sedimen?',
                            'Apakah ada titik masuk ke tangki penyimpanan air hujan yang tidak ditutup secara memadai?Apakah ada titik masuk ke tangki penyimpanan air hujan yang tidak ditutup secara memadai?',
                            'Apakah kran tangki air bocor atau rusak?',
                            'Apakah pipa peluap tidak ditutup secara memadai untuk mencegah masuknya kontaminan masuk ke tangki penyimpanan?',
                            'Apakah ada genangan air di area penampungan?',
                            'Apakah pagar atau pelindung tidak ada atau tidak memadai untuk mencegah masuknya kontaminan ke dalam area penampungan?',
                            'Bisakah tanda-tanda pencemaran lain yang dapat dilihat dalam jarak 15 meter dari tangki penyimpanan?',
                            'Apakah ada kegiatan lokal seperti industri atau pertanian yang dapat mengotori area atap?',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_PENYIMPANAN_AIR_HUJAN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.penyimpanan-air-hujan.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'data_kpr' => $this->dataKPR(),
        ]);
    }

    public function store(Request $request) {
        try {
            // Check if user is guest
            $isGuest = !Auth::check();
            
            if ($isGuest) {
                Log::info('Guest user attempting to create PenyimpananAirHujan inspection', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now()
                ]);
            }

            // Comprehensive input validation
            $validationRules = [
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'u002' => 'nullable|string|max:100',
                'u003' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'dokumen_slhs' => 'nullable|url',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
            ];

            $validationMessages = [
                'subjek.required' => 'Nama Sarana wajib diisi.',
                'pengelola.required' => 'Nama Pemilik Sarana wajib diisi.',
                'u003.required' => 'Desa wajib diisi.',
                'alamat.required' => 'Alamat wajib diisi.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'nama-pemeriksa.required' => 'Nama Pemeriksa wajib diisi.',
                'instansi-pemeriksa.required' => 'Instansi Pemeriksa wajib diisi.',
                'dokumen_slhs.url' => 'Dokumen SLHS harus berupa URL yang valid.',
                'slhs_issued_date.date' => 'Tanggal Terbit SLHS harus berupa tanggal yang valid.',
                'slhs_expire_date.date' => 'Tanggal Kadaluarsa SLHS harus berupa tanggal yang valid.',
                'slhs_expire_date.after_or_equal' => 'Tanggal Kadaluarsa SLHS harus sama atau setelah Tanggal Terbit SLHS.',
            ];

            $request->validate($validationRules, $validationMessages);

            $data = $request->all();
            
            // Handle custom instansi pemeriksa input
            if (!empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
                Log::info('Custom instansi pemeriksa used', [
                    'custom_instansi' => $request->input('instansi-lainnya'),
                    'user_type' => $isGuest ? 'guest' : 'authenticated'
                ]);
            }
            
            // Set user_id: 3 untuk guest, Auth::id() untuk user yang login
            $data['user_id'] = $isGuest ? 3 : Auth::id();
            
            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $formPenilaianName = $this->formPenilaianName();
            $data['skor'] = (int) (array_reduce($formPenilaianName, fn($carry, $column) => $carry + $data[$column]));

            Log::info('Creating PenyimpananAirHujan inspection', [
                'user_id' => $data['user_id'],
                'user_type' => $isGuest ? 'guest' : 'authenticated',
                'subjek' => $data['subjek'],
                'skor' => $data['skor'],
                'ip' => $request->ip()
            ]);

            $insert = PenyimpananAirHujan::create($data);

            if (!$insert) {
                Log::error('Failed to create PenyimpananAirHujan inspection', [
                    'user_id' => $data['user_id'],
                    'user_type' => $isGuest ? 'guest' : 'authenticated',
                    'data' => $data
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian / inspeksi SAM Penyimpanan Air Hujan gagal dibuat, silahkan coba lagi');
            }

            Log::info('PenyimpananAirHujan inspection created successfully', [
                'inspection_id' => $insert->id,
                'user_id' => $data['user_id'],
                'user_type' => $isGuest ? 'guest' : 'authenticated',
                'subjek' => $data['subjek']
            ]);

            return redirect(route('penyimpanan-air-hujan.show', ['penyimpanan_air_hujan' => $insert->id]))
                ->with('success', 'Penilaian / inspeksi SAM Penyimpanan Air Hujan berhasil dibuat');

        } catch (ValidationException $e) {
            Log::warning('Validation failed for PenyimpananAirHujan inspection', [
                'user_type' => !Auth::check() ? 'guest' : 'authenticated',
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error creating PenyimpananAirHujan inspection', [
                'user_type' => !Auth::check() ? 'guest' : 'authenticated',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);
            return redirect(route('inspection'))->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    public function show(PenyimpananAirHujan $penyimpananAirHujan) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $penyimpananAirHujan,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Penyimpanan Air Hujan',
            'edit_route' => route('penyimpanan-air-hujan.edit', ['penyimpanan_air_hujan' => $penyimpananAirHujan['id']]),
            'destroy_route' => route('penyimpanan-air-hujan.destroy', ['penyimpanan_air_hujan' => $penyimpananAirHujan['id']]),
            'export_route' => route(
                'penyimpanan-air-hujan.index',
                [
                    'export' => 'pdf',
                    'id' => $penyimpananAirHujan['id'],
                ]
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenyimpananAirHujan $penyimpananAirHujan) {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return view('pages.inspection.sam.penyimpanan-air-hujan.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'data_kpr' => $this->dataKPR(),
            'form_data' => $penyimpananAirHujan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenyimpananAirHujan $penyimpananAirHujan) {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
        }
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url',
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();

        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }

        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $formPenilaianName = $this->formPenilaianName();

        $data['skor'] = (int) (array_reduce($formPenilaianName, fn($carry, $column) => $carry + $data[$column]));

        if ($data['action'] == 'duplicate') {
            // Get original data for fallback
            $original = $penyimpananAirHujan;
            
            // Create fallback data with original values for required fields
            $fallbackData = array_merge($data, [
                'kelurahan' => !empty($data['kelurahan']) ? $data['kelurahan'] : ($original ? $original->kelurahan : ''),
                'kecamatan' => !empty($data['kecamatan']) ? $data['kecamatan'] : ($original ? $original->kecamatan : ''),
                'kode_kabkot' => !empty($data['kode_kabkot']) ? $data['kode_kabkot'] : ($original ? $original->kode_kabkot : ''),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Remove id to create new record
            unset($fallbackData['id']);
            
            $insert = PenyimpananAirHujan::create($fallbackData);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Penyimpanan Air Hujan gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('penyimpanan-air-hujan.show', ['penyimpanan_air_hujan' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Penyimpanan Air Hujan berhasil dibuat');
        }

        // Remove user_id and action from update data to preserve original user
        unset($data['user_id']);
        unset($data['action']);
        
        $update = $penyimpananAirHujan->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Penyimpanan Air Hujan gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('penyimpanan-air-hujan.show', ['penyimpanan_air_hujan' => $penyimpananAirHujan['id']]))->with('success', 'form informasi dan penilaian SAM Penyimpanan Air Hujan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $penyimpananAirHujan = PenyimpananAirHujan::where('id', $id)->withTrashed()->first();

        if ($penyimpananAirHujan['deleted_at']) {
            $penyimpananAirHujan->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $penyimpananAirHujan->destroy($penyimpananAirHujan['id']);

        if (!$destroy) {
            return redirect(route('penyimpanan-air-hujan.show', ['penyimpanan_air_hujan' => $penyimpananAirHujan['id']]))->with('error', 'form informasi dan penilaian SAM Penyimpanan Air Hujan gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Penyimpanan Air Hujan berhasil dihapus');
    }
}
