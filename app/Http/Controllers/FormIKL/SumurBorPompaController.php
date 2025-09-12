<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\SumurBorPompa;
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

class SumurBorPompaController extends Controller {
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
            Form::input('url', 'Upload Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('select', 'Temperatur', 'u006'),
            Form::input('select', 'Prestipasi Saat IKL', 'u007'),
            Form::input('number', 'Tahun Konstruksi', 'u008'),
            Form::input('select', 'Apakah Sarana Terletak Di daerah Banjir', 'u009'),
            Form::input('select', 'Apakah Saat Ini Air Tersedia?', 'u010'),
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
            Form::select('Apakah pompa rusak atau lepas dari dari dudukannya sehingga kontaminan bisa masuk ke dalam sumur? ', 'int001'),
            Form::select('Apakah lantai plesteran/dudukan tidak ada atau tidak utuh sehingga kontaminan bisa masuk ke dalam sumur? ', 'int002'),
            Form::select('Jika ada lubang inspeksi, apakah tutupnya tidak ada atau tidak utuh sehingga kontaminan dapat masuk ke dalam sumur? ', 'int003'),
            Form::select('Apakah ada kekurangan atau kerusakan di dinding sumur yang terlihat?', 'int004'),
            Form::select('Apakah apron/lantai di sekeliling sumur tidak ada atau tidak utuh untuk mencegah kontaminan masuk ke dalam sumur?', 'int005'),
            Form::select('Apakah saluran air limbah tidak memadai sehingga dapat menyebabkan genangan di area sekitar sumur?', 'int006'),
            Form::select('Apakah pagar atau batasan yang melingkari sumur tidak sempurna sehingga binatang dapat memasuki area sumur? ', 'int007'),
            Form::select('Apakah ada sarana sanitasi dalam jarak 15 meter dari sumur? ', 'int008'),
            Form::select('Apakah ada sarana sanitasi di bagian lebih tinggi dalam radius 30 meter dari sumur?', 'int009'),
            Form::select('Apakah ada tanda-tanda sumber pencemar lain yang terlihat dalam radius 15 meter (seperti binatang, sampah, permukiman, tempat BABS dan penyimpanan bahan bakar? ', 'int010'),
            Form::select('Apakah ada titik masuk ke aquifer yang tidak terlindung dalam radius 100 meter seperti sumur terbuka atau sumur bor) ?', 'int011'),
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
                $item = SumurBorPompa::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Sumur Bor dengan Pompa',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Sumur Bor dengan Pompa', $item['subjek']],
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
                ])->download('BAIKL_SUMUR_BOR_POMPA_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return SumurBorPompa::withTrashed()->get()->map(function ($item) {
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
                            'Temperatur',
                            'Prestipasi Saat IKL',
                            'Tahun Konstruksi',
                            'Apakah Sarana Terletak Di daerah Banjir',
                            'Frekuensi banjir, lama, dan tingkat keparahannya',
                            'Apakah Saat Ini Air Tersedia?',
                            'Alasan Air Tidak Tersedia',
                            'Pengolahan Air/Penerapan Teknologi Tepat Guna Sebelum Didistribusikan Ke Rumah Tangga',
                            'Metode Pengolahan',
                            'Keterangan Metode Lainnya. Contoh: Penggunaan UV, Reverse Osmosis',
                            'Pompa tidak rusak dan tidak lepas dari dari dudukannya sehingga kontaminan tidak bisa masuk ke dalam sumur',
                            'Lantai plesteran/dudukan ada atau utuh sehingga kontaminan tidak bisa masuk ke dalam sumur',
                            'Jika ada lubang inspeksi, tutupnya ada dan utuh sehingga kontaminan tidak dapat masuk ke dalam sumur',
                            'Tidak ada kekurangan atau kerusakan di dinding sumur yang terlihat',
                            'Apron/lantai di sekeliling sumur ada dan utuh untuk mencegah kontaminan masuk ke dalam sumur',
                            'Saluran air limbah memadai sehingga dapat mencegah genangan di area sekitar sumur',
                            'Pagar atau batasan yang melingkari sumur sempurna sehingga binatang tidak dapat memasuki area sumur',
                            'Ada sarana sanitasi dalam jarak 15 meter dari sumur',
                            'Ada sarana sanitasi di bagian lebih tinggi dalam radius 30 meter dari sumur',
                            'Tidak ada tanda-tanda sumber pencemar lain yang terlihat dalam radius 15 meter (seperti binatang, sampah, permukiman, tempat BABS dan penyimpanan bahan bakar)',
                            'Tidak ada titik masuk ke aquifer yang tidak terlindung dalam radius 100 meter (seperti sumur terbuka atau sumur bor)',
                        ];
                    }
                }, 'REPORT_SUMUR_BOR_POMPA_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.sumur-bor-pompa.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'inspeksi_lk' => $this->inspeksiLK(),
            'intervensi_lk' => $this->intervensiLK(),
        ]);
    }

    public function store(Request $request) {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url|max:2048', // Validasi sebagai URL
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
        
        // Auto-calculate SLHS expire date if issued date is provided
        if (isset($data['slhs_issued_date']) && $data['slhs_issued_date']) {
            $issuedDate = Carbon::parse($data['slhs_issued_date']);
            $data['slhs_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
        }

        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

        $insert = SumurBorPompa::create($data);        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Sumur Gali dengan Pompa gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('sumur-bor-pompa.show', ['sumur_bor_pompa' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Sumur Gali dengan Pompa berhasil dibuat');
    }

    public function show(SumurBorPompa $sumurBorPompa) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $sumurBorPompa,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Sumur Gali dengan Pompa',
            'edit_route' => route('sumur-bor-pompa.edit', ['sumur_bor_pompa' => $sumurBorPompa['id']]),
            'destroy_route' => route('sumur-bor-pompa.destroy', ['sumur_bor_pompa' => $sumurBorPompa['id']]),
            'export_route' => route(
                'sumur-bor-pompa.index',
                [
                    'export' => 'pdf',
                    'id' => $sumurBorPompa['id'],
                ]
            ),
        ]);
    }

    public function edit(SumurBorPompa $sumurBorPompa) {
        return view('pages.inspection.sam.sumur-bor-pompa.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'inspeksi_lk' => $this->inspeksiLK(),
            'intervensi_lk' => $this->intervensiLK(),
            'form_data' => $sumurBorPompa,
        ]);
    }

    public function update(Request $request, SumurBorPompa $sumurBorPompa) {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url|max:2048', // Validasi sebagai URL
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }
        
        // Auto-calculate SLHS expire date if issued date is provided
        if (isset($data['slhs_issued_date']) && $data['slhs_issued_date']) {
            $issuedDate = Carbon::parse($data['slhs_issued_date']);
            $data['slhs_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
        }

        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column)));

        if ($data['action'] == 'duplicate') {
            // Get original data for fallback
            $original = $sumurBorPompa;
            
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
            
            $insert = SumurBorPompa::create($fallbackData);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Sumur Gali dengan Pompa gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('sumur-bor-pompa.show', ['sumur_bor_pompa' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Sumur Gali dengan Pompa berhasil dibuat');
        }

        $update = $sumurBorPompa->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Sumur Gali dengan Pompa gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('sumur-bor-pompa.show', ['sumur_bor_pompa' => $sumurBorPompa['id']]))->with('success', 'form informasi dan penilaian SAM Sumur Gali dengan Pompa berhasil diubah');
    }

    public function destroy(String $id) {
        $sumurBorPompa = SumurBorPompa::where('id', $id)->withTrashed()->first();

        if ($sumurBorPompa['deleted_at']) {
            $sumurBorPompa->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $sumurBorPompa->destroy($sumurBorPompa['id']);

        if (!$destroy) {
            return redirect(route('sumur-bor-pompa.show', ['sumur_bor_pompa' => $sumurBorPompa['id']]))->with('error', 'form informasi dan penilaian SAM Sumur Gali dengan Pompa gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Sumur Gali dengan Pompa berhasil dihapus');
    }
}
