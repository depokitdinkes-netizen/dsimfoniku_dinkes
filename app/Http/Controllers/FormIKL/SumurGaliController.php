<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\SumurGali;
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

class SumurGaliController extends Controller {
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
            Form::input('select', 'Jenis Sarana Air Minum', 'u006'),
            Form::input('select', 'Temperatur', 'u007'),
            Form::input('select', 'Prestipasi Saat IKL', 'u008'),
            Form::input('number', 'Tahun Konstruksi', 'u009'),
            Form::input('select', 'Apakah Sarana Terletak Di daerah Banjir', 'u010'),
            Form::input('select', 'Apakah Saat Ini Air Tersedia?', 'u011'),
            Form::input('text', 'Kontak Pengelola', 'kontak'),
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
            Form::select('Apakah sumur gali tidak mempunyai cincin kedap air minimal 3 meter dari permukaan tanah ?', 'int001'),
            Form::select('Apakah sumur gali tidak memiliki bibir sumur ± 80 cm dan tidak retak ?', 'int002'),
            Form::select('Apakah lantai di sekeliling sumur gali tidak kedap air dan lebar kurang dari 1m ?', 'int003'),
            Form::select('Apakah tidak ada saluran pembuangan air yang baik? ', 'int004'),
            Form::select('Apakah tali dan ember pada sumur gali diletakan di lantai sumur, sehingga ada kemungkinan mencemari air sumur? ', 'int005'),
            Form::select('Apakah sumur gali tidak mempunyai penutup sehingga kotoran bisa masuk ke dalam sumur?', 'int006'),
            Form::select('Apakah ada sumber pencemaran (resapan septic tank, kotoran hewan, sampah, limbah) dengan jarak ≤ 15 m? ', 'int007'),
            Form::select('Tidak dilengkapi pagar pelindung', 'int008'),
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
        ];
    }

    public function index(Request $request) {
        switch ($request->export) {
            case 'pdf':
                $item = SumurGali::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Sumur Gali',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Sumur Gali', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::SAM_DELAPAN)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_SUMUR_GALI_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return SumurGali::withTrashed()->get()->map(function ($item) {
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
                            'Jenis Sarana Air Minum',
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
                            'Sumur gali mempunyai cincin kedap air minimal 3 meter dari permukaan tanah',
                            'Sumur gali memiliki bibir sumur ± 80 cm dan tidak retak',
                            'Lantai di sekeliling sumur gali kedap air dan lebar kurang dari 1 m',
                            'Tali dan ember pada sumur gali tidak diletakan di lantai sumur, sehingga menghindari kemungkinan mencemari air sumur',
                            'Sumur gali mempunyai penutup untuk kotoran tidak bisa masuk ke dalam sumur',
                            'Tidak ada sumber pencemaran (resapan septic tank, kotoran hewan, sampah, limbah) dengan jarak ≤ 10 m',
                            'Dilengkapi pagar pelindung',
                            'Sumur gali mempunyai penutup sehingga kotoran tidak bisa masuk ke dalam sumur',
                        ];
                    }
                }, 'REPORT_SUMUR_GALI_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.sumur-gali.create', [
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

        $insert = SumurGali::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Sumur Gali gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('sumur-gali.show', ['sumur_gali' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Sumur Gali berhasil dibuat');
    }

    public function show(SumurGali $sumurGali) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $sumurGali,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Sumur Gali',
            'edit_route' => route('sumur-gali.edit', ['sumur_gali' => $sumurGali['id']]),
            'destroy_route' => route('sumur-gali.destroy', ['sumur_gali' => $sumurGali['id']]),
            'export_route' => route(
                'sumur-gali.index',
                [
                    'export' => 'pdf',
                    'id' => $sumurGali['id'],
                ]
            ),
        ]);
    }

    public function edit(SumurGali $sumurGali) {
        return view('pages.inspection.sam.sumur-gali.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'inspeksi_lk' => $this->inspeksiLK(),
            'intervensi_lk' => $this->intervensiLK(),
            'form_data' => $sumurGali,
        ]);
    }

    public function update(Request $request, SumurGali $sumurGali) {
        
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
            $original = $sumurGali;
            
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
            
            $insert = SumurGali::create($fallbackData);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Sumur Gali gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('sumur-gali.show', ['sumur_gali' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Sumur Gali berhasil dibuat');
        }

        $update = $sumurGali->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Sumur Gali gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('sumur-gali.show', ['sumur_gali' => $sumurGali['id']]))->with('success', 'form informasi dan penilaian SAM Sumur Gali berhasil diubah');
    }

    public function destroy(String $id) {
        $sumurGali = SumurGali::where('id', $id)->withTrashed()->first();

        if ($sumurGali['deleted_at']) {
            $sumurGali->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $sumurGali->destroy($sumurGali['id']);

        if (!$destroy) {
            return redirect(route('sumur-gali.show', ['sumur_gali' => $sumurGali['id']]))->with('error', 'form informasi dan penilaian SAM Sumur Gali gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Sumur Gali berhasil dihapus');
    }
}
