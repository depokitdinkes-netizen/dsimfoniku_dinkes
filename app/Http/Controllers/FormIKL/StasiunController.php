<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Stasiun;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class StasiunController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Stasiun', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Pengelola/Kepala Stasiun', 'pengelola'),
            Form::input('text', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Status Operasi', 'status-operasi'),
            Form::input('text', 'Koordinat Lokasi', 'koordinat'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian')
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'I. Air'),
            Form::checkbox('Tersedia air minum yang cukup untuk semua keperluan', 'a001', 1),
            Form::checkbox('Tersedia air siap minum untuk umum selain kran di tempat kerja', 'a002', 1),
            Form::checkbox('Memiliki akses terhadap sarana cuci tangan pakai sabun dan air mengalir yang berfungsi di area umum/lobby', 'a003', 1),

            Form::h(2, 'II. Udara'),
            Form::checkbox('Tidak ada sumber pencemar udara yang ada di sekitar stasiun/terminal', 'ud001', 1),
            Form::input('text', 'Jika ada, sebutkan sumber pencemar apa saja yang ada di sekitar stasiun/terminal (bisa lebih dari satu): Transportasi, Industri, Pertanian, Komersial, Lainnya', 'ud002'),
            Form::checkbox('Jarak stasiun/terminal dari sumber pencemar udara lebih dari 150 meter', 'ud003', 1),
            Form::checkbox('Tidak terdapat aktivitas membakar sampah di stasiun/terminal', 'ud004', 1),
            Form::checkbox('Semua atau sebagian ruangan ada ventilasi yang terbuka secara permanen dengan ukuran lebih besar atau sama dengan 10 persen dari luas lantai', 'ud005', 1),
            Form::checkbox('Terdapat alat bantu sirkulasi udara yang berfungsi baik dan bersih', 'ud006', 1),
            Form::input('text', 'Jika ada, sebutkan (bisa memilih lebih dari satu dan sebutkan jumlahnya): Kipas angin, AC, Exhaust fan, Air purifier, Lain-lain', 'ud007'),
            Form::checkbox('Tersedia ruang terbuka hijau / pohon rindang / banyak tanaman', 'ud008', 1),
            Form::checkbox('Menerapkan Kawasan Tanpa Rokok dan tidak ditemukan ada yang merokok/vaping/menggunakan rokok elektrik di lingkungan stasiun/terminal', 'ud009', 1),
            Form::checkbox('Tidak terdapat bahan kimia yang menjadi sumber pencemar udara dalam ruangan', 'ud010', 1),
            Form::input('text', 'Jika ada, sebutkan bahan kimia yang digunakan: Cairan pembersih lantai, Spray, Freon (kulkas, AC), Minyak wangi (parfum), Desinfektan, Insektisida, Lainnya', 'ud011'),
            Form::checkbox('Terdapat aktivitas membersihkan area lingkungan terminal/stasiun', 'ud012', 1),
            Form::input('text', 'Jika ada, sebutkan frekuensi kegiatan: Kadang-kadang (kurang dari 3 minggu sekali atau 1 bulan sekali), Seminggu sekali / sering (3–6 kali seminggu), Setiap hari', 'ud013'),

            Form::h(2, 'III. Pangan'),
            Form::checkbox('Seluruh penyedia pangan/gerai di kantin yang menyediakan pangan memiliki sertifikat/label pembinaan Higiene Sanitasi Pangan', 'p001', 1),
            Form::checkbox('Seluruh penjamah sudah mendapatkan sertifikat pelatihan/penyuluhan Higiene Sanitasi Pangan (HSP)', 'p002', 1),

            Form::h(2, 'IV. Sarana dan Fasilitas'),
            Form::checkbox('Tersedia Tempat Penampungan Sementara (TPS) sampah yang tidak mencemari lingkungan', 'sb001', 1),
            Form::checkbox('Semua sampah setiap hari diangkut', 'sb002', 1),
            Form::checkbox('Tersedia pencahayaan alami/buatan yang diterapkan pada ruangan baik di dalam bangunan maupun di luar bangunan gedung untuk bisa melakukan seluruh aktivitas', 'sb003', 1),
            Form::checkbox('Seluruh sarana dan prasarana bangunan bersih dan mudah dibersihkan', 'sb004', 1),
            Form::checkbox('Tersedia area titik kumpul untuk evakuasi', 'sb005', 1),
            Form::checkbox('Adanya fasilitas ruang ibadah yang memenuhi syarat kesehatan', 'sb006', 1),
            Form::checkbox('Memiliki akses terhadap toilet dan peturasan tersedia dengan jumlah yang cukup dan berfungsi', 'sb007', 1),
            Form::checkbox('Semua toilet dan peturasan bersih dan memenuhi syarat', 'sb008', 1),
            Form::checkbox('Toilet laki-laki terpisah dengan perempuan', 'sb009', 1),
            Form::checkbox('Tersedia toilet bagi penyandang disabilitas', 'sb010', 1),
            Form::checkbox('Memiliki ruang laktasi berfungsi dengan baik', 'sb011', 1),

            Form::h(2, 'V. Vektor'),
            Form::checkbox('Lingkungan stasiun/terminal harus bebas jentik nyamuk', 'vb001', 1),
            Form::checkbox('Lingkungan stasiun/terminal harus bebas vektor (nyamuk, lalat, kecoa) dan binatang pembawa penyakit (tikus)', 'vb002', 1),

            Form::h(2, 'VI. Pengelolaan Limbah dan Sampah'),
            Form::checkbox('Tersedianya instalasi pengolahan air limbah yang memenuhi syarat', 'pl001', 1),
            Form::checkbox('Sistem saluran limbah cair yang tertutup', 'pl002', 1),
            Form::checkbox('Tidak terdapat genangan air limbah', 'pl003', 1),
            Form::checkbox('Tidak terdapat sampah berserakan di tempat umum', 'pl004', 1)
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'a001', 'a002', 'a003',
            'ud001', 'ud003', 'ud004', 'ud005', 'ud006', 'ud008', 'ud009', 'ud010', 'ud012',
            'p001', 'p002',
            'sb001', 'sb002', 'sb003', 'sb004', 'sb005', 'sb006', 'sb007', 'sb008', 'sb009', 'sb010', 'sb011',
            'vb001', 'vb002',
            'pl001', 'pl002', 'pl003', 'pl004'
        ];
    }

    protected function hasilPengukuran()
    {
        return [
            Form::input('text', 'Catatan Tambahan Hasil Penilaian', 'hpp001'),
            Form::input('text', 'Rekomendasi Perbaikan', 'hpp002')
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Stasiun::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Stasiun',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Stasiun', $item['subjek']],
                        ['Jenis Stasiun', $item['jenis_stasiun']],
                        ['Nama Pengelola/Kepala Stasiun', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)]
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']
                ])->download('BAIKL_STASIUN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return Stasiun::withTrashed()->get()->map(function ($item) {
                            return collect($item->toArray())->map(function ($value) {
                                if ($value === null || $value === '') {
                                    return '';
                                }
                                return $value === 0 ? '0' : $value;
                            })->toArray();
                        });
                    }

                    public function headings(): array
                    {
                        return [
                            'Id', 'User ID', 'Nama Subjek', 'Nama Pengelola', 'Alamat', 'Kelurahan', 'Kecamatan', 'Kontak',
                            'Status Operasi', 'Koordinat', 'Nama Pemeriksa', 'Instansi Pemeriksa', 'Tanggal Penilaian',
                            'Skor', 'Hasil IKL', 'Rencana Tindak Lanjut', 'Dibuat', 'Diperbarui', 'Dihapus',
                            
                            // I. Air
                            'Tersedia air minum yang cukup untuk semua keperluan',
                            'Tersedia air siap minum untuk umum selain kran di tempat kerja',
                            'Memiliki akses terhadap sarana cuci tangan pakai sabun dan air mengalir yang berfungsi di area umum/lobby',
                            
                            // II. Udara
                            'Tidak ada sumber pencemar udara yang ada di sekitar stasiun/terminal',
                            'Jarak stasiun/terminal dari sumber pencemar udara lebih dari 150 meter',
                            'Tidak terdapat aktivitas membakar sampah di stasiun/terminal',
                            'Semua atau sebagian ruangan ada ventilasi yang terbuka secara permanen dengan ukuran lebih besar atau sama dengan 10 persen dari luas lantai',
                            'Terdapat alat bantu sirkulasi udara yang berfungsi baik dan bersih',
                            'Tersedia ruang terbuka hijau / pohon rindang / banyak tanaman',
                            'Menerapkan Kawasan Tanpa Rokok dan tidak ditemukan ada yang merokok/vaping/menggunakan rokok elektrik di lingkungan stasiun/terminal',
                            'Tidak terdapat bahan kimia yang menjadi sumber pencemar udara dalam ruangan',
                            'Terdapat aktivitas membersihkan area lingkungan terminal/stasiun',
                            
                            // III. Pangan
                            'Seluruh penyedia pangan/gerai di kantin yang menyediakan pangan memiliki sertifikat/label pembinaan Higiene Sanitasi Pangan',
                            'Seluruh penjamah sudah mendapatkan sertifikat pelatihan/penyuluhan Higiene Sanitasi Pangan (HSP)',
                            
                            // IV. Sarana dan Fasilitas
                            'Tersedia Tempat Penampungan Sementara (TPS) sampah yang tidak mencemari lingkungan',
                            'Semua sampah setiap hari diangkut',
                            'Tersedia pencahayaan alami/buatan yang diterapkan pada ruangan baik di dalam bangunan maupun di luar bangunan gedung untuk bisa melakukan seluruh aktivitas',
                            'Seluruh sarana dan prasarana bangunan bersih dan mudah dibersihkan',
                            'Tersedia area titik kumpul untuk evakuasi',
                            'Adanya fasilitas ruang ibadah yang memenuhi syarat kesehatan',
                            'Memiliki akses terhadap toilet dan peturasan tersedia dengan jumlah yang cukup dan berfungsi',
                            'Semua toilet dan peturasan bersih dan memenuhi syarat',
                            'Toilet laki-laki terpisah dengan perempuan',
                            'Tersedia toilet bagi penyandang disabilitas',
                            'Memiliki ruang laktasi berfungsi dengan baik',
                            
                            // V. Vektor
                            'Lingkungan stasiun/terminal harus bebas jentik nyamuk',
                            'Lingkungan stasiun/terminal harus bebas vektor (nyamuk, lalat, kecoa) dan binatang pembawa penyakit (tikus)',
                            
                            // VI. Pengelolaan Limbah dan Sampah
                            'Tersedianya instalasi pengolahan air limbah yang memenuhi syarat',
                            'Sistem saluran limbah cair yang tertutup',
                            'Tidak terdapat genangan air limbah',
                            'Tidak terdapat sampah berserakan di tempat umum',
                            'Catatan Tambahan Hasil Penilaian',
                            'Rekomendasi Perbaikan',
                        ];
                    }
                }, 'REPORT_STASIUN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspection.stasiun.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'hasil_pengukuran' => $this->hasilPengukuran()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('StasiunController store called', [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token'])
            ]);

            // Validasi input
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'kontak' => 'required|numeric',
                'status-operasi' => 'required|boolean',
            ]);

            Log::info('Validation passed for store');

            $data = $request->all();
            // Add auth user ID
            $data['user_id'] = Auth::id();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            // Handle text fields separately (not part of scoring)
            $textFields = ['ud002', 'ud007', 'ud011', 'ud013'];
            foreach ($textFields as $field) {
                $data[$field] = $request->input($field, '');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / count($this->formPenilaianName()) * 100);

            Log::info('Attempting to create Stasiun record', ['skor' => $data['skor']]);

            $insert = Stasiun::create($data);

            if (!$insert) {
                Log::error('Failed to create Stasiun record - insert returned false');
                return redirect(route('inspection'))->with('error', 'Penilaian Stasiun gagal dibuat. Silahkan periksa data dan coba lagi.');
            }

            Log::info('Stasiun record created successfully', ['id' => $insert->id]);
            return redirect(route('stasiun.show', ['stasiun' => $insert->id]))->with('success', 'penilaian / inspeksi Stasiun berhasil dibuat');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in store', [
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in store', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Stasiun $stasiun)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $stasiun,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Stasiun',
            'edit_route' => route('stasiun.edit', ['stasiun' => $stasiun['id']]),
            'destroy_route' => route('stasiun.destroy', ['stasiun' => $stasiun['id']]),
            'export_route' => route('stasiun.index', ['export' => 'pdf', 'id' => $stasiun['id']])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stasiun $stasiun)
    {
        return view('pages.inspection.stasiun.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'hasil_pengukuran' => $this->hasilPengukuran(),
            'form_data' => $stasiun
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stasiun $stasiun)
    {
        try {
            Log::info('StasiunController update called', [
                'stasiun_id' => $stasiun->id,
                'action' => $request->input('action'),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['_token', '_method'])
            ]);
            
            // Validasi input
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'kontak' => 'required|numeric',
                'status-operasi' => 'required|boolean',
            ]);
            
            Log::info('Validation passed for update');

            $data = $request->all();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            // Handle text fields separately (not part of scoring)
            $textFields = ['ud002', 'ud007', 'ud011', 'ud013'];
            foreach ($textFields as $field) {
                $data[$field] = $request->input($field, '');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / count($this->formPenilaianName()) * 100);

            if ($data['action'] == 'duplicate') {
                Log::info('Processing duplicate action');
                // Add auth user ID only for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if (empty($data['kelurahan']) && !empty($stasiun->kelurahan)) {
                    $data['kelurahan'] = $stasiun->kelurahan;
                }
                if (empty($data['kecamatan']) && !empty($stasiun->kecamatan)) {
                    $data['kecamatan'] = $stasiun->kecamatan;
                }
                if (empty($data['subjek']) && !empty($stasiun->subjek)) {
                    $data['subjek'] = $stasiun->subjek;
                }
                if (empty($data['alamat']) && !empty($stasiun->alamat)) {
                    $data['alamat'] = $stasiun->alamat;
                }

                $insert = Stasiun::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Stasiun record', ['original_id' => $stasiun->id]);
                    return redirect(route('inspection'))->with('error', 'Duplikasi penilaian Stasiun gagal. Silahkan coba lagi.');
                }

                Log::info('Stasiun duplicated successfully', ['original_id' => $stasiun->id, 'new_id' => $insert->id]);
                return redirect(route('stasiun.show', ['stasiun' => $insert->id]))->with('success', 'penilaian / inspeksi Stasiun berhasil diduplikasi');
            }

            // Remove user_id from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            Log::info('Attempting to update Stasiun record', [
                'stasiun_id' => $stasiun->id,
                'skor' => $data['skor']
            ]);
            
            $update = $stasiun->update($data);

            if (!$update) {
                Log::error('Update failed for stasiun - update returned false', ['id' => $stasiun->id]);
                return redirect()->back()->withInput()->with('error', 'Penilaian Stasiun gagal diubah. Silahkan periksa data dan coba lagi.');
            }

            Log::info('Stasiun updated successfully', ['id' => $stasiun->id]);
            return redirect(route('stasiun.show', ['stasiun' => $stasiun->id]))->with('success', 'form informasi dan penilaian Stasiun berhasil diubah');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in update', [
                'errors' => $e->errors(),
                'stasiun_id' => $stasiun->id,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error in update', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'stasiun_id' => $stasiun->id,
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $stasiun = Stasiun::where('id', $id)->withTrashed()->first();

        if ($stasiun['deleted_at']) {
            $stasiun->update(['deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $stasiun->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Stasiun berhasil dihapus');
    }
}
