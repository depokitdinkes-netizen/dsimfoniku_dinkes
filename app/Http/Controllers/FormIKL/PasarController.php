<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Pasar;
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

class PasarController extends Controller {
    protected function informasiUmum() {
        return [
            Form::input('text', 'Nama Pasar', 'subjek'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Status Operasi', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian')
        ];
    }

    protected function formPenilaian() {
        return [
            Form::h(2, 'A. Lokasi', 'lokasi'),

            Form::select('Sesuai rencana umum tata ruang', 'l001', 2),
            Form::select('Tidak terletak pada daerah rawan bencana', 'l002', 2),
            Form::select('Tidak terletak pada daerah rawan kecelakaan', 'l003', 2),
            Form::select('Tidak terletak pada tempat pemrosesan akhir sampah', 'l004', 2),
            Form::select('Mempunyai batas wilayah yang jelas', 'l005', 2),

            Form::h(2, 'B. Bangunan Pasar', 'bangunan-pasar'),

            Form::h(3, 'Umum', 'umum'),

            Form::select('Bangunan dan rancang bangun sesuai dengan peraturan yang berlaku', 'u001', 2),

            Form::h(3, 'Penataan Ruang Dagang', 'penataan-ruang-dagang'),

            Form::select('Pembagian area sesuai dengan peruntukkannya (zoning)', 'prd001', 2),
            Form::select('Zoning dengan identitas lengkap', 'prd002', 2),
            Form::select('Lebar lorong antar los minimal 1,5 meter', 'prd003', 2),
            Form::select('Pestisida dan bahan berbahaya beracun terpisah dengan zona makanan dan bahan pangan', 'prd004', 2),

            Form::h(3, 'Ruang Kantor Pengelola', 'ruang-kantor-pengelola'),

            Form::select('Ventilasi minimal 20% dari luas lantai (alami), dan/atau AC/kipas angin (buatan)', 'rkp001', 2),
            Form::select('Pencahayaan minimal 100 lux (utamakan pencahayaan alam)', 'rkp002', 2),
            Form::select('Tersedia toilet dan tempat cuci tangan', 'rkp003', 2),
            Form::select('Tidak berdebu', 'rkp004', 2),

            Form::h(3, 'Tempat Penjualan Bahan Pangan dan Makanan', 'tempat-penjualan-bahan-pangan'),

            Form::h(4, '4.1 Tempat Penjualan Bahan Pangan Basah'),

            Form::select('Meja tempat penjualan: tahan karat, rata, kemiringan cukup, dan tinggi 60 cm', 'tpb001', 2),
            Form::select('Karkas daging terlindung (dikemas)', 'tpb002', 2),
            Form::select('Alas potong (talenan) mudah dibersihkan', 'tpb003', 2),
            Form::select('Tempat penyimpanan bahan pangan dengan rantai dingin (cold chain) bersuhu kurang dari 5°C', 'tpb004', 2),
            Form::select('Tersedia sarana pencucian bahan pangan dan peralatan pada setiap lapak/jongkok/kios', 'tpb005', 2),
            Form::select('Tersedia sarana cuci tangan (minimal 1 los 1 unit) dilengkapi sabun dan air mengalir', 'tpb006', 2),
            Form::select('Saluran pembuangan air limbah: tertutup tidak permanen dan air limbah mengalir dengan lancar', 'tpb007', 2),
            Form::select('Tempat sampah: terpisah (sampah basah dan kering), kedap air, dan tertutup', 'tpb008', 2),
            Form::select('Bebas dari vektor dan binatang pembawa penyakit serta tempat perindukannya', 'tpb009', 2),

            Form::h(4, '4.2 Tempat Penjualan Bahan Pangan Kering'),

            Form::select('Meja tempat penjualan: permukaan rata, mudah dibersihkan, dan tinggi minimal 60 cm', 'tpk001', 2),
            Form::select('Meja terbuat dari bahan tahan karat dan bukan dari kayu', 'tpk002', 2),
            Form::select('Tempat sampah: terpisah (basah dan kering), kedap air, dan tertutup', 'tpk003', 2),
            Form::select('Tempat cuci tangan dilengkapi sabun dan air mengalir', 'tpk004', 2),
            Form::select('Bebas dari vektor dan binatang pembawa penyakit serta tempat perindukannya', 'tpk005', 2),

            Form::h(4, '4.3 Tempat Penjualan Makanan Matang/Siap Saji'),

            Form::select('Tempat penyajian makanan mengikuti peraturan yang berlaku', 'tpm001', 2),

            Form::h(4, '4.4 Area Parkir'),

            Form::select('Ada pemisah yang jelas dengan batas wilayah pasar', 'ap001', 2),
            Form::select('Parkir mobil, motor, sepeda, andong/delman, becak, dll terpisah', 'ap002', 2),
            Form::select('Tersedia area khusus bongkar muat barang', 'ap003', 2),
            Form::select('Tidak ada genangan', 'ap004', 2),
            Form::select('Tersedia tempat sampah setiap radius 10 meter', 'ap005', 2),
            Form::select('Ada jalur dan tanda masuk dan keluar yang jelas', 'ap006', 2),
            Form::select('Ada tanaman penghijauan', 'ap007', 2),
            Form::select('Adanya area resapan air hujan', 'ap008', 2),

            Form::h(4, '4.5 Konstruksi'),

            Form::h(5, '4.5.1 Atap'),

            Form::select('Atap: kuat, tidak bocor', 'ka001', 2),
            Form::select('Kemiringan atap cukup dan tidak memungkinkan genangan air', 'ka002', 2),
            Form::select('Atap dengan ketinggian minimal 4 meter dan jika lebih 10 meter dilengkapi penangkal petir', 'ka003', 2),

            Form::h(5, '4.5.2 Dinding'),

            Form::select('Keadaan dinding: bersih dan berwarna terang', 'kd001', 2),
            Form::select('Permukaan dinding yang selalu terkena percikan air terbuat dari: bahan yang kuat dan kedap air', 'kd002', 2),
            Form::select('Pertemuan lantai dengan dinding harus lengkung (conus)', 'kd003', 2),

            Form::h(5, '4.5.3 Lantai'),

            Form::select('Keadaan lantai: kedap air, rata, tidak licin, tidak retak, dan mudah dibersihkan', 'kl001', 2),
            Form::select('Lantai kamar mandi, tempat cuci, dan sejenisnya mempunyai kemiringan cukup dan dialirkan ke saluran pembuangan air limbah', 'kl002', 2),

            Form::h(5, '4.5.4 Tangga'),

            Form::select('Tinggi maksimal anak tangga 20 cm, lebar tapak tangga 30 cm, lebar tangga minimal 1,5 m', 'kt001', 2),
            Form::select('Terdapat pegangan tangan kiri dan kanan', 'kt002', 2),
            Form::select('Kuat dan tidak licin', 'kt003', 2),
            Form::select('Pencahayaan minimal 100 lux', 'kt004', 2),

            Form::h(5, '4.5.5 Kualitas Udara dalam Ruang'),

            Form::select('Ventilasi minimal 20% dari luas lantai', 'ku001', 2),
            Form::select('Laju udara dalam ruang: 0,15–0,25 m/detik', 'ku002', 2),
            Form::select('Kebisingan tidak boleh lebih dari 85 dB (A)/8 jam', 'ku003', 2),
            Form::select('Kelembaban 40–60% Rh', 'ku004', 2),
            Form::select('Debu (PM2,5) maksimal 35 µg/m³', 'ku005', 2),

            Form::h(5, '4.5.6 Pencahayaan'),

            Form::select('Intensitas pencahayaan cukup untuk melakukan pekerjaan pengelolaan dan pembersihan bahan makanan minimal 100 lux', 'kp001', 2),

            Form::h(5, '4.5.7 Pintu'),

            Form::select('Khusus kios/los penjual daging, ikan, dan sejenisnya menggunakan pintu yang dapat membuka dan menutup sendiri atau tirai plastik untuk menghalangi binatang/serangga penular penyakit', 'kpi001', 2),

            Form::h(2, 'C. Sanitasi', 'sanitasi'),

            Form::h(3, 'Air untuk Kebutuhan Higiene Sanitasi', 'air-higiene-sanitasi'),

            Form::select('Tersedia dalam jumlah yang cukup (minimal 15 liter per orang/hari)', 'ahs001', 2),
            Form::select('Kualitas fisik memenuhi syarat kesehatan (kekeruhan)', 'ahs002', 2),
            Form::select('Jarak sumber air bersih dengan septic tank, minimal 10 meter', 'ahs003', 2),
            Form::select('Pengujian kualitas air untuk kebutuhan higiene sanitasi dilakukan 6 bulan sekali', 'ahs004', 2),
            Form::select('Memenuhi persyaratan kualitas air yang berlaku', 'ahs005', 2),

            Form::h(3, 'Kamar Mandi dan Toilet', 'kamar-mandi-toilet'),

            Form::h(4, 'Toilet'),

            Form::select('Terpisah untuk laki-laki dan perempuan', 'kmt001', 2),
            Form::select('Jumlah cukup (laki-laki = 1:40, perempuan = 1:25)', 'kmt002', 2),
            Form::select('Tersedia penampung air tidak permanen (ember) dan bebas jentik', 'kmt003', 2),
            Form::select('Toilet bersih, tidak ada genangan air, tidak ada sampah, dan tidak berbau', 'kmt004', 2),
            Form::select('Tersedia tempat cuci tangan dan sabun', 'kmt005', 2),
            Form::select('Tersedia tempat sampah yang tertutup', 'kmt006', 2),
            Form::select('Toilet dengan leher angsa dan septic tank yang memenuhi syarat kesehatan', 'kmt007', 2),
            Form::select('Letak toilet minimal 10 meter dari tempat penjualan makanan dan bahan pangan', 'kmt008', 2),
            Form::select('Ventilasi minimal 30% dari luas lantai', 'kmt009', 2),
            Form::select('Pencahayaan minimal 100 lux', 'kmt010', 2),
            Form::select('Lantai kedap air, tidak licin, mudah dibersihkan, dengan kemiringan cukup', 'kmt011', 2),

            Form::h(3, 'Pengelolaan Sampah', 'pengelolaan-sampah'),

            Form::select('Tersedia alat pengangkut sampah yang terpisah di dalam pasar: kuat dan mudah dibersihkan', 'ps001', 2),
            Form::select('Tersedia tempat pembuangan sampah sementara (TPS): kuat, kedap air, mudah dibersihkan, dan mudah dijangkau', 'ps002', 2),
            Form::select('TPS memiliki akses jalan terpisah dengan jalur utama pasar', 'ps003', 2),
            Form::select('TPS berjarak lebih dari 10 meter dari bangunan pasar', 'ps004', 2),
            Form::select('Sampah diangkut setiap hari', 'ps005', 2),
            Form::select('Pasar bersih dari sampah berserakan', 'ps006', 2),

            Form::h(3, 'Saluran Pembuangan Air Limbah', 'saluran-pembuangan-air-limbah'),

            Form::select('Tertutup, dilengkapi bak kontrol/tertutup tidak permanen', 'spal001', 2),
            Form::select('Limbah cair mengalir lancar', 'spal002', 2),
            Form::select('Tidak ada bangunan di atas saluran', 'spal003', 2),
            Form::select('Tidak ada genangan air limbah di dalam pasar', 'spal004', 2),

            Form::h(3, 'IPAL', 'ipal'),

            Form::select('Kapasitas IPAL cukup', 'ipal001', 2),
            Form::select('Pengujian kualitas limbah cair berkala setiap 6 bulan sekali dan memenuhi syarat', 'ipal002', 2),

            Form::h(3, 'Tempat Cuci Tangan', 'tempat-cuci-tangan'),

            Form::select('Tersedia minimal di pintu masuk dan keluar pasar serta toilet', 'tct001', 2),
            Form::select('Tersedia di setiap los', 'tct002', 2),
            Form::select('Dilengkapi sabun', 'tct003', 2),
            Form::select('Tersedia air bersih mengalir', 'tct004', 2),

            Form::h(3, 'Pengendalian Vektor dan Binatang Pembawa Penyakit', 'pengendalian-vektor'),

            Form::select('Los makanan siap saji dan bahan pangan harus bebas dari lalat, kecoa, dan tikus', 'pv001', 2),
            Form::select('Success trap/persentase tikus yang tertangkap <1', 'pv002', 2),
            Form::select('Indeks populasi kecoa <2 ekor per plate di titik pengukuran selama 12 jam', 'pv003', 2),
            Form::select('Indeks populasi lalat <2 ekor/fly grill pengamatan selama 5 menit (30 detik sebanyak 10 kali) pada setiap titik pengamatan', 'pv004', 2),
            Form::select('Angka bebas jentik (ABJ) jentik nyamuk Aedes >95%', 'pv005', 2),

            Form::h(3, 'Kualitas Makanan dan Bahan Pangan', 'kualitas-makanan-bahan-pangan'),

            Form::select('Tidak basi', 'kmbp001', 2),
            Form::select('Kualitas makanan siap saji (hasil pemeriksaan fisik, mikrobiologi, dan kimia) sesuai dengan peraturan', 'kmbp002', 2),
            Form::select('Makanan dalam kemasan tertutup disimpan dalam suhu 4–10°C', 'kmbp003', 2),
            Form::select('Ikan, daging, dan olahannya disimpan dalam suhu 0–4°C', 'kmbp004', 2),
            Form::select('Sayur dan buah disimpan dalam suhu 10°C, telur, susu, dan olahannya disimpan dalam suhu 5–7°C', 'kmbp005', 2),
            Form::select('Penyimpanan bahan makanan dengan jarak 15 cm dari lantai, 5 cm dari dinding, dan 60 cm dari langit-langit', 'kmbp006', 2),
            Form::select('Kebersihan peralatan makanan maksimal 100 kuman per cm² permukaan dan E.coli nol', 'kmbp007', 2),
            Form::select('Pengelola melakukan seleksi/screening makanan yang berpotensi mengandung bahan berbahaya', 'kmbp008', 2),

            Form::h(3, 'Desinfeksi Pasar', 'desinfeksi-pasar'),

            Form::select('Dilakukan secara menyeluruh sehari dalam sebulan', 'dp001', 2),
            Form::select('Bahan desinfeksi tidak mencemari lingkungan', 'dp002', 2),

            Form::h(2, 'D. Manajemen Sanitasi', 'manajemen-sanitasi'),

            Form::select('Pengelolaan sampah (petugas, SOP, lembar cek monitoring)', 'ms001', 2),
            Form::select('Air limbah, drainase, IPAL (petugas, SOP, lembar cek monitoring)', 'ms002', 2),
            Form::select('Toilet dan air (higiene dan air minum) (petugas, SOP, lembar cek monitoring)', 'ms003', 2),
            Form::select('Pembersihan pasar (petugas, SOP, lembar cek monitoring)', 'ms004', 2),

            Form::h(2, 'E. Pemberdayaan Masyarakat dan Perilaku Hidup Bersih dan Sehat', 'pemberdayaan-masyarakat'),

            Form::h(3, 'Pedagang dan Pekerja', 'pedagang-pekerja'),

            Form::select('Pedagang daging, karkas, ikan menggunakan alat pelindung diri', 'pp001', 2),
            Form::select('Berperilaku hidup bersih dan sehat (membuang sampah, membersihkan tempat sampah basah setiap selesai berjualan, CTPS, dll)', 'pp002', 2),
            Form::select('Dilakukan pemeriksaan kesehatan bagi pedagang secara berkala minimal 6 bulan sekali', 'pp003', 2),
            Form::select('Pedagang makanan siap saji tidak sedang menderita penyakit menular langsung seperti diare, hepatitis, TBC, kudis, dll', 'pp004', 2),

            Form::h(3, 'Pengunjung', 'pengunjung'),

            Form::select('Berperilaku hidup bersih dan sehat (PHBS)', 'pg001', 2),
            Form::select('Cuci tangan dengan sabun setelah memegang karkas, daging, atau ikan', 'pg002', 2),

            Form::h(3, 'Pengelola', 'pengelola-phbs'),

            Form::select('Pernah mengikuti kursus/pelatihan di bidang sanitasi dan higiene makanan dan pangan', 'pn001', 2),
            Form::select('Mempunyai rencana kerja PHBS pasar', 'pn002', 2),
            Form::select('Tindak lanjut hasil rekomendasi intervensi permasalahan kesling (laporan)', 'pn003', 2),

            Form::h(3, 'POKJA', 'pokja'),

            Form::select('Ada SK Pokja Pasar (pengelola pasar dan perwakilan pedagang dan petugas pasar)', 'pokja001', 2),
            Form::select('Ada rencana kerja (setiap tahun, ada kegiatan screening bahan pangan yang dijual di pasar)', 'pokja002', 2),
            Form::select('Adanya implementasi rencana kerja (dokumen pelaksanaan kegiatan pokja pasar)', 'pokja003', 2),
            Form::select('Melakukan kegiatan penilaian internal pasar secara rutin perbulan', 'pokja004', 2),
            Form::select('Melakukan monitoring dan evaluasi', 'pokja005', 2),

            Form::h(2, 'F. Keamanan', 'keamanan'),

            Form::h(3, 'Pemadam Kebakaran', 'pemadam-kebakaran'),

            Form::h(4, 'Peralatan pemadam kebakaran'),

            Form::select('Ada', 'pk001', 2),
            Form::select('Jumlah cukup', 'pk002', 2),
            Form::select('80% berfungsi', 'pk003', 2),
            Form::select('Tersedia hidran pilar untuk pemadam kebakaran', 'pk004', 2),
            Form::select('Letak peralatan pemadam kebakaran mudah dijangkau dan ada petunjuk arah penyelamatan/evakuasi', 'pk005', 2),
            Form::select('Adanya SOP penggunaan alat pemadam kebakaran', 'pk006', 2),

            Form::h(3, 'Keamanan', 'keamanan-umum'),

            Form::select('Ada pos keamanan', 'keu001', 2),
            Form::select('Ada personil/petugas keamanan', 'keu002', 2),

            Form::h(2, 'G. Sarana Penunjang', 'sarana-penunjang'),

            Form::select('Tersedia pos pelayanan kesehatan dan pertolongan pertama pada kecelakaan (P3K)', 'sp001', 2),
            Form::select('Tersedia akses keluar masuk barang dan orang terpisah', 'sp002', 2)
        ];
    }

