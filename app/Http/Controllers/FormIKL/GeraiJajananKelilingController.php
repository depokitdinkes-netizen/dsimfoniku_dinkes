<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\GeraiJajananKeliling;
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

class GeraiJajananKelilingController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Gerai', 'subjek'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('text', 'Jenis makanan yang dijual', 'u002'),
            Form::input('select', 'Kendaraan yang digunakan', 'u003'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('time', 'Waktu Berjualan', 'u005'),
            Form::input('text', 'Rute Berjualan', 'u006'),
            Form::input('number', 'Nomor Induk Berusaha (Opsional)', 'u007'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('file', 'Upload Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('text', 'Instansi/Wilayah Kerja IKL', 'u010'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Tipe Jajanan Keliling', 'u011'),
        ];
    }

    protected function formPenilaian()
    {
        return [

            Form::h(3, 'A Umum', 'a-umum'),

            Form::select('Jalur penjualan yang dilalui memungkinkan pedagang untuk mengakses air yang aman dan jamban/toilet yang bisa digunakan oleh pedagang', 'au001', 2),
            Form::select('Tersedia air bersih untuk cuci tangan penjual, lap bersih atau tisu basah sekali pakai', 'au002', 3),

            Form::h(3, 'B Personel', 'b-personel'),

            Form::select('Penjual pangan sehat dan bebas dari penyakit menular', 'bp001', 3),
            Form::select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'bp002', 3),
            Form::select('Selalu mencuci tangan dengan sabun dan air mengalir secara berkala sebelum menangani pangan atau menggunakan hand sanitizer secara teratur', 'bp003', 3),
            Form::select('Setelah menyentuh uang, tidak menyentuh pangan secara langsung', 'bp004', 3),
            Form::select('Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'bp005', 3),
            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'bp006', 3),

            Form::h(4, 'Perilaku personel'),

            Form::select('Tidak menggunakan cincin dan perhiasan lain ketika menangani pangan', 'bp007', 2),
            Form::select('Tidak merokok pada saat menangani pangan', 'bp008', 3),
            Form::select('Tidak bersin atau batuk langsung di atas pangan yang terbuka', 'bp009', 3),
            Form::select('Tidak meludah sembarangan pada saat menangani pangan', 'bp010', 2),
            Form::select('Tidak menangani pangan secara langsung setelah menggaruk-garuk anggota badan tanpa mencuci tangan atau menggunakan hand sanitizer terlebih dahulu', 'bp011', 3),

            Form::select('Personel menggunakan pakaian yang bersih pada saat menangani pangan', 'bp012', 2),
            Form::select('Melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun', 'bp013', 2),
            Form::select('Sudah mendapatkan penyuluhan keamanan pangan siap saji', 'bp014', 2),

            Form::h(3, 'C Alat Angkut/Gerobak Pangan', 'c-alat-angkut-gerobak-pangan'),

            Form::select('Alat angkut/gerobak pangan dapat melindungi pangan', 'ca001', 2),
            Form::select('Kondisi baik dan laik jalan', 'ca002'),
            Form::select('Bersih', 'ca003', 2),

            Form::h(4, 'Tempat penyimpanan pangan'),

            Form::select('Disimpan dalam wadah yang tara pangan', 'ca004', 2),
            Form::select('Tempat penyimpanan bersih dan rata', 'ca005', 3),
            Form::select('Wadah penyimpanan tidak rusak', 'ca006', 3),
            Form::select('Pangan matang tidak dicampur penyimpanannya dengan pangan mentah', 'ca007', 3),
            Form::select('Pangan yang tidak dikemas disajikan dalam lemari display yang tertutup', 'ca008', 3),
            Form::select('Tidak ada bebas vektor dan binatang pembawa penyakit', 'ca009', 3),

            Form::h(3, 'D Peralatan masak / makan', 'd-peralatan-masak-makan'),

            Form::select('Alat yang digunakan untuk mengolah pangan baik (tidak kotor, berkarat atau rusak)', 'dm001', 2),
            Form::select('Menggunakan alat makan sekali pakai baik alami (daun) atau buatan yang memiliki logo tara pangan (food grade).', 'dm002', 3),
            Form::select('Untuk pencucian peralatan makan yang bukan sekali pakai, pencucian menggunakan sumber air yang aman, mengalir dan tidak di jamban/toilet (pedagang harus menyediakan ember atau galon yang dimodifikasi yang memungkinan pencucian peralatan menggunakan air yang mengalir)', 'dm003', 3),
            Form::select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'dm004', 3),

            Form::h(3, 'E Pangan Yang Dijual', 'e-pangan-yang-dijual'),

            Form::select('Jika menggunakan es batu, maka es batu dibuat dari air matang/sudah dimasak atau berasal dari sumber yang terpercaya', 'ep001', 3),

            Form::h(4, 'Bahan pangan dalam kemasan (contoh: saos, kecap)'),

            Form::select('Terdaftar', 'ep002', 2),
            Form::select('Berlabel', 'ep003', 2),
            Form::select('Tidak kedaluwarsa', 'ep004', 3),

            Form::h(4, 'Bahan pangan'),

            Form::select('Dibersihkan dan dicuci sebelum dimasak atau dimakan', 'ep005', 2),
            Form::select('Dimasak secara sempurna sebelum disajikan', 'ep006', 2),

            Form::select('Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)', 'ep007', 2),
            Form::select('Tempat bumbu tertutup rapat', 'ep008', 2),
            Form::select('Pangan siap saji berkuah disimpan dalam kondisi panas dengan suhu di atas 60oC (wadah dengan pemanas)', 'ep009', 3),

            Form::select('Pangan matang yang mudah rusak dan disimpan pada suhu ruang sebaiknya dikonsumsi maksimal 4 (empat) jam setelah dimasak, jika masih akan dikonsumsi harus dilakukan pemanasan ulang', 'ep010', 3),
            Form::select('Menyediakan tempat sampah khusus atau sementara', 'ep011'),
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'au001',
            'au002',

            'bp001',
            'bp002',
            'bp003',
            'bp004',
            'bp005',
            'bp006',
            'bp007',
            'bp008',
            'bp009',
            'bp010',
            'bp011',
            'bp012',
            'bp013',
            'bp014',

            'ca001',
            'ca002',
            'ca003',
            'ca004',
            'ca005',
            'ca006',
            'ca007',
            'ca008',
            'ca009',

            'dm001',
            'dm002',
            'dm003',
            'dm004',

            'ep001',
            'ep002',
            'ep003',
            'ep004',
            'ep005',
            'ep006',
            'ep007',
            'ep008',
            'ep009',
            'ep010',
            'ep011',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = GeraiJajananKeliling::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Gerai Pangan Jajanan Keliling',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Gerai Pangan Jajanan Keliling', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Makanan yang Dijual', $item['u002']],
                        ['Kendaraan yang Digunakan', $item['u003']],
                        ['Nomor Izin Usaha', $item['u007'] ?? '-'],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_GERAI_PANGAN_JAJANAN_KELILING_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return GeraiJajananKeliling::withTrashed()->get()->map(function ($item) {
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
    
                                'Jenis makanan yang dijual',
                                'Kendaraan yang digunakan',
                                'Waktu Berjualan Mulai',
                                'Waktu Berjualan Selesai',
                                'Rute Berjualan',
                                'Nomor Induk Berusaha',
                                'Instansi/Wilayah Kerja IKL',
                                'Tipe Jajanan Keliling',
    
                                'Jalur penjualan yang dilalui memungkinkan pedagang untuk mengakses air yang aman dan jamban/toilet yang bisa digunakan oleh pedagang',
                                'Tersedia air bersih untuk cuci tangan penjual, lap bersih atau tisu basah sekali pakai',
                                'Penjual pangan sehat dan bebas dari penyakit menular',
                                'Berkuku pendek, bersih dan tidak memakai pewarna kuku',
                                'Selalu mencuci tangan dengan sabun dan air mengalir secara berkala sebelum menangani pangan atau menggunakan hand sanitizer secara teratur',
                                'Setelah menyentuh uang, tidak menyentuh pangan secara langsung',
                                'Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                                'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                                'Tidak menggunakan cincin dan perhiasan lain ketika menangani pangan',
                                'Tidak merokok pada saat menangani pangan',
                                'Tidak bersin atau batuk langsung di atas pangan yang terbuka',
                                'Tidak meludah sembarangan pada saat menangani pangan',
                                'Tidak menangani pangan secara langsung setelah menggaruk-garuk anggota badan tanpa mencuci tangan atau menggunakan hand sanitizer terlebih dahulu',
                                'Personel menggunakan pakaian yang bersih pada saat menangani pangan',
                                'Melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun',
                                'Sudah mendapatkan penyuluhan keamanan pangan siap saji',
                                'Alat angkut/gerobak pangan dapat melindungi pangan',
                                'Kondisi baik dan laik jalan',
                                'Bersih',
                                'Disimpan dalam wadah yang tara pangan',
                                'Tempat penyimpanan bersih dan rata',
                                'Wadah penyimpanan tidak rusak',
                                'Pangan matang tidak dicampur penyimpanannya dengan pangan mentah',
                                'Pangan yang tidak dikemas disajikan dalam lemari display yang tertutup',
                                'Tidak ada bebas vektor dan binatang pembawa penyakit',
                                'Alat yang digunakan untuk mengolah pangan baik (tidak kotor, berkarat atau rusak)',
                                'Menggunakan alat makan sekali pakai baik alami (daun) atau buatan yang memiliki logo tara pangan (food grade).',
                                'Untuk pencucian peralatan makan yang bukan sekali pakai, pencucian menggunakan sumber air yang aman, mengalir dan tidak di jamban/toilet (pedagang harus menyediakan ember atau galon yang dimodifikasi yang memungkinan pencucian peralatan menggunakan air yang mengalir)',
                                'Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang',
                                'Jika menggunakan es batu, maka es batu dibuat dari air matang/sudah dimasak atau berasal dari sumber yang terpercaya',
                                'Terdaftar',
                                'Berlabel',
                                'Tidak kedaluwarsa',
                                'Dibersihkan dan dicuci sebelum dimasak atau dimakan',
                                'Dimasak secara sempurna sebelum disajikan',
                                'Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)',
                                'Tempat bumbu tertutup rapat',
                                'Pangan siap saji berkuah disimpan dalam kondisi panas dengan suhu di atas 60oC (wadah dengan pemanas)',
                                'Pangan matang yang mudah rusak dan disimpan pada suhu ruang sebaiknya dikonsumsi maksimal 4 (empat) jam setelah dimasak, jika masih akan dikonsumsi harus dilakukan pemanasan ulang',
                                'Menyediakan tempat sampah khusus atau sementara',
                                'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                                'Tanggal Terbit Dokumen SLHS',
                                'Tanggal Berakhir Dokumen SLHS',
                            ];
                        }
                }, 'REPORT_GERAI_PANGAN_JAJANAN_KELILING_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create(Request $request)
    {
        $golongan = strtoupper($request->query('golongan', 'a1'));
        $formPenilaian = $this->formPenilaian();

        switch ($golongan) {
            case 'A1':
                foreach ($this->formPenilaian() as $index => $input) {
                    if ($input['type'] == 'select' && $input['name'] == 'ep007') {
                        unset($formPenilaian[$index]);
                    }
                }
                break;
            case 'A2':
                foreach (['ep006', 'ep009'] as $column) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $column) {
                            unset($formPenilaian[$index]);
                            continue;
                        }
                    }
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada gerai pangan jajanan keliling dengan golongan tersebut');
        }

        return view('pages.inspection.gerai-jajanan-keliling.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'golongan' => $golongan,
        ]);
    }

    public function store(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();

        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }

        if ($data['u011'] != 'A1' && $data['u011'] != 'A2') {
            return redirect(route('gerai-jajanan-keliling.create'))->with('error', 'tidak ada gerai pangan jajanan keliling dengan golongan tersebut');
        }

        $data['user_id'] = Auth::id();
        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A1' => 98, 'A2' => 95][$data['u011']]) * 100);

        $insert = GeraiJajananKeliling::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Gerai Pangan Jajanan Keliling gagal dibuat, silahkan coba lagi');
        }

        $message = $request->input('action') == 'duplicate' ? 'duplikat penilaian / inspeksi Gerai Pangan Jajanan Keliling berhasil dibuat' : 'penilaian / inspeksi Gerai Pangan Jajanan Keliling berhasil dibuat';
        return redirect(route('gerai-jajanan-keliling.show', ['gerai_jajanan_keliling' => $insert->id]))->with('success', $message);
    }

    public function show(GeraiJajananKeliling $geraiJajananKeliling)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $geraiJajananKeliling,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Gerai Pangan Jajanan Keliling Gol ' . $geraiJajananKeliling['u011'],
            'edit_route' => route('gerai-jajanan-keliling.edit', ['gerai_jajanan_keliling' => $geraiJajananKeliling['id']]),
            'destroy_route' => route('gerai-jajanan-keliling.destroy', ['gerai_jajanan_keliling' => $geraiJajananKeliling['id']]),
            'export_route' => route(
                'gerai-jajanan-keliling.index',
                [
                    'export' => 'pdf',
                    'id' => $geraiJajananKeliling['id'],
                ],
            ),
        ]);
    }

    public function edit(GeraiJajananKeliling $geraiJajananKeliling)
    {
        $formPenilaian = $this->formPenilaian();

        switch ($geraiJajananKeliling['u011']) {
            case 'A1':
                foreach ($this->formPenilaian() as $index => $input) {
                    if ($input['type'] == 'select' && $input['name'] == 'ep007') {
                        unset($formPenilaian[$index]);
                    }
                }
                break;
            case 'A2':
                foreach (['ep006', 'ep009'] as $column) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $column) {
                            unset($formPenilaian[$index]);
                            continue;
                        }
                    }
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada gerai pangan jajanan keliling dengan golongan tersebut');
        }

        return view('pages.inspection.gerai-jajanan-keliling.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'form_data' => $geraiJajananKeliling,
        ]);
    }

    public function update(Request $request, GeraiJajananKeliling $geraiJajananKeliling)
    {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();

        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }

        // Handle duplicate action
        if ($request->input('action') == 'duplicate' && $request->input('original_id')) {
            $original = GeraiJajananKeliling::find($request->input('original_id'));
            
            if ($original) {
                // Create fallback data with original values for required fields
                $fallbackData = array_merge($data, [
                    'kelurahan' => !empty($data['kelurahan']) ? $data['kelurahan'] : $original->kelurahan,
                    'kecamatan' => !empty($data['kecamatan']) ? $data['kecamatan'] : $original->kecamatan,
                    'subjek' => !empty($data['subjek']) ? $data['subjek'] : $original->subjek,
                    'alamat' => !empty($data['alamat']) ? $data['alamat'] : $original->alamat,
                    'pengelola' => !empty($data['pengelola']) ? $data['pengelola'] : $original->pengelola,
                    'kontak' => !empty($data['kontak']) ? $data['kontak'] : $original->kontak,
                    'koordinat' => !empty($data['koordinat']) ? $data['koordinat'] : $original->koordinat,
                    'nama-pemeriksa' => !empty($data['nama-pemeriksa']) ? $data['nama-pemeriksa'] : $original->{'nama-pemeriksa'},
                    'instansi-pemeriksa' => !empty($data['instansi-pemeriksa']) ? $data['instansi-pemeriksa'] : $original->{'instansi-pemeriksa'},
                    'status-operasi' => isset($data['status-operasi']) ? $data['status-operasi'] : $original->{'status-operasi'},
                    'u002' => !empty($data['u002']) ? $data['u002'] : $original->u002,
                    'u003' => !empty($data['u003']) ? $data['u003'] : $original->u003,
                    'u005a' => !empty($data['u005a']) ? $data['u005a'] : $original->u005a,
                    'u005b' => !empty($data['u005b']) ? $data['u005b'] : $original->u005b,
                    'u006' => !empty($data['u006']) ? $data['u006'] : $original->u006,
                    'u007' => !empty($data['u007']) ? $data['u007'] : $original->u007,
                    'u010' => !empty($data['u010']) ? $data['u010'] : $original->u010,
                    'u011' => !empty($data['u011']) ? $data['u011'] : $original->u011,
                    'dokumen_slhs' => !empty($data['dokumen_slhs']) ? $data['dokumen_slhs'] : $original->dokumen_slhs,
                    'slhs_issued_date' => !empty($data['slhs_issued_date']) ? $data['slhs_issued_date'] : $original->slhs_issued_date,
                    'slhs_expire_date' => !empty($data['slhs_expire_date']) ? $data['slhs_expire_date'] : $original->slhs_expire_date,
                    'tanggal-penilaian' => !empty($data['tanggal-penilaian']) ? $data['tanggal-penilaian'] : $original->{'tanggal-penilaian'},
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Remove duplicate action fields
                unset($fallbackData['action'], $fallbackData['original_id']);
                $data = $fallbackData;
            }
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A1' => 98, 'A2' => 95][$data['u011']]) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($geraiJajananKeliling->kelurahan)) {
                $data['kelurahan'] = $geraiJajananKeliling->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($geraiJajananKeliling->kecamatan)) {
                $data['kecamatan'] = $geraiJajananKeliling->kecamatan;
            }
            if (empty($data['subjek']) && !empty($geraiJajananKeliling->subjek)) {
                $data['subjek'] = $geraiJajananKeliling->subjek;
            }
            if (empty($data['alamat']) && !empty($geraiJajananKeliling->alamat)) {
                $data['alamat'] = $geraiJajananKeliling->alamat;
            }

            $insert = GeraiJajananKeliling::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Gerai Pangan Jajanan Keliling gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('gerai-jajanan-keliling.show', ['gerai_jajanan_keliling' => $insert->id]))->with('success', 'penilaian / inspeksi Gerai Pangan Jajanan Keliling berhasil dibuat');
        }

        $update = $geraiJajananKeliling->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Gerai Pangan Jajanan Keliling gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('gerai-jajanan-keliling.show', ['gerai_jajanan_keliling' => $geraiJajananKeliling['id']]))->with('success', 'form informasi dan penilaian Gerai Pangan Jajanan Keliling berhasil diubah');
    }

    public function destroy(String $id)
    {
        $geraiJajananKeliling = GeraiJajananKeliling::where('id', $id)->withTrashed()->first();

        if ($geraiJajananKeliling['deleted_at']) {
            $geraiJajananKeliling->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $geraiJajananKeliling->destroy($geraiJajananKeliling['id']);

        if (!$destroy) {
            return redirect(route('gerai-jajanan-keliling.show', ['gerai_jajanan_keliling' => $geraiJajananKeliling['id']]))->with('error', 'form informasi dan penilaian Gerai Pangan Jajanan Keliling gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Gerai Pangan Jajanan Keliling berhasil dihapus');
    }
}