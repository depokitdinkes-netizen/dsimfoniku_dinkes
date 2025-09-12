<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Sekolah;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class SekolahController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Sekolah', 'subjek'),
            Form::input('select', 'Jenis Sekolah', 'jenis_sekolah'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Kepala Sekolah/NIP', 'pengelola'),
            Form::input('number', 'Kontak yang Dapat Dihubungi', 'kontak'),
            Form::input('number', 'Jumlah Siswa', 'u004'),
            Form::input('number', 'Jumlah Guru', 'u005'),
            Form::input('number', 'Nomor Pokok Sekolah Nasional', 'u006'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('select', 'Status Operasi', 'status-operasi'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian')];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Air'),
            Form::checkbox('Tersedia air bersih (Kadar tertinggi yang diperkenankan 15  liter/org/hr)', 'a001'),

            Form::h(3, 'Kualitas Fisik', 'kualitas-fisik-air'),
            Form::checkbox('Warna (Kadar tertinggi yang diperkenankan 50 TCU)', 'a002'),
            Form::checkbox('Kekeruhan (Kadar tertinggi yang diperkenankan 25 NTU)', 'a003'),
            Form::checkbox('Tidak Bau', 'a004'),

            Form::h(3, 'Kualitas Biologi', 'kualitas-biologi-air'),

            Form::checkbox('E-Coli (Kadar tertinggi yang diperkenankan 0 jml/100 ml sampel)', 'a005'),

            Form::h(3, 'Kualitas Kimia', 'kualitas-kimia-air'),

            Form::checkbox('pH (Kadar tertinggi yang diperkenankan 6,5 - 8,5 mg/l)', 'a006'),
            Form::checkbox('Fe (Kadar tertinggi yang diperkenankan 1 mg/l)', 'a007'),
            Form::checkbox('Mn (Kadar tertinggi yang diperkenankan 0,5 mg/l)', 'a008'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-air'),

            Form::checkbox('Air bersih tersedia sepanjang waktu', 'a009'),

            Form::h(2, 'Udara'),
            Form::h(3, 'Kualitas Fisik', 'kualitas-fisik-udara'),

            Form::checkbox('Pencahayaan ruang kelas (Kadar tertinggi yang diperkenankan 200-300 lux)', 'ud001'),
            Form::checkbox('Pencahayaan ruang perpustakaan (Kadar tertinggi yang diperkenankan 200-300 lux)', 'ud002'),
            Form::checkbox('Pencahayaan ruang laboratorium (Kadar tertinggi yang diperkenankan 200-300 lux)', 'ud003'),
            Form::checkbox('Kelembaban (Kadar tertinggi yang diperkenankan 40-60%)', 'ud004'),
            Form::checkbox('Laju ventilasi udara (Kadar tertinggi yang diperkenankan 0,15 - 0,25 m³/menit/org)', 'ud005'),
            Form::checkbox('Kebisingan (Kadar tertinggi yang diperkenankan 45 dB(A))', 'ud006'),
            Form::checkbox('PM2,5 (Kadar tertinggi yang diperkenankan 35 µg/m³ dalam 24 jam)', 'ud007'),

            Form::h(3, 'Kualitas Biologi', 'kualitas-biologi-udara'),

            Form::checkbox('Angka Kuman (Kadar tertinggi yang diperkenankan <700 CFU/m³)', 'ud008'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-udara'),

            Form::checkbox('Sekolah bebas dari asap rokok', 'ud009'),
            Form::checkbox('Lingkungan sekolah tidak banyak debu', 'ud010'),
            Form::checkbox('Pencahayaan ruang kelas dapat untuk membaca buku dengan jelas tanpa bantuan penerangan pada siang hari (bisa membaca dgn jelas dengan jarak 30 cm)', 'ud011'),
            Form::checkbox('Udara dalam ruang sekolah tidak pengap/terasa segar/terasa nyaman', 'ud012'),
            Form::checkbox('Udara dalam ruang sekolah tidak berbau', 'ud013'),

            Form::h(2, 'Pangan'),

            Form::checkbox('Apakah memiliki Kantin Sekolah', 'p001'),
            Form::checkbox('Apakah Sudah pernah melakukan IKL pada kantin sekolah', 'p002'),

            Form::h(2, 'Sarana Dan Bangunan'),

            Form::checkbox('Jarak papan tulis dengan murid terdepan (Kadar tertinggi yang diperkenankan >2,5 meter)', 'sb001'),
            Form::checkbox('Jarak papan tulis dengan murid paling belakang (Kadar tertinggi yang diperkenankan < 9 meter)', 'sb002'),
            Form::checkbox('Kepadatan Kelas (Kadar tertinggi yang diperkenankan minimal 1,75 m²/murid)', 'sb003'),
            Form::checkbox('Lebar anak tangga (Kadar tertinggi yang diperkenankan minimal 30 cm)', 'sb004'),
            Form::checkbox('Lantai (Kadar tertinggi yang diperkenankan maksimal 20 cm)', 'sb005'),
            Form::checkbox('Lebar tangga/luas tangga (Kadar tertinggi yang diperkenankan  ≥ 150 cm)', 'sb006'),
            Form::checkbox('Jumlah sarana WC/urinoir (Kadar tertinggi yang diperkenankan) = Minimal 1 : 40 untuk laki-laki dan Minimal 1 : 25 untuk perempuan', 'sb007'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-sarana-dan-bangunan'),

            Form::checkbox('Tidak ada genangan (halaman sekolah, atap, talang)', 'sb008'),
            Form::checkbox('Dinding kuat, tidak retak, tidak pecah', 'sb009'),
            Form::checkbox('Dinding tidak berjamur', 'sb010'),
            Form::checkbox('Dinding tidak dicat dengan kapur', 'sb011'),
            Form::checkbox('Lantai kuat, kedap air, permukaan rata, tidak retak, tidak licin, mudah dibersihkan', 'sb012'),
            Form::checkbox('Tangga harus dilengkapi dengan pegangan tangan', 'sb013'),
            Form::checkbox('Tersedia tempat cuci tangan dengan air mengalir dan sabun di setiap kelas', 'sb014'),
            Form::checkbox('Kamar mandi bersih dan tidak berbau', 'sb015'),
            Form::checkbox('Ventilasi dan penerangan kamar mandi cukup', 'sb016'),
            Form::checkbox('Lantai kamar mandi kedap air, tidak licin, tidak ada genangan air', 'sb017'),
            Form::checkbox('Kamar mandi/WC tersedia air bersih dan sabun', 'sb018'),
            Form::checkbox('Tersedia tempat sampah organik dan anorganik di setiap ruangan', 'sb019'),
            Form::checkbox('Tidak ada sampah yang berserakan', 'sb020'),
            Form::checkbox('Tersedia Tempat Pembuangan Sementara (TPS) sampah', 'sb021'),
            Form::checkbox('Tidak ada sampah membusuk/ berbau di TPS', 'sb022'),
            Form::checkbox('Air limbah mengalir dengan lancar', 'sb023'),
            Form::checkbox('Tersedia penampungan air limbah yang tertutup', 'sb024'),
            Form::checkbox('Saluran pembuangan air limbah kedap air dan tertutup', 'sb025'),
            Form::checkbox('Tersedia septic tank dalam kondisi baik (tidak pecah, tidak bocor)', 'sb026'),
            Form::checkbox('Tersedia area titik kumpul untuk evakuasi', 'sb027'),
            Form::checkbox('Tidak tersedia penampungan air permanen di kamar mandi/WC', 'sb028'),

            Form::h(2, 'Vektor Dan Binatang Pembawa Penyakit'),

            Form::checkbox('Angka Bebas Jentik (ABJ) (Kadar tertinggi yang diperkenankan 100%)', 'vb001'),
            Form::checkbox('Angka rata-rata populasi lalat (Kadar tertinggi yang diperkenankan <2 ekor)', 'vb002'),
            Form::checkbox('Angka rata-rata populasi kecoa (Kadar tertinggi yang diperkenankan <2 ekor)', 'vb003'),
            Form::checkbox('Strata UKS pada sekolah', 'vb004'),
            Form::checkbox('Apakah dilakukan pengukuran kualitas lingkungan menggunakan Sanitarian Kit?', 'vb005'),
            Form::checkbox('Sanitarian Kit apakah berfungsi dengan baik', 'vb006')];
    }

    protected function formPenilaianName()
    {
        return [
            'a001',
            'a002',
            'a003',
            'a004',
            'a005',
            'a006',
            'a007',
            'a008',
            'a009',

            'ud001',
            'ud002',
            'ud003',
            'ud004',
            'ud005',
            'ud006',
            'ud007',
            'ud008',
            'ud009',
            'ud010',
            'ud011',
            'ud012',
            'ud013',

            'p001',
            'p002',

            'sb001',
            'sb002',
            'sb003',
            'sb004',
            'sb005',
            'sb006',
            'sb007',
            'sb008',
            'sb009',
            'sb010',
            'sb011',
            'sb012',
            'sb013',
            'sb014',
            'sb015',
            'sb016',
            'sb017',
            'sb018',
            'sb019',
            'sb020',
            'sb021',
            'sb022',
            'sb023',
            'sb024',
            'sb025',
            'sb026',
            'sb027',
            'sb028',

            'vb001',
            'vb002',
            'vb003',
            'vb004',
            'vb005',
            'vb006'];
    }

    protected function hasilPengukuran()
    {
        return [
            Form::input('text', 'Tuliskan hasil Pengukuran Pencahayaan di Ruang Kelas', 'hpp001'),
            Form::input('text', 'Tuliskan hasil Pengukuran Pencahayaan di Ruang Perpustakaan', 'hpp002'),
            Form::input('text', 'Tuliskan hasil Pengukuran Pencahayaan di Ruang Laboratorium', 'hpp003'),
            Form::input('text', 'Tuliskan hasil Pengukuran Kelembaban', 'hpp004'),
            Form::input('text', 'Tuliskan hasil Pengukuran Kebisingan', 'hpp005'),
            Form::input('text', 'Tuliskan hasil Pengukuran PM 2,5', 'hpp006'),
            Form::input('text', 'Tuliskan hasil Pengukuran PM10', 'hpp007')];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Sekolah::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Sekolah',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Sekolah', $item['subjek']],
                        ['Jenis Sekolah', $item['jenis_sekolah']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)],

                        ['Jumlah Siswa', $item['u004']],
                        ['Jumlah Guru', $item['u005']],
                        ['NPSN', $item['u006']]],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_SEKOLAH_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return Sekolah::withTrashed()->get()->map(function ($item) {
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
                            'Jenis Sekolah',
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

                            'Jumlah Siswa',
                            'Jumlah Guru',
                            'Nomor Pokok Sekolah Nasional',

                            'Tersedia air bersih (Kadar tertinggi yang diperkenankan 15  liter/org/hr)',
                            'Warna (Kadar tertinggi yang diperkenankan 50 TCU)',
                            'Kekeruhan (Kadar tertinggi yang diperkenankan 25 NTU)',
                            'Tidak Bau',
                            'E-Coli (Kadar tertinggi yang diperkenankan 0 jml/100 ml sampel)',
                            'pH (Kadar tertinggi yang diperkenankan 6,5 - 8,5 mg/l)',
                            'Fe (Kadar tertinggi yang diperkenankan 1 mg/l)',
                            'Mn (Kadar tertinggi yang diperkenankan 0,5 mg/l)',
                            'Air bersih tersedia sepanjang waktu',
                            'Pencahayaan ruang kelas (Kadar tertinggi yang diperkenankan 200-300 lux)',
                            'Pencahayaan ruang perpustakaan (Kadar tertinggi yang diperkenankan 200-300 lux)',
                            'Pencahayaan ruang laboratorium (Kadar tertinggi yang diperkenankan 200-300 lux)',
                            'Kelembaban (Kadar tertinggi yang diperkenankan 40-60%)',
                            'Laju ventilasi udara (Kadar tertinggi yang diperkenankan 0,15 - 0,25 m³/menit/org)',
                            'Kebisingan (Kadar tertinggi yang diperkenankan 45 dB(A))',
                            'PM2,5 (Kadar tertinggi yang diperkenankan 35 µg/m³ dalam 24 jam)',
                            'Angka Kuman (Kadar tertinggi yang diperkenankan <700 CFU/m³)',
                            'Sekolah bebas dari asap rokok',
                            'Lingkungan sekolah tidak banyak debu',
                            'Pencahayaan ruang kelas dapat untuk membaca buku dengan jelas tanpa bantuan penerangan pada siang hari (bisa membaca dgn jelas dengan jarak 30 cm)',
                            'Udara dalam ruang sekolah tidak pengap/terasa segar/terasa nyaman',
                            'Udara dalam ruang sekolah tidak berbau',
                            'Apakah memiliki Kantin Sekolah',
                            'Apakah Sudah pernah melakukan IKL pada kantin sekolah',
                            'Jarak papan tulis dengan murid terdepan (Kadar tertinggi yang diperkenankan >2,5 meter)',
                            'Jarak papan tulis dengan murid paling belakang (Kadar tertinggi yang diperkenankan < 9 meter)',
                            'Kepadatan Kelas (Kadar tertinggi yang diperkenankan minimal 1,75 m²/murid)',
                            'Lebar anak tangga (Kadar tertinggi yang diperkenankan minimal 30 cm)',
                            'Lantai (Kadar tertinggi yang diperkenankan maksimal 20 cm)',
                            'Lebar tangga/luas tangga (Kadar tertinggi yang diperkenankan  ≥ 150 cm)',
                            'Jumlah sarana WC/urinoir (Kadar tertinggi yang diperkenankan) = Minimal 1 : 40 untuk laki-laki dan Minimal 1 : 25 untuk perempuan',
                            'Tidak ada genangan (halaman sekolah, atap, talang)',
                            'Dinding kuat, tidak retak, tidak pecah',
                            'Dinding tidak berjamur',
                            'Dinding tidak dicat dengan kapur',
                            'Lantai kuat, kedap air, permukaan rata, tidak retak, tidak licin, mudah dibersihkan',
                            'Tangga harus dilengkapi dengan pegangan tangan',
                            'Tersedia tempat cuci tangan dengan air mengalir dan sabun di setiap kelas',
                            'Kamar mandi bersih dan tidak berbau',
                            'Ventilasi dan penerangan kamar mandi cukup',
                            'Lantai kamar mandi kedap air, tidak licin, tidak ada genangan air',
                            'Kamar mandi/WC tersedia air bersih dan sabun',
                            'Tersedia tempat sampah organik dan anorganik di setiap ruangan',
                            'Tidak ada sampah yang berserakan',
                            'Tersedia Tempat Pembuangan Sementara (TPS) sampah',
                            'Tidak ada sampah membusuk/ berbau di TPS',
                            'Air limbah mengalir dengan lancar',
                            'Tersedia penampungan air limbah yang tertutup',
                            'Saluran pembuangan air limbah kedap air dan tertutup',
                            'Tersedia septic tank dalam kondisi baik (tidak pecah, tidak bocor)',
                            'Tersedia area titik kumpul untuk evakuasi',
                            'Tidak tersedia penampungan air permanen di kamar mandi/WC',
                            'Angka Bebas Jentik (ABJ) (Kadar tertinggi yang diperkenankan 100%)',
                            'Angka rata-rata populasi lalat (Kadar tertinggi yang diperkenankan <2 ekor)',
                            'Angka rata-rata populasi kecoa (Kadar tertinggi yang diperkenankan <2 ekor)',
                            'Strata UKS pada sekolah',
                            'Apakah dilakukan pengukuran kualitas lingkungan menggunakan Sanitarian Kit?',
                            'Sanitarian Kit apakah berfungsi dengan baik',
                            'Tuliskan hasil Pengukuran Pencahayaan di Ruang Kelas',
                            'Tuliskan hasil Pengukuran Pencahayaan di Ruang Perpustakaan',
                            'Tuliskan hasil Pengukuran Pencahayaan di Ruang Laboratorium',
                            'Tuliskan hasil Pengukuran Kelembaban',
                            'Tuliskan hasil Pengukuran Kebisingan',
                            'Tuliskan hasil Pengukuran PM 2,5',
                            'Tuliskan hasil Pengukuran PM10']; }
                    }, 'REPORT_SEKOLAH_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspection.sekolah.create', [
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
            // Validasi input dengan custom error messages
            $request->validate([
                'subjek' => 'required|string|max:255',
                'jenis_sekolah' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:255',
                'u004' => 'required|numeric|min:0',
                'u005' => 'required|numeric|min:0',
                'u006' => 'required|numeric',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
                'status-operasi' => 'required|boolean',
                'catatan-lain' => 'required|string',
                'rencana-tindak-lanjut' => 'required|string',
                'hpp001' => 'required|string',
                'hpp002' => 'required|string',
                'hpp003' => 'required|string',
                'hpp004' => 'required|string',
                'hpp005' => 'required|string',
                'hpp006' => 'required|string',
                'hpp007' => 'required|string',
            ], [
                'subjek.required' => 'Nama Sekolah harus diisi',
                'jenis_sekolah.required' => 'Jenis Sekolah harus dipilih',
                'alamat.required' => 'Alamat harus diisi',
                'kecamatan.required' => 'Kecamatan harus dipilih',
                'kelurahan.required' => 'Kelurahan harus dipilih',
                'pengelola.required' => 'Kepala Sekolah/NIP harus diisi',
                'u004.required' => 'Jumlah Siswa harus diisi',
                'u004.numeric' => 'Jumlah Siswa harus berupa angka',
                'u005.required' => 'Jumlah Guru harus diisi',
                'u005.numeric' => 'Jumlah Guru harus berupa angka',
                'u006.required' => 'Nomor Pokok Sekolah Nasional harus diisi',
                'u006.numeric' => 'Nomor Pokok Sekolah Nasional harus berupa angka',
                'nama-pemeriksa.required' => 'Nama Pemeriksa harus diisi',
                'instansi-pemeriksa.required' => 'Instansi Pemeriksa harus diisi',
                'tanggal-penilaian.required' => 'Tanggal Penilaian harus diisi',
                'tanggal-penilaian.date' => 'Format Tanggal Penilaian tidak valid',
                'koordinat.required' => 'Koordinat harus diisi',
                'koordinat.regex' => 'Format koordinat harus: latitude, longitude (contoh: -6.324667, 106.891268)',
                'status-operasi.required' => 'Status Operasi harus dipilih',
                'catatan-lain.required' => 'Hasil IKL harus diisi',
                'rencana-tindak-lanjut.required' => 'Rencana Tindak Lanjut harus diisi',
                'hpp001.required' => 'Hasil Pengukuran Pencahayaan di Ruang Kelas harus diisi',
                'hpp002.required' => 'Hasil Pengukuran Pencahayaan di Ruang Perpustakaan harus diisi',
                'hpp003.required' => 'Hasil Pengukuran Pencahayaan di Ruang Laboratorium harus diisi',
                'hpp004.required' => 'Hasil Pengukuran Kelembaban harus diisi',
                'hpp005.required' => 'Hasil Pengukuran Kebisingan harus diisi',
                'hpp006.required' => 'Hasil Pengukuran PM 2,5 harus diisi',
                'hpp007.required' => 'Hasil Pengukuran PM10 harus diisi',
            ]);

            $data = $request->all();
            
            // Handle instansi lainnya
            if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->filled('instansi-lainnya')) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // Handle duplicate action
            if ($request->input('action') == 'duplicate' && $request->input('original_id')) {
                $original = Sekolah::find($request->input('original_id'));
                
                if ($original) {
                    // Create fallback data with original values for required fields
                    $fallbackData = array_merge($data, [
                        'kelurahan' => !empty($data['kelurahan']) ? $data['kelurahan'] : $original->kelurahan,
                        'kecamatan' => !empty($data['kecamatan']) ? $data['kecamatan'] : $original->kecamatan,
                        'subjek' => !empty($data['subjek']) ? $data['subjek'] : $original->subjek,
                        'alamat' => !empty($data['alamat']) ? $data['alamat'] : $original->alamat,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    // Remove duplicate action fields
                    unset($fallbackData['action'], $fallbackData['original_id']);
                    $data = $fallbackData;
                }
            }
            
            // Add auth user ID
            $data['user_id'] = Auth::id();

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 58 * 100);

            $insert = Sekolah::create($data);

            if (!$insert) {
                return redirect()->back()->with('error', 'Penilaian / inspeksi Sekolah gagal dibuat, silahkan coba lagi')->withInput();
            }

            $message = $request->input('action') == 'duplicate' ? 'Duplikat penilaian / inspeksi Sekolah berhasil dibuat' : 'Penilaian / inspeksi Sekolah berhasil dibuat';
            return redirect(route('sekolah.show', ['sekolah' => $insert->id]))->with('success', $message);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in store method', [
                'errors' => $e->errors(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorList = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorList[] = $message;
                }
            }
            
            // Create detailed error information for logging and display
            $errorDetails = [
                'Error Type' => 'Validation Error (Store)',
                'Error Message' => 'Field yang belum diisi dengan benar: ' . implode(', ', $errorList),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Code' => $e->getCode(),
                'Fields' => json_encode($e->errors(), JSON_PRETTY_PRINT)
            ];
            
            $errorText = "Validation Error Details (Store):\n";
            foreach ($errorDetails as $key => $value) {
                $errorText .= "{$key}: {$value}\n";
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_details', $errorText)
                ->with('error', 'Terjadi kesalahan validasi. Lihat detail error di bawah.');
                
        } catch (\Exception $e) {
            Log::error('Error creating sekolah', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Create detailed error information for logging and display
            $errorDetails = [
                'Error Type' => 'General Error (Store)',
                'Error Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Code' => $e->getCode()
            ];
            
            $errorText = "General Error Details (Store):\n";
            foreach ($errorDetails as $key => $value) {
                $errorText .= "{$key}: {$value}\n";
            }
            
            return redirect()->back()
                ->with('error_details', $errorText)
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Lihat detail error di bawah.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $sekolah,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Sekolah',
            'edit_route' => route('sekolah.edit', ['sekolah' => $sekolah['id']]),
            'destroy_route' => route('sekolah.destroy', ['sekolah' => $sekolah['id']]),
            'export_route' => route(
                'sekolah.index',
                [
                    'export' => 'pdf',
                    'id' => $sekolah['id']],
            )]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah)
    {
        return view('pages.inspection.sekolah.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'hasil_pengukuran' => $this->hasilPengukuran(),
            'form_data' => $sekolah]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sekolah $sekolah)
    {
        Log::info('SekolahController update called', [
            'action' => $request->input('action'),
            'all_files' => $request->allFiles()
        ]);
        
        try {
            // Validasi input dengan custom error messages
            $request->validate([
                'subjek' => 'required|string|max:255',
                'jenis_sekolah' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:255',
                'u004' => 'required|numeric|min:0',
                'u005' => 'required|numeric|min:0',
                'u006' => 'required|numeric',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
                'status-operasi' => 'required|boolean',
                'catatan-lain' => 'required|string',
                'rencana-tindak-lanjut' => 'required|string',
                'hpp001' => 'required|string',
                'hpp002' => 'required|string',
                'hpp003' => 'required|string',
                'hpp004' => 'required|string',
                'hpp005' => 'required|string',
                'hpp006' => 'required|string',
                'hpp007' => 'required|string',
            ], [
                'subjek.required' => 'Nama Sekolah harus diisi',
                'jenis_sekolah.required' => 'Jenis Sekolah harus dipilih',
                'alamat.required' => 'Alamat harus diisi',
                'kecamatan.required' => 'Kecamatan harus dipilih',
                'kelurahan.required' => 'Kelurahan harus dipilih',
                'pengelola.required' => 'Kepala Sekolah/NIP harus diisi',
                'u004.required' => 'Jumlah Siswa harus diisi',
                'u004.numeric' => 'Jumlah Siswa harus berupa angka',
                'u005.required' => 'Jumlah Guru harus diisi',
                'u005.numeric' => 'Jumlah Guru harus berupa angka',
                'u006.required' => 'Nomor Pokok Sekolah Nasional harus diisi',
                'u006.numeric' => 'Nomor Pokok Sekolah Nasional harus berupa angka',
                'nama-pemeriksa.required' => 'Nama Pemeriksa harus diisi',
                'instansi-pemeriksa.required' => 'Instansi Pemeriksa harus diisi',
                'tanggal-penilaian.required' => 'Tanggal Penilaian harus diisi',
                'tanggal-penilaian.date' => 'Format Tanggal Penilaian tidak valid',
                'koordinat.required' => 'Koordinat harus diisi',
                'koordinat.regex' => 'Format koordinat harus: latitude, longitude (contoh: -6.324667, 106.891268)',
                'status-operasi.required' => 'Status Operasi harus dipilih',
                'catatan-lain.required' => 'Hasil IKL harus diisi',
                'rencana-tindak-lanjut.required' => 'Rencana Tindak Lanjut harus diisi',
                'hpp001.required' => 'Hasil Pengukuran Pencahayaan di Ruang Kelas harus diisi',
                'hpp002.required' => 'Hasil Pengukuran Pencahayaan di Ruang Perpustakaan harus diisi',
                'hpp003.required' => 'Hasil Pengukuran Pencahayaan di Ruang Laboratorium harus diisi',
                'hpp004.required' => 'Hasil Pengukuran Kelembaban harus diisi',
                'hpp005.required' => 'Hasil Pengukuran Kebisingan harus diisi',
                'hpp006.required' => 'Hasil Pengukuran PM 2,5 harus diisi',
                'hpp007.required' => 'Hasil Pengukuran PM10 harus diisi',
            ]);
            
            Log::info('Validation passed');
            
            $data = $request->all();
            
            // Handle instansi lainnya
            if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->filled('instansi-lainnya')) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 58 * 100);

            if ($data['action'] == 'duplicate') {
                Log::info('Starting duplicate process for sekolah', ['original_id' => $sekolah->id]);
                
                // Add auth user ID only for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if (empty($data['kelurahan']) && !empty($sekolah->kelurahan)) {
                    $data['kelurahan'] = $sekolah->kelurahan;
                }
                if (empty($data['kecamatan']) && !empty($sekolah->kecamatan)) {
                    $data['kecamatan'] = $sekolah->kecamatan;
                }
                if (empty($data['subjek']) && !empty($sekolah->subjek)) {
                    $data['subjek'] = $sekolah->subjek;
                }
                if (empty($data['alamat']) && !empty($sekolah->alamat)) {
                    $data['alamat'] = $sekolah->alamat;
                }
                
                // Remove action field before creating
                unset($data['action']);
                
                Log::info('Data for duplicate creation', ['data_keys' => array_keys($data)]);

                try {
                    $insert = Sekolah::create($data);
                    
                    if (!$insert) {
                        Log::error('Failed to create duplicate sekolah - insert returned false');
                        return redirect()->back()->with('error', 'Penilaian / inspeksi Sekolah gagal dibuat, silahkan coba lagi')->withInput();
                    }
                    
                    Log::info('Duplicate sekolah created successfully', ['new_id' => $insert->id]);
                    return redirect(route('sekolah.show', ['sekolah' => $insert->id]))->with('success', 'Duplikat penilaian / inspeksi Sekolah berhasil dibuat');
                    
                } catch (\Exception $duplicateException) {
                     Log::error('Error creating duplicate sekolah', [
                         'error' => $duplicateException->getMessage(),
                         'trace' => $duplicateException->getTraceAsString(),
                         'data' => $data
                     ]);
                     
                     // Display detailed error for debugging
                     $errorDetails = [
                         'Error Message' => $duplicateException->getMessage(),
                         'File' => $duplicateException->getFile(),
                         'Line' => $duplicateException->getLine(),
                         'Code' => $duplicateException->getCode()
                     ];
                     
                     $errorText = "Error Details:\n";
                     foreach ($errorDetails as $key => $value) {
                         $errorText .= "{$key}: {$value}\n";
                     }
                     
                     // Return with detailed error that can be copied
                     return redirect()->back()
                         ->with('error_details', $errorText)
                         ->with('error', 'Terjadi kesalahan saat membuat duplikat. Lihat detail error di bawah.')
                         ->withInput();
                 }
            }

            // Remove user_id from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            Log::info('Data to update', ['data_keys' => array_keys($data)]);
            
            $update = $sekolah->update($data);

            Log::info('Update result', ['success' => $update]);

            if (!$update) {
                Log::error('Update failed for sekolah', ['id' => $sekolah->id]);
                return redirect()->back()->with('error', 'Form informasi dan penilaian Sekolah gagal diubah')->withInput();
            }

            Log::info('Update successful, redirecting');
            return redirect(route('sekolah.show', ['sekolah' => $sekolah['id']]))->with('success', 'Form informasi dan penilaian Sekolah berhasil diubah');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorList = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorList[] = $message;
                }
            }
            
            // Create detailed error information for logging and display
            $errorDetails = [
                'Error Type' => 'Validation Error',
                'Error Message' => 'Field yang belum diisi dengan benar: ' . implode(', ', $errorList),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Code' => $e->getCode(),
                'Fields' => json_encode($e->errors(), JSON_PRETTY_PRINT)
            ];
            
            $errorText = "Validation Error Details:\n";
            foreach ($errorDetails as $key => $value) {
                $errorText .= "{$key}: {$value}\n";
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_details', $errorText)
                ->with('error', 'Terjadi kesalahan validasi. Lihat detail error di bawah.');
                
        } catch (\Exception $e) {
            Log::error('Error updating sekolah', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'sekolah_id' => $sekolah->id ?? 'unknown'
            ]);
            
            // Create detailed error information for logging and display
            $errorDetails = [
                'Error Type' => 'General Error',
                'Error Message' => $e->getMessage(),
                'File' => $e->getFile(),
                'Line' => $e->getLine(),
                'Code' => $e->getCode()
            ];
            
            $errorText = "General Error Details:\n";
            foreach ($errorDetails as $key => $value) {
                $errorText .= "{$key}: {$value}\n";
            }
            
            return redirect()->back()
                ->with('error_details', $errorText)
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Lihat detail error di bawah.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $sekolah = Sekolah::where('id', $id)->withTrashed()->first();

        if ($sekolah['deleted_at']) {
            $sekolah->update([
                'deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $sekolah->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Sekolah berhasil dihapus');
    }
    }