    protected function formPenilaianName() {
        return [
            // A. Lokasi
            'l001', 'l002', 'l003', 'l004', 'l005',
            
            // B. Bangunan Pasar - Umum
            'u001',
            
            // Penataan Ruang Dagang
            'prd001', 'prd002', 'prd003', 'prd004',
            
            // Ruang Kantor Pengelola
            'rkp001', 'rkp002', 'rkp003', 'rkp004',
            
            // Tempat Penjualan Bahan Pangan Basah
            'tpb001', 'tpb002', 'tpb003', 'tpb004', 'tpb005', 'tpb006', 'tpb007', 'tpb008', 'tpb009',
            
            // Tempat Penjualan Bahan Pangan Kering
            'tpk001', 'tpk002', 'tpk003', 'tpk004', 'tpk005',
            
            // Tempat Penjualan Makanan Matang/Siap Saji
            'tpm001',
            
            // Area Parkir
            'ap001', 'ap002', 'ap003', 'ap004', 'ap005', 'ap006', 'ap007', 'ap008',
            
            // Konstruksi - Atap
            'ka001', 'ka002', 'ka003',
            
            // Konstruksi - Dinding
            'kd001', 'kd002', 'kd003',
            
            // Konstruksi - Lantai
            'kl001', 'kl002',
            
            // Konstruksi - Tangga
            'kt001', 'kt002', 'kt003', 'kt004',
            
            // Konstruksi - Kualitas Udara
            'ku001', 'ku002', 'ku003', 'ku004', 'ku005',
            
            // Konstruksi - Pencahayaan
            'kp001',
            
            // Konstruksi - Pintu
            'kpi001',
            
            // C. Sanitasi - Air untuk Kebutuhan Higiene Sanitasi
            'ahs001', 'ahs002', 'ahs003', 'ahs004', 'ahs005',
            
            // Kamar Mandi dan Toilet
            'kmt001', 'kmt002', 'kmt003', 'kmt004', 'kmt005', 'kmt006', 'kmt007', 'kmt008', 'kmt009', 'kmt010', 'kmt011',
            
            // Pengelolaan Sampah
            'ps001', 'ps002', 'ps003', 'ps004', 'ps005', 'ps006',
            
            // Saluran Pembuangan Air Limbah
            'spal001', 'spal002', 'spal003', 'spal004',
            
            // IPAL
            'ipal001', 'ipal002',
            
            // Tempat Cuci Tangan
            'tct001', 'tct002', 'tct003', 'tct004',
            
            // Pengendalian Vektor dan Binatang Pembawa Penyakit
            'pv001', 'pv002', 'pv003', 'pv004', 'pv005',
            
            // Kualitas Makanan dan Bahan Pangan
            'kmbp001', 'kmbp002', 'kmbp003', 'kmbp004', 'kmbp005', 'kmbp006', 'kmbp007', 'kmbp008',
            
            // Desinfeksi Pasar
            'dp001', 'dp002',
            
            // D. Manajemen Sanitasi
            'ms001', 'ms002', 'ms003', 'ms004',
            
            // E. Pemberdayaan Masyarakat - Pedagang dan Pekerja
            'pp001', 'pp002', 'pp003', 'pp004',
            
            // E. Pemberdayaan Masyarakat - Pengunjung
            'pg001', 'pg002',
            
            // E. Pemberdayaan Masyarakat - Pengelola
            'pn001', 'pn002', 'pn003',
            
            // E. Pemberdayaan Masyarakat - POKJA
            'pokja001', 'pokja002', 'pokja003', 'pokja004', 'pokja005',
            
            // F. Keamanan - Pemadam Kebakaran
            'pk001', 'pk002', 'pk003', 'pk004', 'pk005', 'pk006',
            
            // F. Keamanan - Keamanan Umum
            'keu001', 'keu002',
            
            // G. Sarana Penunjang
            'sp001', 'sp002'
        ];
    }

