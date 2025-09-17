<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\DepotAirMinum;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class DepotAirMinumController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama DAM', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Jumlah Penjamah Pangan/Operator DAM', 'u004'),
            Form::input('number', 'Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat', 'u005'),
            Form::input('date', 'Tanggal/Bulan/Tahun Mulai Beroperasi', 'u006'),
            Form::input('file', 'Upload Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('text', 'Lokasi/Tempat Sumber Air Baku', 'u007'),
            Form::input('number', 'Luas bangunan (m²)', 'u008'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('select', 'Tujuan IKL', 'tujuan-ikl'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Luar TPP'),

            Form::h(3, 'A Lokasi', 'lokasi'),

            Form::select('Lokasi bebas banjir', 'l001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'l002'),
            Form::select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'l003'),

            Form::h(3, 'B Desain Bangunan Luar', 'desain-bangunan-luar'),

            Form::h(4, 'Bangunan'),

            Form::select('Bahan bangunan kuat', 'db001'),
            Form::select('Mudah dibersihkan', 'db002'),
            Form::select('Mudah dalam pemeliharaan', 'db003'),
            Form::select('Tidak ada lubang/retakan yang terbuka ke area dalam bangunan (tempat sarang atau akses vektor dan binatang pembawa penyakit masuk ke area pengolahan)', 'db004'),
            Form::select('Tidak ada sawang/bebas kotoran', 'db005'),

            Form::h(4, 'Terdapat drainase'),

            Form::select('Bersih', 'db006'),
            Form::select('Tidak ada luapan air / sumbatan', 'db007'),

            Form::select('Tersedia wastafel untuk cuci tangan', 'db008'),

            Form::h(4, 'Wastafel'),

            Form::select('Terdapat petunjuk cuci tangan', 'db009'),
            Form::select('Terdapat sabun cair untuk cuci tangan', 'db010', 2),
            Form::select('Tersedia air mengalir', 'db011', 2),
            Form::select('Tersedia pengering tangan', 'db012'),
            Form::select('Bahan kuat', 'db013'),
            Form::select('Desain mudah dibersihkan', 'db014'),

            Form::h(2, 'Inspeksi Area Dalam atau Proses Depot Air Minum'),

            Form::h(3, 'A Desain Bangunan dan Fasilitasnya', 'desain-bangunan-dan-fasilitasnya'),

            Form::h(4, 'Dinding'),

            Form::select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'df001'),
            Form::select('Tidak retak', 'df002'),
            Form::select('Berwarna terang', 'df003'),

            Form::h(4, 'Lantai'),

            Form::select('Bersih (tidak ada kotoran atau jamur)', 'df004'),
            Form::select('Bahan kuat (tidak retak)', 'df005'),
            Form::select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'df006'),
            Form::select('Kedap air', 'df007'),
            Form::select('Permukaan rata', 'df008'),
            Form::select('Tidak licin', 'df009'),

            Form::h(4, 'Langit-langit'),

            Form::select('Bersih (tidak ada kotoran atau jamur)', 'df010'),
            Form::select('Kuat', 'df011'),
            Form::select('Mudah dibersihkan', 'df012'),
            Form::select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau vektor dan binatang pembawa penyakit)', 'df013'),
            Form::select('Berwarna terang', 'df014'),
            Form::select('Ketinggian cukup (peralatan tidak menyentuh langit-langit)', 'df015'),

            Form::select('Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)', 'df016'),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'df017', 3),
            Form::select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan', 'df018', 3),

            Form::select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)', 'df019', 2),
            Form::select('Ventilasi udara cukup', 'df020'),
            Form::select('Terdapat tempat sampah', 'df021', 2),

            Form::h(4, 'Tempat sampah'),

            Form::select('Tertutup rapat', 'df022', 2),
            Form::select('Tidak ada bau yang menyengat', 'df023', 2),
            Form::select('Tidak ada tumpukan sampah. Frekuensi pembuangan teratur', 'df024', 2),
            Form::select('Pembuangan minimal 1 X 24 jam', 'df025'),

            Form::select('Memiliki akses ke kamar mandi atau jamban', 'df026', 2),

            Form::h(4, 'Jika DAM memiliki toilet di dalam bangunan'),
            Form::h(4, 'Desain'),

            Form::select('Kuat', 'df027'),
            Form::select('Permukaan halus', 'df028'),
            Form::select('Mudah dibersihkan', 'df029'),
            Form::select('Pintu tidak membuka langsung ke ruang pengolahan', 'df030', 3),
            Form::select('Jumlah cukup', 'df031'),

            Form::h(4, 'Tersedia'),

            Form::select('Air mengalir', 'df032', 3),
            Form::select('Sabun cair untuk cuci tangan', 'df033', 3),
            Form::select('Tempat sampah', 'df034'),
            Form::select('Tisu/pengering', 'df035', 2),
            Form::select('Ventilasi yang baik', 'df036', 2),
            Form::select('Petunjuk cuci tangan setelah dari toilet', 'df037', 2),

            Form::h(3, 'B Penjamah Pangan/Operator DAM', 'penjamah-pangan-operator-dam'),

            Form::h(4, 'Personil yang bekerja pada area ini'),

            Form::select('Sehat', 'pp001', 3),
            Form::select('Menggunakan pakaian kerja yang hanya digunakan di tempat kerja', 'pp002', 2),
            Form::select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'pp003', 3),
            Form::select('Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'pp004', 3),

            Form::h(4, 'Pada saat menangani pangan tidak'),

            Form::select('Merokok', 'pp005', 3),
            Form::select('Bersin atau batuk di atas pangan langsung', 'pp006', 3),
            Form::select('Meludah', 'pp007', 3),

            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'pp008', 3),

            Form::select('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun, dibuktikan dengan surat keterangan sehat dari fasilitas pelayanan kesehatan', 'pp009'),

            Form::select('Pengelola/pemilik/penanggung jawab/dan penjamah pangan memiliki sertifikat telah mengikuti pelatihan higiene sanitasi Depot Air Minum', 'pp010', 3),

            Form::h(3, 'C Peralatan', 'peralatan'),

            Form::h(4, 'Peralatan (pipa pengisian air baku, pompa penghisap dan penyedot, keran pengisian air minum, keran pencucian/pembilasan galon, kran penghubung, dan peralatan disinfeksi) yang digunakan'),

            Form::select('Bahan kuat', 'p001', 2),
            Form::select('Tidak berkarat', 'p002', 3),
            Form::select('Tara pangan (food grade)', 'p003', 3),
            Form::select('Bersih sebelum digunakan', 'p004', 3),
            Form::select('Setelah digunakan kondisi bersih dan kering', 'p005', 2),

            Form::h(4, 'Mikrofilter'),

            Form::select('Tara pangan (food grade)', 'p006', 3),
            Form::select('Dalam masa pakai/tidak kedaluwarsa (dibuktikan dengan dokumen/rekaman mikrofilter dari pabrik)', 'p007', 3),
            Form::select('Terdapat lebih dari satu mikro filter dengan ukuran berjenjang', 'p008', 3),
            Form::select('Pembersihan menggunakan sistem pencucian terbalik (back washing)', 'p009', 3),
            Form::select('Jika sistem pembersihan back washing tidak tersedia, maka DAM harus memiliki jadwal pengantian tabung mikrofilter secara rutin (dibuktikan dengan rekaman penggantian mikrofilter)', 'p010', 3),

            Form::select('Terdapat peralatan sterilisasi/disinfeksi air (contoh: Ultra Violet, Ozonisasi atau Reverse Osmosis)', 'p011', 3),

            Form::h(4, 'Peralatan sterilisasi'),

            Form::select('Berfungsi dengan baik', 'p012', 3),
            Form::select('Masa pakai peralatan sterilisasi sesuai dengan standar pabrikan alat tersebut dibuktikan dengan catatan tanggal pemasangan dan data standar masa pakai alat (dapat diperoleh dari kemasan pabrikan peralatan)', 'p013', 3),

            Form::h(4, 'Tandon air baku'),

            Form::select('Tara pangan (food grade)', 'p014', 3),
            Form::select('Tertutup dan terlindungi dari cahaya matahari langsung', 'p015', 2),

            Form::select('Terdapat fasilitas pencucian dan pembilasan galon air', 'p016', 3),

            Form::select('Fasilitas pengisian galon air dalam ruangan tertutup', 'p017', 3),

            Form::h(4, 'Wadah/galon'),

            Form::select('Sebelum dilakukan pengisian dilakukan penyikatan bagian dalam galon sekitar 30 detik', 'p018', 3),
            Form::select('Pembilasan sebelum pengisian dilakukan dengan penyemprotan air produk selama 10 detik', 'p019', 3),
            Form::select('Sesudah terisi maka disimpan dalam kondisi tertutup rapat', 'p020', 3),
            Form::select('Galon yang sudah terisi langsung diberikan kepada konsumen dan tidak boleh disimpan pada DAM lebih dari 1x24 jam', 'p021'),

            Form::h(3, 'D Air Baku', 'air-baku'),

            Form::select('Terdapat bukti tertulis nota pembelian air baku dari perusahaan pengangkutan air/sertifikat sumber air', 'ab001', 3),

            // Form::h(3, 'F Produk Akhir', 'produk-akhir'),

            // Form::h(4, 'Produk akhir air minum yang dihasilkan oleh DAM sesuai dengan persyaratan kualitas air minum sesuai peraturan terkait yang berlaku tentang persyaratan kualitas air minum'),

            // Form::h(4, 'Melakukan pengujian semua parameter (sesuai persyaratan yang berlaku) minimal sekali dalam 6 (enam) bulan secara mandiri di laboratorium terakreditasi atau laboratorium yang ditunjuk oleh pemerintah daerah'),

            // Form::h(4, 'Melakukan pengujian E. coli setiap tiga bulan sekali secara mandiri di laboratorium terakreditasi atau laboratorium yang ditunjuk oleh pemerintah daerah'),

            // Form::h(4, 'Melaporkan hasil analisis air kepada dinas kesehatan minimal dua kali dalam setahun')

        ];
    }

    protected function formPenilaianName()
    {
        return [
            'l001',
            'l002',
            'l003',

            'db001',
            'db002',
            'db003',
            'db004',
            'db005',
            'db006',
            'db007',
            'db008',
            'db009',
            'db010',
            'db011',
            'db012',
            'db013',
            'db014',

            'df001',
            'df002',
            'df003',
            'df004',
            'df005',
            'df006',
            'df007',
            'df008',
            'df009',
            'df010',
            'df011',
            'df012',
            'df013',
            'df014',
            'df015',
            'df016',
            'df017',
            'df018',
            'df019',
            'df020',
            'df021',
            'df022',
            'df023',
            'df024',
            'df025',
            'df026',
            'df027',
            'df028',
            'df029',
            'df030',
            'df031',
            'df032',
            'df033',
            'df034',
            'df035',
            'df036',
            'df037',

            'pp001',
            'pp002',
            'pp003',
            'pp004',
            'pp005',
            'pp006',
            'pp007',
            'pp008',
            'pp009',
            'pp010',

            'p001',
            'p002',
            'p003',
            'p004',
            'p005',
            'p006',
            'p007',
            'p008',
            'p009',
            'p010',
            'p011',
            'p012',
            'p013',
            'p014',
            'p015',
            'p016',
            'p017',
            'p018',
            'p019',
            'p020',
            'p021',

            'ab001',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = DepotAirMinum::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Depot Air Minum',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Depot Air Minum', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Operator DAM (Bersertifikat/Total)', $item['u005'] . '/' . $item['u004']],
                        ['Sumber Air Baku', $item['u007']],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_DEPOT_AIR_MINUM_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return DepotAirMinum::withTrashed()->get()->map(function ($item) {
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
                            'Tujuan IKL',
                            'Tanggal Penilaian',
                            'Skor',
                            'Hasil IKL',
                            'Rencana Tindak Lanjut',
                            'Dibuat',
                            'Diperbarui',
                            'Dihapus',

                            'Operator DAM Total',
                            'Operator DAM Bersertifikat',
                            'Tanggal Mulai Beroperasi',
                            'Sumber Air Baku',
                            'Luas Bangunan (m²)',

                            'Lokasi bebas banjir',
                            'Lokasi bebas dari pencemaran bau/asap/debu/kotoran',
                            'Lokasi bebas dari sumber vektor dan binatang pembawa penyakit',
                            'Bahan bangunan kuat',
                            'Mudah dibersihkan',
                            'Mudah dalam pemeliharaan',
                            'Tidak ada lubang/retakan yang terbuka ke area dalam bangunan (tempat sarang atau akses vektor dan binatang pembawa penyakit masuk ke area pengolahan)',
                            'Tidak ada sawang/bebas kotoran',
                            'Bersih',
                            'Tidak ada luapan air / sumbatan',
                            'Tersedia wastafel untuk cuci tangan',
                            'Terdapat petunjuk cuci tangan',
                            'Terdapat sabun cair untuk cuci tangan',
                            'Tersedia air mengalir',
                            'Tersedia pengering tangan',
                            'Bahan kuat',
                            'Desain mudah dibersihkan',
                            'Bersih (tidak ada kotoran, jamur atau cat mengelupas)',
                            'Tidak retak',
                            'Berwarna terang',
                            'Bersih (tidak ada kotoran atau jamur)',
                            'Bahan kuat (tidak retak)',
                            'Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)',
                            'Kedap air',
                            'Permukaan rata',
                            'Tidak licin',
                            'Bersih (tidak ada kotoran atau jamur)',
                            'Kuat',
                            'Mudah dibersihkan',
                            'Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau vektor dan binatang pembawa penyakit)',
                            'Berwarna terang',
                            'Ketinggian cukup (peralatan tidak menyentuh langit-langit)',
                            'Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan',
                            'Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)',
                            'Ventilasi udara cukup',
                            'Terdapat tempat sampah',
                            'Tertutup rapat',
                            'Tidak ada bau yang menyengat',
                            'Tidak ada tumpukan sampah. Frekuensi pembuangan teratur',
                            'Pembuangan minimal 1 X 24 jam',
                            'Memiliki akses ke kamar mandi atau jamban',
                            'Kuat',
                            'Permukaan halus',
                            'Mudah dibersihkan',
                            'Pintu tidak membuka langsung ke ruang pengolahan',
                            'Jumlah cukup',
                            'Air mengalir',
                            'Sabun cair untuk cuci tangan',
                            'Tempat sampah',
                            'Tisu/pengering',
                            'Ventilasi yang baik',
                            'Petunjuk cuci tangan setelah dari toilet',
                            'Sehat',
                            'Menggunakan pakaian kerja yang hanya digunakan di tempat kerja',
                            'Berkuku pendek, bersih dan tidak memakai pewarna kuku',
                            'Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan',
                            'Merokok',
                            'Bersin atau batuk di atas pangan langsung',
                            'Meludah',
                            'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                            'Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun, dibuktikan dengan surat keterangan sehat dari fasilitas pelayanan kesehatan',
                            'Pengelola/pemilik/penanggung jawab/dan penjamah pangan memiliki sertifikat telah mengikuti pelatihan higiene sanitasi Depot Air Minum',
                            'Bahan kuat',
                            'Tidak berkarat',
                            'Tara pangan (food grade)',
                            'Bersih sebelum digunakan',
                            'Setelah digunakan kondisi bersih dan kering',
                            'Tara pangan (food grade)',
                            'Dalam masa pakai/tidak kedaluwarsa (dibuktikan dengan dokumen/rekaman mikrofilter dari pabrik)',
                            'Terdapat lebih dari satu mikro filter dengan ukuran berjenjang',
                            'Pembersihan menggunakan sistem pencucian terbalik (back washing)',
                            'Jika sistem pembersihan back washing tidak tersedia, maka DAM harus memiliki jadwal pengantian tabung mikrofilter secara rutin (dibuktikan dengan rekaman penggantian mikrofilter)',
                            'Terdapat peralatan sterilisasi/disinfeksi air (contoh: Ultra Violet, Ozonisasi atau Reverse Osmosis)',
                            'Berfungsi dengan baik',
                            'Masa pakai peralatan sterilisasi sesuai dengan standar pabrikan alat tersebut dibuktikan dengan catatan tanggal pemasangan dan data standar masa pakai alat (dapat diperoleh dari kemasan pabrikan peralatan)',
                            'Tara pangan (food grade)',
                            'Tertutup dan terlindungi dari cahaya matahari langsung',
                            'Terdapat fasilitas pencucian dan pembilasan galon air',
                            'Fasilitas pengisian galon air dalam ruangan tertutup',
                            'Sebelum dilakukan pengisian dilakukan penyikatan bagian dalam galon sekitar 30 detik',
                            'Pembilasan sebelum pengisian dilakukan dengan penyemprotan air produk selama 10 detik',
                            'Sesudah terisi maka disimpan dalam kondisi tertutup rapat',
                            'Galon yang sudah terisi langsung diberikan kepada konsumen dan tidak boleh disimpan pada DAM lebih dari 1x24 jam',
                            'Terdapat bukti tertulis nota pembelian air baku dari perusahaan pengangkutan air/sertifikat sumber air',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_DEPOT_AIR_MINUM_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create()
    {
        return view('pages.inspection.dam.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
        ]);
    }

    public function store(Request $request)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to submit Depot Air Minum form', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.');
        }

        try {
            Log::info('Depot Air Minum form submission started', [
                'user_id' => Auth::id(),
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola')
            ]);
            // Validasi input yang lebih comprehensive
            $validator = Validator::make($request->all(), [
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:100',
                'kelurahan' => 'nullable|string|max:100',
                'kontak' => 'required|string|max:20',
                'status-operasi' => 'required|in:0,1',
                'koordinat' => 'required|string|max:100',
                'pengelola' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'catatan-lain' => 'required|string',
                'rencana-tindak-lanjut' => 'required|string',
                'dokumen_slhs' => 'nullable|url',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
            ], [
                'subjek.required' => 'Nama subjek/tempat harus diisi',
                'alamat.required' => 'Alamat harus diisi',
                'kecamatan.required' => 'Kecamatan harus dipilih',
                'kontak.required' => 'Kontak harus diisi',
                'status-operasi.required' => 'Status operasi harus dipilih',
                'koordinat.required' => 'Koordinat harus diisi',
                'pengelola.required' => 'Nama pengelola harus diisi',
                'nama-pemeriksa.required' => 'Nama pemeriksa harus diisi',
                'catatan-lain.required' => 'Hasil IKL harus diisi',
                'rencana-tindak-lanjut.required' => 'Rencana tindak lanjut harus diisi',

                'dokumen_slhs.url' => 'Link dokumen SLHS harus berupa URL yang valid',
                'slhs_issued_date.date' => 'Format tanggal terbit SLHS tidak valid',
                'slhs_expire_date.date' => 'Format tanggal berakhir SLHS tidak valid',
                'slhs_expire_date.after_or_equal' => 'Tanggal berakhir SLHS harus setelah tanggal terbit',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal menyimpan! Ada field yang belum diisi dengan benar. Silakan periksa kembali form Anda.');
            }

            $data = $request->all();
            
            // Handle custom instansi pemeriksa input
            if (!empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 165) * 100);

            $insert = DepotAirMinum::create($data);

            if (!$insert) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal menyimpan penilaian Depot Air Minum. Silakan coba lagi.');
            }

            Log::info('Depot Air Minum record created successfully', [
                'user_id' => Auth::id(),
                'record_id' => $insert->id,
                'subjek' => $data['subjek'] ?? 'N/A'
            ]);

            return redirect(route('depot-air-minum.show', ['depot_air_minum' => $insert->id]))
                ->with('success', 'Penilaian Depot Air Minum berhasil disimpan!');

        } catch (Exception $e) {
            Log::error('Depot Air Minum creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->all()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function show(DepotAirMinum $depotAirMinum)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $depotAirMinum,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Depot Air Minum',
            'edit_route' => route('depot-air-minum.edit', ['depot_air_minum' => $depotAirMinum['id']]),
            'destroy_route' => route('depot-air-minum.destroy', ['depot_air_minum' => $depotAirMinum['id']]),
            'export_route' => route(
                'depot-air-minum.index',
                [
                    'export' => 'pdf',
                    'id' => $depotAirMinum['id'],
                ],
            ),
        ]);
    }

    public function edit(DepotAirMinum $depotAirMinum)
    {
        return view('pages.inspection.dam.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $depotAirMinum,
        ]);
    }

    public function update(Request $request, DepotAirMinum $depotAirMinum)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to update Depot Air Minum form', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.');
        }

        try {
            Log::info('Depot Air Minum update process started', [
                'user_id' => Auth::id(),
                'record_id' => $depotAirMinum->id
            ]);
            // Validasi input yang lebih comprehensive
            $validator = Validator::make($request->all(), [
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:100',
                'kelurahan' => 'nullable|string|max:100',
                'kontak' => 'required|string|max:20',
                'status-operasi' => 'required|in:0,1',
                'koordinat' => 'required|string|max:100',
                'pengelola' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'catatan-lain' => 'required|string',
                'rencana-tindak-lanjut' => 'required|string',
                'dokumen_slhs' => 'nullable|url',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
            ], [
                'subjek.required' => 'Nama subjek/tempat harus diisi',
                'alamat.required' => 'Alamat harus diisi',
                'kecamatan.required' => 'Kecamatan harus dipilih',
                'kontak.required' => 'Kontak harus diisi',
                'status-operasi.required' => 'Status operasi harus dipilih',
                'koordinat.required' => 'Koordinat harus diisi',
                'pengelola.required' => 'Nama pengelola harus diisi',
                'nama-pemeriksa.required' => 'Nama pemeriksa harus diisi',
                'catatan-lain.required' => 'Hasil IKL harus diisi',
                'rencana-tindak-lanjut.required' => 'Rencana tindak lanjut harus diisi',

                'dokumen_slhs.url' => 'Link dokumen SLHS harus berupa URL yang valid',
                'slhs_issued_date.date' => 'Format tanggal terbit SLHS tidak valid',
                'slhs_expire_date.date' => 'Format tanggal berakhir SLHS tidak valid',
                'slhs_expire_date.after_or_equal' => 'Tanggal berakhir SLHS harus setelah tanggal terbit',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal menyimpan! Ada field yang belum diisi dengan benar. Silakan periksa kembali form Anda.');
            }

            $data = $request->all();
            
            // Handle custom instansi pemeriksa input
            if (!empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 165) * 100);

            if (isset($data['action']) && $data['action'] == 'duplicate') {
                // Add auth user ID for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if (empty($data['kelurahan']) && !empty($depotAirMinum->kelurahan)) {
                    $data['kelurahan'] = $depotAirMinum->kelurahan;
                }
                if (empty($data['kecamatan']) && !empty($depotAirMinum->kecamatan)) {
                    $data['kecamatan'] = $depotAirMinum->kecamatan;
                }
                if (empty($data['subjek']) && !empty($depotAirMinum->subjek)) {
                    $data['subjek'] = $depotAirMinum->subjek;
                }
                if (empty($data['alamat']) && !empty($depotAirMinum->alamat)) {
                    $data['alamat'] = $depotAirMinum->alamat;
                }

                $insert = DepotAirMinum::create($data);

                if (!$insert) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Gagal menduplikat penilaian Depot Air Minum. Silakan coba lagi.');
                }

                Log::info('Depot Air Minum record duplicated successfully', [
                    'user_id' => Auth::id(),
                    'original_id' => $depotAirMinum->id,
                    'new_id' => $insert->id,
                    'subjek' => $data['subjek'] ?? 'N/A'
                ]);

                return redirect(route('depot-air-minum.show', ['depot_air_minum' => $insert->id]))
                    ->with('success', 'Penilaian Depot Air Minum berhasil diduplikat!');
            }

            $update = $depotAirMinum->update($data);

            if (!$update) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal mengupdate penilaian Depot Air Minum. Silakan coba lagi.');
            }

            // Clear application cache to ensure fresh data is loaded
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Log::info('Depot Air Minum record updated successfully', [
                'user_id' => Auth::id(),
                'record_id' => $depotAirMinum->id,
                'subjek' => $data['subjek'] ?? 'N/A'
            ]);

            return redirect(route('depot-air-minum.show', ['depot_air_minum' => $depotAirMinum['id']]))
                ->with('success', 'Penilaian Depot Air Minum berhasil diupdate!');

        } catch (Exception $e) {
            Log::error('Depot Air Minum update failed', [
                'user_id' => Auth::id(),
                'record_id' => $depotAirMinum->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengupdate data. Silakan coba lagi.');
        }
    }

    public function destroy(String $id)
    {
        $depotAirMinum = DepotAirMinum::where('id', $id)->withTrashed()->first();

        if ($depotAirMinum['deleted_at']) {
            $depotAirMinum->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $depotAirMinum->destroy($depotAirMinum['id']);

        if (!$destroy) {
            return redirect(route('depot-air-minum.show', ['depot_air_minum' => $depotAirMinum['id']]))->with('error', 'form informasi dan penilaian DAM gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian DAM berhasil dihapus');
    }
}

