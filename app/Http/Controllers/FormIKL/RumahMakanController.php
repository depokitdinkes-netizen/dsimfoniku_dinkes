<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\RumahMakan;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RumahMakanController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Rumah Makan', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Jumlah Penjamah Pangan', 'u004'),
            Form::input('number', 'Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat', 'u005'),
            Form::input('number', 'Nomor Izin Usaha (Opsional)', 'u006'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Tipe Rumah Makan', 'u009'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Luar TPP'),

            Form::select('Lokasi bebas banjir', 'tpp001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'tpp002'),
            Form::select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'tpp003'),
            Form::select('Tenda tidak bocor (kedap air), kuat dan mudah dibersihkan', 'tpp004', 2),

            Form::h(2, 'Inspeksi Area Pelayanan Konsumen'),

            Form::select('Area tempat makan konsumen bersih', 'pk001'),
            Form::select('Dinding ruang makan bersih (jika tidak ada dinding, maka abaikan persyaratan ini)', 'pk002'),
            Form::select('Ventilasi udara baik (bisa menggunakan ventilasi udara alami atau buatan)', 'pk003'),
            Form::select('Memiliki tempat sampah,', 'pk004'),
            Form::select('Yang Tertutup rapat', 'pk005'),
            Form::select('Yang Tidak ada tumpukan sampah. Frekuensi pembuangan teratur', 'pk006', 2),

            Form::select('Tempat/area makan atau meja makan konsumen bersih dan mudah dibersihkan', 'pk007', 3),
            Form::select('Tempat/area makan atau meja makan konsumen utuh/rata', 'pk008'),
            Form::select('Tempat/area makan atau meja makan konsumen kedap air', 'pk009'),

            Form::select('Peralatan yang digunakan untuk penyajian bersih', 'pk010', 3),
            Form::select('Peralatan yang digunakan untuk penyajian utuh', 'pk011', 2),
            Form::select('Peralatan yang digunakan untuk penyajian aman bagi kesehatan', 'pk012', 3),
            Form::select('Peralatan yang digunakan untuk penyajian tara pangan (food grade)', 'pk013', 3),

            Form::select('Pangan yang tidak dikemas harus disajikan dengan penutup (tudung saji) atau di dalam lemari display yang tertutup', 'pk014', 3),
            Form::select('Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 50C (lemari pendingin) atau di wadah bersuhu dingin (coolbox)', 'pk015', 3),
            Form::select('Pangan siap saji berkuah disimpan dalam kondisi panas dengan suhu di atas 600C (wadah dengan pemanas)', 'pk016', 3),
            Form::select('Pangan matang yang mudah rusak dan disimpan pada suhu ruang dikonsumsi maksimal 4 jam setelah dimasak, jika masih akan dikonsumsi harus dilakukan pemanasan ulang', 'pk017', 3),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'pk018', 3),
            Form::select('Personel yang menyentuh uang saat melayani pembayaran, tidak menyentuh pangan secara langsung sebelum melakukan cuci tangan atau menggunakan hand sanitizer', 'pk019', 3),

            Form::h(2, 'Inspeksi Area Dapur / Penyiapan Pangan'),

            Form::h(3, 'A Umum', 'umum'),

            Form::select('Tersedia akses ke sumber air yang aman', 'um001', 2),
            Form::select('Tersedia akses jamban/toilet yang mudah diakses', 'um002', 2),
            Form::select('Tersedia tempat pencucian peralatan dan bahan pangan, dengan: a. Menggunakan air mengalir', 'um003', 2),
            Form::select('Pencucian tidak dilakukan di area sumber kontaminasi (kamar mandi, jamban, kamar mandi umum, sungai, atau air permukaan seperti danau, dan lainnya)', 'um004', 3),
            Form::select('Tersedia tempat cuci tangan', 'um005', 3),
            Form::select('Dengan air mengalir', 'um006', 3),
            Form::select('Dengan sabun cuci tangan', 'um007', 3),
            Form::select('Tersedia tempat sampah yang tertutup', 'um008', 2),
            Form::select('Tersedia tempat penyimpanan pangan yang bersih terlindung dari bahan kimia, serta vektor dan binatang pembawa penyakit', 'um009', 2),
            Form::select('Tersedia tempat penyimpanan peralatan yang bersih terhindar dari vektor dan binatang pembawa penyakit', 'um010', 2),
            Form::select('Tempat penyimpanan bukan merupakan jalur akses ke kamar mandi atau jamban', 'um011', 2),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'um012', 3),
            Form::select('Bahan kimia (insektisida dan lainnya) tidak disimpan bersebelahan dengan bahan pangan', 'um013', 3),
            Form::select('Lantai Rata', 'um014'),
            Form::select('Lantai Mudah dibersihkan', 'um015'),
            Form::select('Memiliki ventilasi udara', 'um016'),
            Form::select('Yang Berbahan kuat dan tahan lama', 'um017'),
            Form::select('Yang Jika terbuka, memiliki kasa anti serangga yang mudah dilepas dan dibersihkan', 'um018'),
            Form::select('Yang Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih', 'um019'),

            Form::h(3, 'B Pemilihan dan Penyimpanan Bahan Pangan', 'pemilihan-dan-penyimpanan-bahan-pangan'),

            Form::select('Bahan pangan mutu baik', 'bpp001'),
            Form::select('Bahan pangan utuh dan tidak rusak', 'bpp002'),
            Form::select('Bahan baku pangan dalam kemasan memiliki label', 'bpp003', 2),
            Form::select('Bahan baku pangan dalam kemasan terdaftar atau ada izin edar', 'bpp004', 2),
            Form::select('Bahan baku pangan dalam kemasan tidak kadaluwarsa', 'bpp005', 2),
            Form::select('Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)', 'bpp006', 2),

            Form::select('Pangan yang disimpan di kulkas bersih', 'bpp007', 2),
            Form::select('Pangan yang disimpan di kulkas tersusun rapi sesuai jenis pangan (matang di atas dan mentah di bagian bawah)', 'bpp008', 2),
            Form::select('Pangan yang disimpan di kulkas tidak terlalu padat', 'bpp009', 2),

            Form::select('Bahan pangan disimpan terpisah dan dikelompokkan menurut jenisnya dalam wadah yang bersih, dan tara pangan (food grade)', 'bpp010', 2),
            Form::select('Bahan pangan disimpan pada suhu yang tepat sesuai jenisnya', 'bpp011', 2),
            Form::select('Tidak terdapat bahan pangan yang kadaluwarsa', 'bpp012', 2),
            Form::select('Bahan pangan tertutup untuk mencegah akses vektor dan binatang pembawa penyakit', 'bpp013', 2),

            Form::h(3, 'C Persiapan dan Pengolahan/Pemasakan Pangan', 'persiapan-dan-pengolahan-pemasakan-pangan'),

            Form::select('Pencahayaan cukup terang', 'cpp001', 2),
            Form::select('Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak', 'cpp002', 2),
            Form::select('Melakukan thawing/pelunakan pangan dengan benar', 'cpp003', 2),
            Form::select('Pangan dimasak dengan suhu yang sesuai dan matang', 'cpp004', 3),

            Form::select('Personil yang bekerja pada area ini sehat dan bebas dari penyakit menular', 'cpp005', 3),
            Form::select('Personil menggunakan APD: Celemek', 'cpp006', 2),
            Form::select('Personil menggunakan APD: Masker', 'cpp007', 3),
            Form::select('Personil menggunakan APD: Hairnet/penutup rambut', 'cpp008', 3),
            Form::select('Personil berkuku pendek, bersih dan tidak memakai pewarna kuku', 'cpp009', 3),
            Form::select('Personil selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'cpp010', 3),
            Form::select('Personil tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros dan lain-lain) ketika mengolah pangan', 'cpp011', 3),

            Form::select('Pada saat mengolah pangan: Tidak merokok', 'cpp012', 3),
            Form::select('Pada saat mengolah pangan: Tidak bersin atau batuk di atas pangan langsung', 'cpp013', 3),
            Form::select('Pada saat mengolah pangan: Tidak meludah sembarangan', 'cpp014', 3),
            Form::select('Pada saat mengolah pangan: Tidak mengunyah makanan/permen', 'cpp015', 3),
            Form::select('Pada saat mengolah pangan: Tidak menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian langsung menyentuh pangan', 'cpp016', 3),
            Form::select('Personil mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'cpp017', 3),
            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'cpp018', 3),
            Form::select('Personil melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun', 'cpp019', 2),
            Form::select('Penjamah pangan sudah mendapat penyuluhan keamanan pangan siap saji', 'cpp020', 3),

            Form::h(3, 'Peralatan (termasuk meja tempat pengolahan)', 'peralatan'),

            Form::select('Peralatan untuk pengolahan pangan berbahan kuat', 'pl001', 2),
            Form::select('Peralatan untuk pengolahan pangan tidak berkarat', 'pl002', 3),
            Form::select('Peralatan untuk pengolahan pangan tara pangan (food grade)', 'pl003', 3),
            Form::select('Peralatan untuk pengolahan pangan bersih sebelum digunakan', 'pl004', 3),
            Form::select('Peralatan untuk pengolahan pangan dalam kondisi bersih dan kering setelah digunakan', 'pl005', 2),
            Form::select('Peralatan berbeda untuk pangan matang dan pangan mentah', 'pl006', 3),
            Form::select('Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade', 'pl007', 3),
            Form::select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'pl008', 2),
            Form::select('Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)', 'pl009', 2),

            Form::h(3, 'Penyajian Pangan Matang', 'penyajian-pangan-matang'),

            Form::select('Pangan matang yang mudah rusak harus sudah dikonsumsi 4 (empat) jam setelah matang', 'pm001', 3),
            Form::select('Pangan matang panas dijaga pada suhu > 60°C', 'pm002', 3),
            Form::select('Pangan matang dingin dijaga pada suhu < 5°C', 'pm003', 3),
            Form::select('Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5°C (lemari pendingin) atau di wadah bersuhu dingin (coolbox)', 'pm004', 3),
            Form::select('Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'pm005', 3),
            Form::select('Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi', 'pm006', 3),
            Form::select('Air untuk minum memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'pm007', 3),

            Form::select('Tempat yang digunakan untuk menyajikan pangan: Piring bersih dan tara pangan', 'pm008', 3),
            Form::select('Tempat yang digunakan untuk menyajikan pangan: Gelas bersih dan tara pangan', 'pm009', 3),
            Form::select('Tempat yang digunakan untuk menyajikan pangan: Sendok bersih dan tara pangan', 'pm010', 3),
            Form::select('Tempat yang digunakan untuk menyajikan pangan: Sedotan bersih dan tara pangan', 'pm011', 3),

            Form::h(3, 'Pengemasan Pangan Matang', 'pengemasan-pangan-matang'),

            Form::select('Pengemasan dilakukan secara higiene (personel cuci tangan dan menggunakan sarung tangan dengan kondisi baik)', 'pm012', 3),
            Form::select('Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)', 'pm013', 3),
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'tpp001',
            'tpp002',
            'tpp003',
            'tpp004',

            'pk001',
            'pk002',
            'pk003',
            'pk004',
            'pk005',
            'pk006',
            'pk007',
            'pk008',
            'pk009',
            'pk010',
            'pk011',
            'pk012',
            'pk013',
            'pk014',
            'pk015',
            'pk016',
            'pk017',
            'pk018',
            'pk019',

            'um001',
            'um002',
            'um003',
            'um004',
            'um005',
            'um006',
            'um007',
            'um008',
            'um009',
            'um010',
            'um011',
            'um012',
            'um013',
            'um014',
            'um015',
            'um016',
            'um017',
            'um018',
            'um019',

            'bpp001',
            'bpp002',
            'bpp003',
            'bpp004',
            'bpp005',
            'bpp006',
            'bpp007',
            'bpp008',
            'bpp009',
            'bpp010',
            'bpp011',
            'bpp012',
            'bpp013',

            'cpp001',
            'cpp002',
            'cpp003',
            'cpp004',
            'cpp005',
            'cpp006',
            'cpp007',
            'cpp008',
            'cpp009',
            'cpp010',
            'cpp011',
            'cpp012',
            'cpp013',
            'cpp014',
            'cpp015',
            'cpp016',
            'cpp017',
            'cpp018',
            'cpp019',
            'cpp020',

            'pl001',
            'pl002',
            'pl003',
            'pl004',
            'pl005',
            'pl006',
            'pl007',
            'pl008',
            'pl009',

            'pm001',
            'pm002',
            'pm003',
            'pm004',
            'pm005',
            'pm006',
            'pm007',
            'pm008',
            'pm009',
            'pm010',
            'pm011',
            'pm012',
            'pm013',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = RumahMakan::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Rumah Makan',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Rumah Makan', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Nomor Izin Usaha', $item['u006'] ?? '-'],
                        ['Penjamah Pangan (Bersertifikat/Total)', $item['u005'] . '/' . $item['u004']],
                        ['Tipe Rumah Makan', $item['u009']],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                    'is_superadmin' => Auth::check() && Auth::user()->role === 'superadmin',
                ])->download('BAIKL_RUMAH_MAKAN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return RumahMakan::withTrashed()->get()->map(function ($item) {
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

                            'Penjamah Pangan Total',
                            'Penjamah Pangan Total Bersertifikat',
                            'Nomor Izin Usaha',
                            'Tipe Rumah Makan',

                            'Lokasi bebas banjir',
                            'Lokasi bebas dari pencemaran bau/asap/debu/kotoran',
                            'Lokasi bebas dari sumber vektor dan binatang pembawa penyakit',
                            'Tenda tidak bocor (kedap air), kuat dan mudah dibersihkan',
                            'Area tempat makan konsumen bersih',
                            'Dinding ruang makan bersih (jika tidak ada dinding, maka abaikan persyaratan ini)',
                            'Ventilasi udara baik (bisa menggunakan ventilasi udara alami atau buatan)',
                            'Memiliki tempat sampah,',
                            'Yang Tertutup rapat',
                            'Yang Tidak ada tumpukan sampah. Frekuensi pembuangan teratur',
                            'Tempat/area makan atau meja makan konsumen bersih dan mudah dibersihkan',
                            'Tempat/area makan atau meja makan konsumen utuh/rata',
                            'Tempat/area makan atau meja makan konsumen kedap air',
                            'Peralatan yang digunakan untuk penyajian bersih',
                            'Peralatan yang digunakan untuk penyajian utuh',
                            'Peralatan yang digunakan untuk penyajian aman bagi kesehatan',
                            'Peralatan yang digunakan untuk penyajian tara pangan (food grade)',
                            'Pangan yang tidak dikemas harus disajikan dengan penutup (tudung saji) atau di dalam lemari display yang tertutup',
                            'Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 50C (lemari pendingin) atau di wadah bersuhu dingin (coolbox)',
                            'Pangan siap saji berkuah disimpan dalam kondisi panas dengan suhu di atas 600C (wadah dengan pemanas)',
                            'Pangan matang yang mudah rusak dan disimpan pada suhu ruang dikonsumsi maksimal 4 jam setelah dimasak, jika masih akan dikonsumsi harus dilakukan pemanasan ulang',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Personel yang menyentuh uang saat melayani pembayaran, tidak menyentuh pangan secara langsung sebelum melakukan cuci tangan atau menggunakan hand sanitizer',
                            'Tersedia akses ke sumber air yang aman',
                            'Tersedia akses jamban/toilet yang mudah diakses',
                            'Tersedia tempat pencucian peralatan dan bahan pangan, dengan: a. Menggunakan air mengalir',
                            'Pencucian tidak dilakukan di area sumber kontaminasi (kamar mandi, jamban, kamar mandi umum, sungai, atau air permukaan seperti danau, dan lainnya)',
                            'Tersedia tempat cuci tangan',
                            'Dengan air mengalir',
                            'Dengan sabun cuci tangan',
                            'Tersedia tempat sampah yang tertutup',
                            'Tersedia tempat penyimpanan pangan yang bersih terlindung dari bahan kimia, serta vektor dan binatang pembawa penyakit',
                            'Tersedia tempat penyimpanan peralatan yang bersih terhindar dari vektor dan binatang pembawa penyakit',
                            'Tempat penyimpanan bukan merupakan jalur akses ke kamar mandi atau jamban',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Bahan kimia (insektisida dan lainnya) tidak disimpan bersebelahan dengan bahan pangan',
                            'Lantai Rata',
                            'Lantai Mudah dibersihkan',
                            'Memiliki ventilasi udara',
                            'Yang Berbahan kuat dan tahan lama',
                            'Yang Jika terbuka, memiliki kasa anti serangga yang mudah dilepas dan dibersihkan',
                            'Yang Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih',
                            'Bahan pangan mutu baik',
                            'Bahan pangan utuh dan tidak rusak',
                            'Bahan baku pangan dalam kemasan memiliki label',
                            'Bahan baku pangan dalam kemasan terdaftar atau ada izin edar',
                            'Bahan baku pangan dalam kemasan tidak kadaluwarsa',
                            'Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)',
                            'Pangan yang disimpan di kulkas bersih',
                            'Pangan yang disimpan di kulkas tersusun rapi sesuai jenis pangan (matang di atas dan mentah di bagian bawah)',
                            'Pangan yang disimpan di kulkas tidak terlalu padat',
                            'Bahan pangan disimpan terpisah dan dikelompokkan menurut jenisnya dalam wadah yang bersih, dan tara pangan (food grade)',
                            'Bahan pangan disimpan pada suhu yang tepat sesuai jenisnya',
                            'Tidak terdapat bahan pangan yang kadaluwarsa',
                            'Bahan pangan tertutup untuk mencegah akses vektor dan binatang pembawa penyakit',
                            'Pencahayaan cukup terang',
                            'Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak',
                            'Melakukan thawing/pelunakan pangan dengan benar',
                            'Pangan dimasak dengan suhu yang sesuai dan matang',
                            'Personil yang bekerja pada area ini sehat dan bebas dari penyakit menular',
                            'Personil menggunakan APD: Celemek',
                            'Personil menggunakan APD: Masker',
                            'Personil menggunakan APD: Hairnet/penutup rambut',
                            'Personil berkuku pendek, bersih dan tidak memakai pewarna kuku',
                            'Personil selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan',
                            'Personil tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros dan lain-lain) ketika mengolah pangan',
                            'Pada saat mengolah pangan: Tidak merokok',
                            'Pada saat mengolah pangan: Tidak bersin atau batuk di atas pangan langsung',
                            'Pada saat mengolah pangan: Tidak meludah sembarangan',
                            'Pada saat mengolah pangan: Tidak mengunyah makanan/permen',
                            'Pada saat mengolah pangan: Tidak menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian langsung menyentuh pangan',
                            'Personil mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                            'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                            'Personil melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun',
                            'Penjamah pangan sudah mendapat penyuluhan keamanan pangan siap saji',
                            'Peralatan untuk pengolahan pangan berbahan kuat',
                            'Peralatan untuk pengolahan pangan tidak berkarat',
                            'Peralatan untuk pengolahan pangan tara pangan (food grade)',
                            'Peralatan untuk pengolahan pangan bersih sebelum digunakan',
                            'Peralatan untuk pengolahan pangan dalam kondisi bersih dan kering setelah digunakan',
                            'Peralatan berbeda untuk pangan matang dan pangan mentah',
                            'Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade',
                            'Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang',
                            'Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)',
                            'Pangan matang yang mudah rusak harus sudah dikonsumsi 4 (empat) jam setelah matang',
                            'Pangan matang panas dijaga pada suhu > 60°C',
                            'Pangan matang dingin dijaga pada suhu < 5°C',
                            'Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5°C (lemari pendingin) atau di wadah bersuhu dingin (coolbox)',
                            'Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi',
                            'Air untuk minum memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Tempat yang digunakan untuk menyajikan pangan: Piring bersih dan tara pangan',
                            'Tempat yang digunakan untuk menyajikan pangan: Gelas bersih dan tara pangan',
                            'Tempat yang digunakan untuk menyajikan pangan: Sendok bersih dan tara pangan',
                            'Tempat yang digunakan untuk menyajikan pangan: Sedotan bersih dan tara pangan',
                            'Pengemasan dilakukan secara higiene (personel cuci tangan dan menggunakan sarung tangan dengan kondisi baik)',
                            'Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ]; }
                    }, 'REPORT_RUMAH_MAKAN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }
    public function create(Request $request)
    {
        $tipe = strtoupper($request->query('tipe', 'a1'));

        $formPenilaian = $this->formPenilaian();

        switch ($tipe) {
            case 'A1':
                unset($formPenilaian[4]);
                break;
            case 'A2':
                //  inspeksi area pelayanan konsumen
                $bukanTipeUmum = [];
                foreach (
                    [
                        'um011',
                        'um016',
                        'um017',
                        'um018',
                        'um019',

                        'bpp007',
                        'bpp008',
                        'bpp009',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanTipeUmum, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanTipeUmum as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada restoran dengan tipe tersebut');
        }

        return view('pages.inspection.rumah-makan.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'tipe' => $tipe,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Check if user is authenticated or guest
            $isGuest = !Auth::check();
            
            if ($isGuest) {
                Log::info('Guest user attempting to store RumahMakan data');
            }

            // Validasi input komprehensif
            $validatedData = $request->validate([
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'u004' => 'nullable|numeric|min:0',
                'u005' => 'nullable|numeric|min:0',
                'u006' => 'nullable|string|max:255',
                'u009' => 'required|in:A1,A2',
                'dokumen_slhs' => 'nullable|url',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
                'status-operasi' => 'nullable|string',
                'kontak' => 'nullable|numeric',
                'koordinat' => 'required|string|max:255',
                'nama-pemeriksa' => 'nullable|string|max:255',
                'instansi-pemeriksa' => 'nullable|string|max:255',
                'tanggal-penilaian' => 'nullable|date',
                'catatan-lain' => 'nullable|string|max:1000',
                'rencana-tindak-lanjut' => 'nullable|string|max:1000',
                'tujuan-ikl' => 'nullable|string|max:255',
            ], [
                'subjek.required' => 'Nama rumah makan wajib diisi.',
                'subjek.max' => 'Nama rumah makan maksimal 255 karakter.',
                'pengelola.required' => 'Nama pengelola wajib diisi.',
                'pengelola.max' => 'Nama pengelola maksimal 255 karakter.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.max' => 'Alamat maksimal 500 karakter.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'u009.required' => 'Tipe rumah makan wajib dipilih.',
                'u009.in' => 'Tipe rumah makan harus A1 atau A2.',
                'koordinat.required' => 'Koordinat wajib diisi.',
                'dokumen_slhs.url' => 'Link dokumen SLHS harus berupa URL yang valid.',
                'slhs_issued_date.date' => 'Format tanggal terbit SLHS tidak valid.',
                'slhs_expire_date.date' => 'Format tanggal berakhir SLHS tidak valid.',
                'slhs_expire_date.after_or_equal' => 'Tanggal berakhir SLHS harus setelah tanggal terbit.',
            ]);

            Log::info('RumahMakan form submission started', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola'),
                'is_guest' => $isGuest
            ]);

            $data = $request->all();
            
            // Set user_id: 3 untuk guest, Auth::id() untuk user yang login
            $data['user_id'] = $isGuest ? 3 : Auth::id();
            
            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // Handle duplicate action
            if ($request->input('action') == 'duplicate' && $request->input('original_id')) {
                $original = RumahMakan::find($request->input('original_id'));
                
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
                        'tujuan-ikl' => !empty($data['tujuan-ikl']) ? $data['tujuan-ikl'] : $original->{'tujuan-ikl'},
                        'u009' => !empty($data['u009']) ? $data['u009'] : $original->u009,
                        'u004' => isset($data['u004']) ? $data['u004'] : $original->u004,
                        'u005' => isset($data['u005']) ? $data['u005'] : $original->u005,
                        'u006' => isset($data['u006']) && !empty($data['u006']) ? $data['u006'] : $original->u006,
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

            $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A1' => 225, 'A2' => 215][strtoupper($request->input('u009', 'A1'))]) * 100);

            $insert = RumahMakan::create($data);

            if (!$insert) {
                Log::error('Failed to create RumahMakan record', [
                    'user_id' => $isGuest ? 3 : Auth::id(),
                    'is_guest' => $isGuest,
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Rumah Makan gagal dibuat, silahkan coba lagi.');
            }

            Log::info('RumahMakan record created successfully', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            $message = $request->input('action') == 'duplicate' ? 'Duplikat penilaian/inspeksi Rumah Makan berhasil dibuat.' : 'Penilaian/inspeksi Rumah Makan ' . $request->input('u009') . ' berhasil dibuat.';
            return redirect(route('rumah-makan.show', ['rumah_makan' => $insert->id]))->with('success', $message);

        } catch (ValidationException $e) {
            $isGuest = !Auth::check();
            Log::warning('RumahMakan form validation failed', [
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
            Log::error('Unexpected error during RumahMakan form submission', [
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

    /**
     * Display the specified resource.
     */
    public function show(RumahMakan $rumahMakan)
    {
        return view('pages.inspection.show', [
            'page_name' => 'inspection',
            'form_data' => $rumahMakan,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Rumah Makan Tipe ' . $rumahMakan['u009'],
            'edit_route' => route('rumah-makan.edit', ['rumah_makan' => $rumahMakan['id']]),
            'destroy_route' => route('rumah-makan.destroy', ['rumah_makan' => $rumahMakan['id']]),
            'export_route' => route(
                'rumah-makan.index',
                [
                    'export' => 'pdf',
                    'id' => $rumahMakan['id'],
                ],
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RumahMakan $rumahMakan)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $formPenilaian = $this->formPenilaian();

        switch ($rumahMakan->u009) {
            case 'A1':
                unset($formPenilaian[4]);
                break;
            case 'A2':
                //  inspeksi area pelayanan konsumen
                $bukanTipeUmum = [];
                foreach (
                    [
                        'um011',
                        'um016',
                        'um017',
                        'um018',
                        'um019',

                        'bpp007',
                        'bpp008',
                        'bpp009',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanTipeUmum, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanTipeUmum as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada restoran dengan tipe tersebut');
        }

        return view('pages.inspection.rumah-makan.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'form_data' => $rumahMakan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RumahMakan $rumahMakan)
    {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle instansi lainnya
        if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->filled('instansi-lainnya')) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        if ($request->hasFile('dokumen_slhs')) {
            $file = $request->file('dokumen_slhs');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A1' => 225, 'A2' => 215][strtoupper($request->input('u009', 'A1'))]) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($rumahMakan->kelurahan)) {
                $data['kelurahan'] = $rumahMakan->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($rumahMakan->kecamatan)) {
                $data['kecamatan'] = $rumahMakan->kecamatan;
            }
            if (empty($data['subjek']) && !empty($rumahMakan->subjek)) {
                $data['subjek'] = $rumahMakan->subjek;
            }
            if (empty($data['alamat']) && !empty($rumahMakan->alamat)) {
                $data['alamat'] = $rumahMakan->alamat;
            }

            $insert = RumahMakan::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Rumah Makan gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('rumah-makan.show', ['rumah_makan' => $insert->id]))->with('success', 'penilaian / inspeksi Rumah Makan berhasil dibuat');
        }

        $update = $rumahMakan->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Rumah Makan gagal diubah, silahkan coba lagi');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('rumah-makan.show', ['rumah_makan' => $rumahMakan['id']]))->with('success', 'form informasi dan penilaian Rumah Makan Tipe ' . $request->input('u009') . ' berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $rumahMakan = RumahMakan::where('id', $id)->withTrashed()->first();

        if ($rumahMakan['deleted_at']) {
            $rumahMakan->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $rumahMakan->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Rumah Makan berhasil dihapus');
    }
}