    protected function informasiUmumName() {
        return [
            'subjek',
            'pengelola',
            'alamat',
            'kecamatan',
            'kelurahan',
            'kontak',
            'status-operasi',
            'koordinat',
            'nama-pemeriksa',
            'instansi-pemeriksa',
            'tanggal-penilaian'
        ];
    }

    protected function hasilPengukuran()
    {
        return [
            Form::input('text', 'Catatan Tambahan Hasil Penilaian', 'catatan-lain'),
            Form::input('text', 'Rekomendasi Perbaikan', 'rencana-tindak-lanjut')
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Pasar::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Pasar',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Pasar', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)]
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']
                ])->download('BAIKL_PASAR_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return Pasar::withTrashed()->get()->map(function ($item) {
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
                            'Id', 'User ID', 'Nama Subjek', 'Nama Pengelola', 'Alamat', 'Kelurahan', 'Kecamatan',
                            'Kontak', 'Status Operasi', 'Koordinat', 'Nama Pemeriksa', 'Instansi Pemeriksa', 'Tanggal Penilaian',
                            'Skor', 'Hasil IKL', 'Catatan Lain', 'Rencana Tindak Lanjut', 'Dibuat', 'Diperbarui', 'Dihapus',
                            
                            // A. Lokasi
                            'Sesuai rencana umum tata ruang',
                            'Tidak terletak pada daerah rawan bencana',
                            'Tidak terletak pada daerah rawan kecelakaan',
                            'Tidak terletak pada tempat pemrosesan akhir sampah',
                            'Mempunyai batas wilayah yang jelas',
                            
                            // B. Bangunan Pasar - Umum
                            'Bangunan dan rancang bangun sesuai dengan peraturan yang berlaku',
                            
                            // Penataan Ruang Dagang
                            'Pembagian area sesuai dengan peruntukkannya (zoning)',
                            'Zoning dengan identitas lengkap',
                            'Lebar lorong antar los minimal 1,5 meter',
                            'Pestisida dan bahan berbahaya beracun terpisah dengan zona makanan dan bahan pangan',
                            
                            // Ruang Kantor Pengelola
                            'Ventilasi minimal 20% dari luas lantai (alami), dan/atau AC/kipas angin (buatan)',
                            'Pencahayaan minimal 100 lux (utamakan pencahayaan alam)',
                            'Tersedia toilet dan tempat cuci tangan',
                            'Tidak berdebu',
                            
                            // Tempat Penjualan Bahan Pangan Basah
                            'Meja tempat penjualan: tahan karat, rata, kemiringan cukup, dan tinggi 60 cm',
                            'Karkas daging terlindung (dikemas)',
                            'Alas potong (talenan) mudah dibersihkan',
                            'Tempat penyimpanan bahan pangan dengan rantai dingin (cold chain) bersuhu kurang dari 5°C',
                            'Tersedia sarana pencucian bahan pangan dan peralatan pada setiap lapak/jongkok/kios',
                            'Tersedia sarana cuci tangan (minimal 1 los 1 unit) dilengkapi sabun dan air mengalir',
                            'Saluran pembuangan air limbah: tertutup tidak permanen dan air limbah mengalir dengan lancar',
                            'Tempat sampah: terpisah (sampah basah dan kering), kedap air, dan tertutup',
                            'Bebas dari vektor dan binatang pembawa penyakit serta tempat perindukannya',
                            
                            // Tempat Penjualan Bahan Pangan Kering
                            'Meja tempat penjualan: permukaan rata, mudah dibersihkan, dan tinggi minimal 60 cm',
                            'Meja terbuat dari bahan tahan karat dan bukan dari kayu',
                            'Tempat sampah: terpisah (basah dan kering), kedap air, dan tertutup',
                            'Tempat cuci tangan dilengkapi sabun dan air mengalir',
                            'Bebas dari vektor dan binatang pembawa penyakit serta tempat perindukannya',
                            
                            // Tempat Penjualan Makanan Matang/Siap Saji
                            'Tempat penyajian makanan mengikuti peraturan yang berlaku',
                            
                            // Area Parkir
                            'Ada pemisah yang jelas dengan batas wilayah pasar',
                            'Parkir mobil, motor, sepeda, andong/delman, becak, dll terpisah',
                            'Tersedia area khusus bongkar muat barang',
                            'Tidak ada genangan',
                            'Tersedia tempat sampah setiap radius 10 meter',
                            'Ada jalur dan tanda masuk dan keluar yang jelas',
                            'Ada tanaman penghijauan',
                            'Adanya area resapan air hujan',
                            
                            // Konstruksi - Atap
                            'Atap: kuat, tidak bocor',
                            'Kemiringan atap cukup dan tidak memungkinkan genangan air',
                            'Atap dengan ketinggian minimal 4 meter dan jika lebih 10 meter dilengkapi penangkal petir',
                            
                            // Konstruksi - Dinding
                            'Keadaan dinding: bersih dan berwarna terang',
                            'Permukaan dinding yang selalu terkena percikan air terbuat dari: bahan yang kuat dan kedap air',
                            'Pertemuan lantai dengan dinding harus lengkung (conus)',
                            
                            // Konstruksi - Lantai
                            'Keadaan lantai: kedap air, rata, tidak licin, tidak retak, dan mudah dibersihkan',
                            'Lantai kamar mandi, tempat cuci, dan sejenisnya mempunyai kemiringan cukup dan dialirkan ke saluran pembuangan air limbah',
                            
                            // Konstruksi - Tangga
                            'Tinggi maksimal anak tangga 20 cm, lebar tapak tangga 30 cm, lebar tangga minimal 1,5 m',
                            'Terdapat pegangan tangan kiri dan kanan',
                            'Kuat dan tidak licin',
                            'Pencahayaan minimal 100 lux',
                            
                            // Konstruksi - Kualitas Udara
                            'Ventilasi minimal 20% dari luas lantai',
                            'Laju udara dalam ruang: 0,15–0,25 m/detik',
                            'Kebisingan tidak boleh lebih dari 85 dB (A)/8 jam',
                            'Kelembaban 40–60% Rh',
                            'Debu (PM2,5) maksimal 35 µg/m³',
                            
                            // Konstruksi - Pencahayaan
                            'Intensitas pencahayaan cukup untuk melakukan pekerjaan pengelolaan dan pembersihan bahan makanan minimal 100 lux',
                            
                            // Konstruksi - Pintu
                            'Khusus kios/los penjual daging, ikan, dan sejenisnya menggunakan pintu yang dapat membuka dan menutup sendiri atau tirai plastik untuk menghalangi binatang/serangga penular penyakit',
                            
                            // Air untuk Kebutuhan Higiene Sanitasi
                            'Tersedia dalam jumlah yang cukup (minimal 15 liter per orang/hari)',
                            'Kualitas fisik memenuhi syarat kesehatan (kekeruhan)',
                            'Jarak sumber air bersih dengan septic tank, minimal 10 meter',
                            'Pengujian kualitas air untuk kebutuhan higiene sanitasi dilakukan 6 bulan sekali',
                            'Memenuhi persyaratan kualitas air yang berlaku',
                            
                            // Kamar Mandi dan Toilet
                            'Terpisah untuk laki-laki dan perempuan',
                            'Jumlah cukup (laki-laki = 1:40, perempuan = 1:25)',
                            'Tersedia penampung air tidak permanen (ember) dan bebas jentik',
                            'Toilet bersih, tidak ada genangan air, tidak ada sampah, dan tidak berbau',
                            'Tersedia tempat cuci tangan dan sabun',
                            'Tersedia tempat sampah yang tertutup',
                            'Toilet dengan leher angsa dan septic tank yang memenuhi syarat kesehatan',
                            'Letak toilet minimal 10 meter dari tempat penjualan makanan dan bahan pangan',
                            'Ventilasi minimal 30% dari luas lantai',
                            'Pencahayaan minimal 100 lux',
                            'Lantai kedap air, tidak licin, mudah dibersihkan, dengan kemiringan cukup',
                            
                            // Pengelolaan Sampah
                            'Tersedia alat pengangkut sampah yang terpisah di dalam pasar: kuat dan mudah dibersihkan',
                            'Tersedia tempat pembuangan sampah sementara (TPS): kuat, kedap air, mudah dibersihkan, dan mudah dijangkau',
                            'TPS memiliki akses jalan terpisah dengan jalur utama pasar',
                            'TPS berjarak lebih dari 10 meter dari bangunan pasar',
                            'Sampah diangkut setiap hari',
                            'Pasar bersih dari sampah berserakan',
                            
                            // Saluran Pembuangan Air Limbah
                            'Tertutup, dilengkapi bak kontrol/tertutup tidak permanen',
                            'Limbah cair mengalir lancar',
                            'Tidak ada bangunan di atas saluran',
                            'Tidak ada genangan air limbah di dalam pasar',
                            
                            // IPAL
                            'Kapasitas IPAL cukup',
                            'Pengujian kualitas limbah cair berkala setiap 6 bulan sekali dan memenuhi syarat',
                            
                            // Tempat Cuci Tangan
                            'Tersedia minimal di pintu masuk dan keluar pasar serta toilet',
                            'Tersedia di setiap los',
                            'Dilengkapi sabun',
                            'Tersedia air bersih mengalir',
                            
                            // Pengendalian Vektor dan Binatang Pembawa Penyakit
                            'Los makanan siap saji dan bahan pangan harus bebas dari lalat, kecoa, dan tikus',
                            'Success trap/persentase tikus yang tertangkap <1',
                            'Indeks populasi kecoa <2 ekor per plate di titik pengukuran selama 12 jam',
                            'Indeks populasi lalat <2 ekor/fly grill pengamatan selama 5 menit (30 detik sebanyak 10 kali) pada setiap titik pengamatan',
                            'Angka bebas jentik (ABJ) jentik nyamuk Aedes >95%',
                            
                            // Kualitas Makanan dan Bahan Pangan
                            'Tidak basi',
                            'Kualitas makanan siap saji (hasil pemeriksaan fisik, mikrobiologi, dan kimia) sesuai dengan peraturan',
                            'Makanan dalam kemasan tertutup disimpan dalam suhu 4–10°C',
                            'Ikan, daging, dan olahannya disimpan dalam suhu 0–4°C',
                            'Sayur dan buah disimpan dalam suhu 10°C, telur, susu, dan olahannya disimpan dalam suhu 5–7°C',
                            'Penyimpanan bahan makanan dengan jarak 15 cm dari lantai, 5 cm dari dinding, dan 60 cm dari langit-langit',
                            'Kebersihan peralatan makanan maksimal 100 kuman per cm² permukaan dan E.coli nol',
                            'Pengelola melakukan seleksi/screening makanan yang berpotensi mengandung bahan berbahaya',
                            
                            // Desinfeksi Pasar
                            'Dilakukan secara menyeluruh sehari dalam sebulan',
                            'Bahan desinfeksi tidak mencemari lingkungan',
                            
                            // Manajemen Sanitasi
                            'Pengelolaan sampah (petugas, SOP, lembar cek monitoring)',
                            'Air limbah, drainase, IPAL (petugas, SOP, lembar cek monitoring)',
                            'Toilet dan air (higiene dan air minum) (petugas, SOP, lembar cek monitoring)',
                            'Pembersihan pasar (petugas, SOP, lembar cek monitoring)',
                            
                            // Pedagang dan Pekerja
                            'Pedagang daging, karkas, ikan menggunakan alat pelindung diri',
                            'Berperilaku hidup bersih dan sehat (membuang sampah, membersihkan tempat sampah basah setiap selesai berjualan, CTPS, dll)',
                            'Dilakukan pemeriksaan kesehatan bagi pedagang secara berkala minimal 6 bulan sekali',
                            'Pedagang makanan siap saji tidak sedang menderita penyakit menular langsung seperti diare, hepatitis, TBC, kudis, dll',
                            
                            // Pengunjung
                            'Berperilaku hidup bersih dan sehat (PHBS)',
                            'Cuci tangan dengan sabun setelah memegang karkas, daging, atau ikan',
                            
                            // Pengelola
                            'Pernah mengikuti kursus/pelatihan di bidang sanitasi dan higiene makanan dan pangan',
                            'Mempunyai rencana kerja PHBS pasar',
                            'Tindak lanjut hasil rekomendasi intervensi permasalahan kesling (laporan)',
                            
                            // POKJA
                            'Ada SK Pokja Pasar (pengelola pasar dan perwakilan pedagang dan petugas pasar)',
                            'Ada rencana kerja (setiap tahun, ada kegiatan screening bahan pangan yang dijual di pasar)',
                            'Adanya implementasi rencana kerja (dokumen pelaksanaan kegiatan pokja pasar)',
                            'Melakukan kegiatan penilaian internal pasar secara rutin perbulan',
                            'Melakukan monitoring dan evaluasi',
                            
                            // Peralatan pemadam kebakaran
                            'Ada',
                            'Jumlah cukup',
                            '80% berfungsi',
                            'Tersedia hidran pilar untuk pemadam kebakaran',
                            'Letak peralatan pemadam kebakaran mudah dijangkau dan ada petunjuk arah penyelamatan/evakuasi',
                            'Adanya SOP penggunaan alat pemadam kebakaran',
                            
                            // Keamanan
                            'Ada pos keamanan',
                            'Ada personil/petugas keamanan',
                            
                            // Sarana Penunjang
                            'Tersedia pos pelayanan kesehatan dan pertolongan pertama pada kecelakaan (P3K)',
                            'Tersedia akses keluar masuk barang dan orang terpisah'
                        ];
                    }
                }, 'REPORT_PASAR_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.pasar.create', [
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
            Log::info('PasarController store called', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'request_data' => $request->except(['_token'])
            ]);

            $data = $request->all();
            
            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // Handle duplicate action BEFORE validation
            if ($request->input('action') == 'duplicate' && $request->input('original_id')) {
                $original = Pasar::find($request->input('original_id'));
                
                if ($original) {
                    Log::info('Duplicate action - original data', [
                        'original_id' => $original->id,
                        'original_status_operasi' => $original->{'status-operasi'},
                        'original_nama_pemeriksa' => $original->{'nama-pemeriksa'},
                        'original_instansi_pemeriksa' => $original->{'instansi-pemeriksa'},
                        'original_tanggal_penilaian' => $original->{'tanggal-penilaian'}
                    ]);
                    
                    Log::info('Duplicate action - received data', [
                        'received_status_operasi' => $data['status-operasi'] ?? 'NOT_SET',
                        'received_nama_pemeriksa' => $data['nama-pemeriksa'] ?? 'NOT_SET',
                        'received_instansi_pemeriksa' => $data['instansi-pemeriksa'] ?? 'NOT_SET',
                        'received_tanggal_penilaian' => $data['tanggal-penilaian'] ?? 'NOT_SET'
                    ]);
                    
                    // Create fallback data with original values for required fields
                    $fallbackData = array_merge($data, [
                        'kelurahan' => (!empty($data['kelurahan']) && $data['kelurahan'] !== '') ? $data['kelurahan'] : $original->kelurahan,
                        'kecamatan' => (!empty($data['kecamatan']) && $data['kecamatan'] !== '') ? $data['kecamatan'] : $original->kecamatan,
                        'subjek' => (!empty($data['subjek']) && $data['subjek'] !== '') ? $data['subjek'] : $original->subjek,
                        'alamat' => (!empty($data['alamat']) && $data['alamat'] !== '') ? $data['alamat'] : $original->alamat,
                        'pengelola' => (!empty($data['pengelola']) && $data['pengelola'] !== '') ? $data['pengelola'] : $original->pengelola,
                        'kontak' => (!empty($data['kontak']) && $data['kontak'] !== '') ? $data['kontak'] : $original->kontak,
                        'koordinat' => (!empty($data['koordinat']) && $data['koordinat'] !== '') ? $data['koordinat'] : $original->koordinat,
                        'status-operasi' => (!empty($data['status-operasi']) && $data['status-operasi'] !== '') ? $data['status-operasi'] : $original->{'status-operasi'},
                        'nama-pemeriksa' => (!empty($data['nama-pemeriksa']) && $data['nama-pemeriksa'] !== '') ? $data['nama-pemeriksa'] : $original->{'nama-pemeriksa'},
                        'instansi-pemeriksa' => (!empty($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] !== '') ? $data['instansi-pemeriksa'] : $original->{'instansi-pemeriksa'},
                        'tanggal-penilaian' => (!empty($data['tanggal-penilaian']) && $data['tanggal-penilaian'] !== '') ? $data['tanggal-penilaian'] : ($original->{'tanggal-penilaian'} ? $original->{'tanggal-penilaian'}->format('Y-m-d') : null),
                        'catatan-lain' => (!empty($data['catatan-lain']) && $data['catatan-lain'] !== '') ? $data['catatan-lain'] : $original->{'catatan-lain'},
                        'rencana-tindak-lanjut' => (!empty($data['rencana-tindak-lanjut']) && $data['rencana-tindak-lanjut'] !== '') ? $data['rencana-tindak-lanjut'] : $original->{'rencana-tindak-lanjut'},
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    Log::info('Duplicate action - fallback data applied', [
                        'fallback_status_operasi' => $fallbackData['status-operasi'] ?? 'NOT_SET',
                        'fallback_nama_pemeriksa' => $fallbackData['nama-pemeriksa'] ?? 'NOT_SET',
                        'fallback_instansi_pemeriksa' => $fallbackData['instansi-pemeriksa'] ?? 'NOT_SET',
                        'fallback_tanggal_penilaian' => $fallbackData['tanggal-penilaian'] ?? 'NOT_SET'
                    ]);
                    
                    // Remove duplicate action fields
                    unset($fallbackData['action'], $fallbackData['original_id']);
                    $data = $fallbackData;
                    
                    // Add form penilaian scores with original values
                    foreach ($this->formPenilaianName() as $column) {
                        $data[$column] = !empty($data[$column]) ? $data[$column] : ($original->{$column} ?? '0');
                    }
                    
                    Log::info('Duplicate action - form penilaian data copied', [
                        'sample_columns' => [
                            'l001' => $data['l001'] ?? 'NOT_SET',
                            'l002' => $data['l002'] ?? 'NOT_SET',
                            'u001' => $data['u001'] ?? 'NOT_SET'
                        ]
                    ]);
                    
                    // Update request data for validation
                    $request->merge($data);
                }
            }

            // Validasi input
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:20',
                'koordinat' => 'required|string|max:255',
                'status-operasi' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
            ]);

            Log::info('Validation passed for Pasar store');
            
            // Set user_id: 3 for guest, actual user_id for logged users
            $data['user_id'] = Auth::check() ? Auth::id() : 3;

            // Only process form penilaian if not already processed in duplicate action
            if ($request->input('action') !== 'duplicate') {
                foreach ($this->formPenilaianName() as $column) {
                    $data[$column] = $request->input($column, '0');
                }
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), function($carry, $column) use ($data) {
                // For pasar scoring: "Sesuai" = 0 in form but should count as 1 point
                // "Tidak Sesuai" = 2 in form but should count as 0 point
                $value = $data[$column] ?? 0;
                return $carry + ($value == 0 ? 1 : 0); // 1 point for "Sesuai" (value=0), 0 point for "Tidak Sesuai" (value=2)
            }, 0) / count($this->formPenilaianName()) * 100);

            Log::info('Attempting to create Pasar record', ['data_keys' => array_keys($data)]);

            $insert = Pasar::create($data);

            if (!$insert) {
                Log::error('Failed to create Pasar record - insert returned false', [
                    'user_id' => Auth::check() ? Auth::id() : 3,
                    'data' => $data
                ]);
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Pasar gagal dibuat, silahkan coba lagi');
            }

            Log::info('Pasar record created successfully', ['id' => $insert->id]);

            $message = $request->input('action') == 'duplicate' ? 'duplikat penilaian / inspeksi Pasar berhasil dibuat' : 'penilaian / inspeksi Pasar berhasil dibuat';
            return redirect(route('pasar.show', ['pasar' => $insert->id]))->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in Pasar store', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali form Anda.');
        } catch (\Exception $e) {
            Log::error('Exception occurred in Pasar store', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data penilaian Pasar. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasar $pasar)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $pasar,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Pasar',
            'edit_route' => route('pasar.edit', ['pasar' => $pasar['id']]),
            'destroy_route' => route('pasar.destroy', ['pasar' => $pasar['id']]),
            'export_route' => route('pasar.index', ['export' => 'pdf', 'id' => $pasar['id']])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasar $pasar)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to access Pasar edit form', ['pasar_id' => $pasar->id]);
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return view('pages.inspection.pasar.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'hasil_pengukuran' => $this->hasilPengukuran(),
            'form_data' => $pasar
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasar $pasar)
    {
        try {
            Log::info('PasarController update called', [
                'pasar_id' => $pasar->id,
                'user_id' => Auth::check() ? Auth::id() : 3,
                'action' => $request->input('action'),
                'request_data' => $request->except(['_token'])
            ]);
            
            // Validasi input
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:20',
                'koordinat' => 'required|string|max:255',
                'status-operasi' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
            ]);
            
            Log::info('Validation passed for Pasar update');

            $data = $request->all();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            if ($data['action'] == 'duplicate') {
                // Add auth user ID only for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if ((empty($data['kelurahan']) || $data['kelurahan'] === '') && !empty($pasar->kelurahan)) {
                    $data['kelurahan'] = $pasar->kelurahan;
                }
                if ((empty($data['kecamatan']) || $data['kecamatan'] === '') && !empty($pasar->kecamatan)) {
                    $data['kecamatan'] = $pasar->kecamatan;
                }
                if ((empty($data['subjek']) || $data['subjek'] === '') && !empty($pasar->subjek)) {
                    $data['subjek'] = $pasar->subjek;
                }
                if ((empty($data['alamat']) || $data['alamat'] === '') && !empty($pasar->alamat)) {
                    $data['alamat'] = $pasar->alamat;
                }
                if ((empty($data['pengelola']) || $data['pengelola'] === '') && !empty($pasar->pengelola)) {
                    $data['pengelola'] = $pasar->pengelola;
                }
                if ((empty($data['kontak']) || $data['kontak'] === '') && !empty($pasar->kontak)) {
                    $data['kontak'] = $pasar->kontak;
                }
                if ((empty($data['koordinat']) || $data['koordinat'] === '') && !empty($pasar->koordinat)) {
                    $data['koordinat'] = $pasar->koordinat;
                }
                if ((empty($data['status-operasi']) || $data['status-operasi'] === '') && !empty($pasar->{'status-operasi'})) {
                    $data['status-operasi'] = $pasar->{'status-operasi'};
                }
                if ((empty($data['nama-pemeriksa']) || $data['nama-pemeriksa'] === '') && !empty($pasar->{'nama-pemeriksa'})) {
                    $data['nama-pemeriksa'] = $pasar->{'nama-pemeriksa'};
                }
                if ((empty($data['instansi-pemeriksa']) || $data['instansi-pemeriksa'] === '') && !empty($pasar->{'instansi-pemeriksa'})) {
                    $data['instansi-pemeriksa'] = $pasar->{'instansi-pemeriksa'};
                }
                if ((empty($data['tanggal-penilaian']) || $data['tanggal-penilaian'] === '') && !empty($pasar->{'tanggal-penilaian'})) {
                    $data['tanggal-penilaian'] = $pasar->{'tanggal-penilaian'}->format('Y-m-d');
                }
                if ((empty($data['catatan-lain']) || $data['catatan-lain'] === '') && !empty($pasar->{'catatan-lain'})) {
                    $data['catatan-lain'] = $pasar->{'catatan-lain'};
                }
                if ((empty($data['rencana-tindak-lanjut']) || $data['rencana-tindak-lanjut'] === '') && !empty($pasar->{'rencana-tindak-lanjut'})) {
                    $data['rencana-tindak-lanjut'] = $pasar->{'rencana-tindak-lanjut'};
                }

                // Add form penilaian scores with original values for duplicate
                foreach ($this->formPenilaianName() as $column) {
                    if (empty($data[$column]) || $data[$column] === '') {
                        $data[$column] = $pasar->{$column} ?? '0';
                    }
                }

                // Calculate score after all data is processed
                $data['skor'] = (int) (array_reduce($this->formPenilaianName(), function($carry, $column) use ($data) {
                    // For pasar scoring: "Sesuai" = 0 in form but should count as 1 point
                    // "Tidak Sesuai" = 2 in form but should count as 0 point
                    $value = $data[$column] ?? 0;
                    return $carry + ($value == 0 ? 1 : 0); // 1 point for "Sesuai" (value=0), 0 point for "Tidak Sesuai" (value=2)
                }, 0) / count($this->formPenilaianName()) * 100);

                Log::info('Attempting to duplicate Pasar record', ['original_id' => $pasar->id]);

                $insert = Pasar::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Pasar record', [
                        'original_id' => $pasar->id,
                        'user_id' => Auth::id(),
                        'data' => $data
                    ]);
                    return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Pasar gagal dibuat, silahkan coba lagi');
                }

                Log::info('Pasar record duplicated successfully', ['new_id' => $insert->id]);
                return redirect(route('pasar.show', ['pasar' => $insert->id]))->with('success', 'penilaian / inspeksi Pasar berhasil dibuat');
            }

            // Calculate score for non-duplicate updates
            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), function($carry, $column) use ($data) {
                // For pasar scoring: "Sesuai" = 0 in form but should count as 1 point
                // "Tidak Sesuai" = 2 in form but should count as 0 point
                $value = $data[$column] ?? 0;
                return $carry + ($value == 0 ? 1 : 0); // 1 point for "Sesuai" (value=0), 0 point for "Tidak Sesuai" (value=2)
            }, 0) / count($this->formPenilaianName()) * 100);

            // Remove user_id from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            Log::info('Attempting to update Pasar record', [
                'pasar_id' => $pasar->id,
                'data_keys' => array_keys($data)
            ]);
            
            $update = $pasar->update($data);

            Log::info('Update result', ['success' => $update, 'pasar_id' => $pasar->id]);

            if (!$update) {
                Log::error('Update failed for pasar', [
                    'pasar_id' => $pasar->id,
                    'user_id' => Auth::id(),
                    'data' => $data
                ]);
                return redirect()->back()->with('error', 'form informasi dan penilaian Pasar gagal diubah');
            }

            Log::info('Update successful, redirecting', ['pasar_id' => $pasar->id]);
            return redirect(route('pasar.show', ['pasar' => $pasar['id']]))->with('success', 'form informasi dan penilaian Pasar berhasil diubah');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in Pasar update', [
                'pasar_id' => $pasar->id,
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali form Anda.');
        } catch (\Exception $e) {
            Log::error('Exception occurred in Pasar update', [
                'pasar_id' => $pasar->id,
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengubah data penilaian Pasar. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to destroy Pasar data', ['pasar_id' => $id]);
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $pasar = Pasar::where('id', $id)->withTrashed()->first();

        if ($pasar['deleted_at']) {
            $pasar->update(['deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $pasar->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Pasar berhasil dihapus');
    }
}