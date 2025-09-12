<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\PerlindunganMataAir;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class PerlindunganMataAirController extends Controller {
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

    protected function kualitasFA() {
        return [
            Form::checkbox('Tidak Keruh', 'kfa001'),
            Form::checkbox('Tidak Berbau', 'kfa002'),
            Form::checkbox('Tidak Berasa', 'kfa003'),
            Form::checkbox('Tidak Berwarna', 'kfa004'),
        ];
    }

    protected function dataKPR() {
        return [
            Form::select('Apakah dinding atau bangunan PMA hilang, rusak atau tidak memadai untuk mencegah kontaminasi memasuki mata air?', 'int001'),
            Form::select('Apakah pipa tempat keluarnya air tidak bersih atau posisinya tidak tepat untuk mencegah kontaminan masuk ke mata air?', 'int002'),
            Form::select('Apakah area isi ulang ke mata air terkikis atau rawan erosi karena ketiadaan vegetasi?', 'int003'),
            Form::select('Apakah saluran air limbah tidak memadai yang dapat menyebabkan genangan air di area mata air?', 'int004'),
            Form::select('Apakah parit pengalihan air hujan yang tidak terserap oleh tanah di atas mata air tidak ada atau tidak memadai untuk mencegah kontaminan memasuki mata air?', 'int005'),
            Form::select('Apakah tidak ada pagar atau pagar sekeliling mata air tidak memadai untuk mencegah hewan memasuki mata air?', 'int006'),
            Form::select('Apakah tidak ada pagar atau pagar pada bagian hulu mata air tidak memadai untuk mencegah kontaminan memasuki mata air?', 'int007'),
            Form::select('Apakah ada sarana sanitasi (jamban/sewer/tanki septik) berjarak sekitar 15 m dari mata air?', 'int008'),
            Form::select('Apakah ada sarana sanitasi di bagian lebih tinggi dalam radius 30 meter dari sumur?', 'int009'),
            Form::select('Apakah ada tanda-tanda sumber kontaminan lain yang terlihat dalam radius 15 meter (seperti binatang, sampah, permukiman, tempat BABS dan penyimpanan bahan bakar?', 'int010'),
            Form::select('Apakah ada titik masuk ke aquifer yang tidak terlindung dalam radius 100 meter seperti sumur terbuka atau sumur bor) ?', 'int011'),

            Form::select('Apakah ada tanda-tanda kontaminasi yang terlihat dalam bangunan mata air (hewan dan atau kotorannya, akumulasi sedimen?', 'int012'),
            Form::select('Jika ada lubang inspeksi, apakah tidak ada tutup atau tutupnya tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?', 'int013'),
            Form::select('Apakah pipa peluap desainnya tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?', 'int014'),
            Form::select('Apakah pipa peluap tidak ditutup secara memadai untuk mencegah masuknya kontaminan ke dalam mata air?', 'int015'),
            Form::select('Jika ada saluran udara apakah didesain atau ditutup secara tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?', 'int016'),
        ];
    }

    protected function kualitasFAName() {
        return [
            'kfa001',
            'kfa002',
            'kfa003',
            'kfa004',
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
        ];
    }

    protected function formPenilaianName() {
        return array_merge($this->dataKPRName());
    }

    public function index(Request $request) {
        switch ($request->export) {
            case 'pdf':
                $item = PerlindunganMataAir::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Perlindungan Mata Air',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Perlindungan Mata Air', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::SAM_PMA)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_PERLINDUNGAN_MATA_AIR_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection() {
                        return PerlindunganMataAir::withTrashed()->get()->map(function ($item) {
                            return collect($item->toArray())->map(function ($value) {
                                return ($value === null || $value === '' || $value === 0) ? '0' : $value;
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

                            'Tidak Keruh',
                            'Tidak Berbau',
                            'Tidak Berasa',
                            'Tidak Berwarna',

                            'Apakah dinding atau bangunan PMA hilang, rusak atau tidak memadai untuk mencegah kontaminasi memasuki mata air?',
                            'Apakah pipa tempat keluarnya air tidak bersih atau posisinya tidak tepat untuk mencegah kontaminan masuk ke mata air?',
                            'Apakah area isi ulang ke mata air terkikis atau rawan erosi karena ketiadaan vegetasi?',
                            'Apakah saluran air limbah tidak memadai yang dapat menyebabkan genangan air di area mata air?',
                            'Apakah parit pengalihan air hujan yang tidak terserap oleh tanah di atas mata air tidak ada atau tidak memadai untuk mencegah kontaminan memasuki mata air?',
                            'Apakah tidak ada pagar atau pagar sekeliling mata air tidak memadai untuk mencegah hewan memasuki mata air?',
                            'Apakah tidak ada pagar atau pagar pada bagian hulu mata air tidak memadai untuk mencegah kontaminan memasuki mata air?',
                            'Apakah ada sarana sanitasi (jamban/sewer/tanki septik) berjarak sekitar 15 m dari mata air?',
                            'Apakah ada sarana sanitasi di bagian lebih tinggi dalam radius 30 meter dari sumur?',
                            'Apakah ada tanda-tanda sumber kontaminan lain yang terlihat dalam radius 15 meter (seperti binatang, sampah, permukiman, tempat BABS dan penyimpanan bahan bakar?',
                            'Apakah ada titik masuk ke aquifer yang tidak terlindung dalam radius 100 meter seperti sumur terbuka atau sumur bor) ?',

                            'Apakah ada bangunan penangkap?',
                            'Apakah ada tanda-tanda kontaminasi yang terlihat dalam bangunan mata air (hewan dan atau kotorannya, akumulasi sedimen?',
                            'Jika ada lubang inspeksi, apakah tidak ada tutup atau tutupnya tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?',
                            'Apakah pipa peluap desainnya tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?',
                            'Apakah pipa peluap tidak ditutup secara memadai untuk mencegah masuknya kontaminan ke dalam mata air?',
                            'Jika ada saluran udara apakah didesain atau ditutup secara tidak memadai untuk mencegah masuknya kontaminan ke dalam mata air?',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_PERLINDUNGAN_MATA_AIR_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.sam.perlindungan-mata-air.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'kualitas_fa' => $this->kualitasFA(),
            'data_kpr' => $this->dataKPR(),
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
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        
        foreach ($this->kualitasFAName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $formPenilaianName = $this->formPenilaianName();

        if ($request->input('ada-bangunan-penangkap') == '0') {
            unset($formPenilaianName[11]);
            unset($formPenilaianName[12]);
            unset($formPenilaianName[13]);
            unset($formPenilaianName[14]);
            unset($formPenilaianName[15]);
        }

        $data['skor'] = (int) (array_reduce($formPenilaianName, fn($carry, $column) => $carry + $data[$column]));

        $insert = PerlindunganMataAir::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Perlindungan Mata Air gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('perlindungan-mata-air.show', ['perlindungan_mata_air' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Perlindungan Mata Air berhasil dibuat');
    }

    public function show(PerlindunganMataAir $perlindunganMataAir) {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $perlindunganMataAir,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'SAM Perlindungan Mata Air',
            'edit_route' => route('perlindungan-mata-air.edit', ['perlindungan_mata_air' => $perlindunganMataAir['id']]),
            'destroy_route' => route('perlindungan-mata-air.destroy', ['perlindungan_mata_air' => $perlindunganMataAir['id']]),
            'export_route' => route(
                'perlindungan-mata-air.index',
                [
                    'export' => 'pdf',
                    'id' => $perlindunganMataAir['id'],
                ]
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerlindunganMataAir $perlindunganMataAir) {
        return view('pages.inspection.sam.perlindungan-mata-air.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'kualitas_fa' => $this->kualitasFA(),
            'data_kpr' => $this->dataKPR(),
            'form_data' => $perlindunganMataAir,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerlindunganMataAir $perlindunganMataAir) {
        
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

        foreach ($this->kualitasFAName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $formPenilaianName = $this->formPenilaianName();

        if ($request->input('ada-bangunan-penangkap') == '0') {
            unset($formPenilaianName[11]);
            unset($formPenilaianName[12]);
            unset($formPenilaianName[13]);
            unset($formPenilaianName[14]);
            unset($formPenilaianName[15]);
        }

        $data['skor'] = (int) (array_reduce($formPenilaianName, fn($carry, $column) => $carry + $data[$column]));

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($perlindunganMataAir->kelurahan)) {
                $data['kelurahan'] = $perlindunganMataAir->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($perlindunganMataAir->kecamatan)) {
                $data['kecamatan'] = $perlindunganMataAir->kecamatan;
            }
            if (empty($data['subjek']) && !empty($perlindunganMataAir->subjek)) {
                $data['subjek'] = $perlindunganMataAir->subjek;
            }
            if (empty($data['alamat']) && !empty($perlindunganMataAir->alamat)) {
                $data['alamat'] = $perlindunganMataAir->alamat;
            }

            $insert = PerlindunganMataAir::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi SAM Perlindungan Mata Air gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('perlindungan-mata-air.show', ['perlindungan_mata_air' => $insert->id]))->with('success', 'penilaian / inspeksi SAM Perlindungan Mata Air berhasil dibuat');
        }

        $update = $perlindunganMataAir->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian SAM Perlindungan Mata Air gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('perlindungan-mata-air.show', ['perlindungan_mata_air' => $perlindunganMataAir['id']]))->with('success', 'form informasi dan penilaian SAM Perlindungan Mata Air berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) {
        $perlindunganMataAir = PerlindunganMataAir::where('id', $id)->withTrashed()->first();

        if ($perlindunganMataAir['deleted_at']) {
            $perlindunganMataAir->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $perlindunganMataAir->destroy($perlindunganMataAir['id']);

        if (!$destroy) {
            return redirect(route('perlindungan-mata-air.show', ['perlindungan_mata_air' => $perlindunganMataAir['id']]))->with('error', 'form informasi dan penilaian SAM Perlindungan Mata Air gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian SAM Perlindungan Mata Air berhasil dihapus');
    }
}
