<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Perpipaan;
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

class PerpipaanController extends Controller {
    protected function select($label, $name, $tidak_sesuai = 1) {
        return [
            'type' => 'select',
            'label' => $label,
            'name' => $name,
            'option' => [
                [
                    'label' => 'Iya',
                    'value' => 0,
                ],
                [
                    'label' => 'Tidak',
                    'value' => $tidak_sesuai,
                ],
            ],
        ];
    }

    protected function informasiUmum() {
        return [
            Form::input('text', 'Nama Sarana', 'subjek'),
            Form::input('select', 'Kategori SAM', 'u001'),
            Form::input('text', 'Nama Instansi / Pemilik Sarana', 'pengelola'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('select', 'Apakah air PDAM digunakan sebagai air minum?', 'u006'),
            Form::input('select', 'Temperatur', 'u007'),
            Form::input('select', 'Prestipasi Saat IKL', 'u008'),
            Form::input('number', 'Tahun Konstruksi', 'u009'),
            Form::input('select', 'Apakah Sarana Terletak Di daerah Banjir', 'u010'),
            Form::input('select', 'Apakah Saat Ini Air Tersedia?', 'u011'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function inspeksiLK() {
        return [
            $this->select('Pengolahan Air/Penerapan Teknologi Tepat Guna Sebelum Didistribusikan Ke Rumah Tangga', 'ins001'),
            $this->select('Metode Pengolahan', 'ins002'),
            Form::input('text', 'Keterangan Metode Lainnya. Contoh: Penggunaan UV, Reverse Osmosis', 'ins003'),
        ];
    }

    protected function intervensiLK() {
        return [
            Form::select('Apakah ada titik-titik kebocoran pada sistem pipa distribusi?', 'int001'),
            Form::select('Apakah reservoir/bak penampung air tidak memenuhi syarat (tidak tertutup, ada kebocoran/retak)? ', 'int002'),
            Form::select('Apakah ada endapan atau lumut pada reservoar/bak penampung?', 'int003'),
            Form::select('Apakah terjadi bencana seperti gempa, banjir/banjir bandang setelah penanaman pipa? ', 'int004'),
            Form::select('Apakah kran di luar bangunan rumah (misal di halaman)?', 'int005'),
            Form::select('Apakah area sekitar tangki atau keran kotor? ', 'int006'),
            Form::select('Apakah ada kebocoran pipa di area rumah?', 'int007'),
            Form::select('Apakah hewan dapat akses ke area sekitar pipa atau keran? ', 'int008'),
            Form::select('Apakah pengguna pernah melaporkan adanya kerusakan pipa dalam seminggu terakhir? ', 'int009'),
            Form::select('Apakah ada gangguan penyediaan air minum dalam 10 hari terakhir? ', 'int010'),
            Form::select('Apakah air untuk rumah tangga tsb berasal lebih dari satu sumber? ', 'int011'),
        ];
    }

    protected function formPenilaianName() {
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
        ];
    }

    public function index(Request $request) {
        switch ($request->export) {
            case 'pdf':
                $item = Perpipaan::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Perpipaan PDAM',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Perpipaan PDAM', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::SAM_SEBELAS)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_PERPIPAAN_PDAM_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return Perpipaan::withTrashed()->get()->map(function ($item) {
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

                            'Kategori SAM',
                            'Apakah air PDAM digunakan sebagai air minum?',
                            'Temperatur',
                            'Prestipasi Saat IKL',
                            'Tahun Konstruksi',
                            'Apakah Sarana Terletak Di daerah Banjir',
                            'Frekuensi banjir, lama, dan tingkat keparahannya',
                            'Apakah Saat Ini Air Tersedia?',
                            'Alasan Air Tidak Tersedia',
                            'Kontak Pengelola',
                            'Pengolahan Air/Penerapan Teknologi Tepat Guna Sebelum Didistribusikan Ke Rumah Tangga',
                            'Metode Pengolahan',
                            'Keterangan Metode Lainnya. Contoh: Penggunaan UV, Reverse Osmosis',
                            'Tidak ada titik-titik kebocoran pada sistem pipa distribusi',
                            'Reservoir/bak penampung air memenuhi syarat (tertutup, tidak ada kebocoran/retak)',
                            'Tidak ada endapan atau lumut pada reservoar/bak penampung (tidak dilakukan pengurasan >3 bulan berarti ada endapan)',
                            'Tidak terjadi bencana seperti gempa, banjir/banjir bandang setelah penanaman pipa',
                            'Keran Di luar Bangunan Rumah (misal di halaman)',
                            'Area Sekitar Keran atau Tangki Bersih',
                            'Tidak ada Kebocoran Pipa di Area Rumah',
                            'Hewan Tidak Dapat Akses ke Area Sekitar Pipa atau Keran',
                            'Pengguna tidak pernah melaporkan adanya kerusakan pipa dalam seminggu terakhir',
                            'Tidak ada gangguan penyediaan air minum dalam 10 hari terakhir',
                            'Air untuk rumah tangga tsb berasal lebih dari satu sumber',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_PERPIPAAN_PDAM_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.perpipaan.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'inspeksi_lk' => $this->inspeksiLK(),
            'intervensi_lk' => $this->intervensiLK(),
        ]);
    }

    public function store(Request $request) {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url',
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        
        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

        $insert = Perpipaan::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Perpipaan gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('perpipaan.show', ['perpipaan' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Perpipaan PDAM berhasil dibuat');
    }

    public function show(Perpipaan $perpipaan) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $perpipaan,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Perpipaan PDAM',
            'edit_route' => route('perpipaan.edit', ['perpipaan' => $perpipaan['id']]),
            'destroy_route' => route('perpipaan.destroy', ['perpipaan' => $perpipaan['id']]),
            'export_route' => route(
                'perpipaan.index',
                [
                    'export' => 'pdf',
                    'id' => $perpipaan['id'],
                ]
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perpipaan $perpipaan) {
        return view('pages.inspection.sam.perpipaan.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'inspeksi_lk' => $this->inspeksiLK(),
            'intervensi_lk' => $this->intervensiLK(),
            'form_data' => $perpipaan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perpipaan $perpipaan) {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url',
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();

        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }

        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

        if ($data['action'] == 'duplicate') {
            // Get original data for fallback
            $original = $perpipaan;
            
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
            
            $insert = Perpipaan::create($fallbackData);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Perpipaan gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('perpipaan.show', ['perpipaan' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Perpipaan PDAM berhasil dibuat');
        }

        $update = $perpipaan->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Perpipaan gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('perpipaan.show', ['perpipaan' => $perpipaan['id']]))->with('success', 'form informasi dan penilaian SAM Perpipaan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) {
        $perpipaan = Perpipaan::where('id', $id)->withTrashed()->first();

        if ($perpipaan['deleted_at']) {
            $perpipaan->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $perpipaan->destroy($perpipaan['id']);

        if (!$destroy) {
            return redirect(route('perpipaan.show', ['perpipaan' => $perpipaan['id']]))->with('error', 'form informasi dan penilaian SAM Perpipaan gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Perpipaan berhasil dihapus');
    }
}
