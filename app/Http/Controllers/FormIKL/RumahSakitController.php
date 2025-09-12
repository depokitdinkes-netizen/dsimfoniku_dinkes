<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\RumahSakit;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class RumahSakitController extends Controller
{

    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Rumah Sakit', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            [
                'type' => 'select',
                'label' => 'Kelas Rumah Sakit',
                'name' => 'kelas',
                'option' => [
                    ['label' => 'Kelas A', 'value' => 'A'],
                    ['label' => 'Kelas B', 'value' => 'B'],
                    ['label' => 'Kelas C', 'value' => 'C'],
                    ['label' => 'Kelas D', 'value' => 'D']
                ],
            ],
            Form::input('number', 'Jumlah Tempat Tidur', 'jumlah-tempat-tidur'),
            Form::input('text', 'Nama Penanggung Jawab Kesehatan Lingkungan Rumah Sakit', 'pengelola'),
            Form::input('number', 'Kontak Penanggung Jawab Kesehatan Lingkungan Rumah Sakit', 'kontak'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::selects('status-operasi', 1, 'Status Operasi', [
                Form::option(1, 'Aktif Operasi'),
                Form::option(0, 'Tidak Aktif Operasi')
            ]),
            Form::input('text', 'Titik GPS', 'koordinat'),
            
            // Pertanyaan tambahan untuk dokumen dan sistem
            Form::selects('dokumen-rintek-tps-b3', 1, 'Apakah Memiliki Dokumen Rintek TPS B3?', [
                Form::option('Tidak', 'Tidak'),
                Form::option('Ya', 'Ya')
            ]),
            Form::input('text', 'No Dokumen Rintek TPS B3 (Jika Ya)', 'nomor-dokumen-rintek-tps-b3'),
            Form::selects('dokumen-pertek-ipal', 1, 'Apakah Memiliki Dokumen Pertek IPAL?', [
                Form::option('Tidak', 'Tidak'),
                Form::option('Ya', 'Ya')
            ]),
            Form::input('text', 'No Dokumen Pertek IPAL (Jika Ya)', 'nomor-dokumen-pertek-ipal'),
            Form::selects('pengisian-sikelim', 1, 'Apakah Melakukan Pengisian SIKELIM Setiap Bulan?', [
                Form::option('Tidak', 'Tidak'),
                Form::option('Ya', 'Ya')
            ]),
            Form::input('text', 'Alasan Tidak Mengisi SIKELIM (Jika Tidak)', 'alasan-sikelim'),
            Form::selects('pengisian-dsmiling', 1, 'Apakah Melakukan Pengisian DSMILING Setiap Bulan?', [
                Form::option('Tidak', 'Tidak'),
                Form::option('Ya', 'Ya')
            ]),
            Form::input('text', 'Alasan Tidak Mengisi DSMILING (Jika Tidak)', 'alasan-dsmiling')
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'I. KESEHATAN AIR RUMAH SAKIT (Bobot: 14)'),
            Form::selects('f1001', 4, 'Kuantitas Air Minum (Bobot: 4)', [
                Form::option(100, 'Memenuhi 5 L/TT/hari'),
                Form::option(50, '< 5 L/TT/hari'),
                Form::option(0, 'Tidak memenuhi')]),
            Form::selectc('1002a', 4, 100, 'RS kelas A dan B di ruang rawat inap 400 - 450 liter/TT/hari atau RS kelas C dan D di ruang rawat inap 200 - 300 liter/TT/hari (Bobot: 4)'),
            Form::selectc('1002b', 4, 100, 'Di unit rawat jalan semua kelas rumah sakit 5 L/orang/hari (Bobot: 4)'),
            Form::selectc('1002c', 4, 25, 'Tidak memenuhi persyaratan kuantitas air keperluan higiene dan sanitasi (Bobot: 4)'),
            Form::selects('f1003', 3, 'Kualitas Air Minum (Bobot: 3)', [
                Form::option(100, 'Memenuhi syarat (fisik, mikrobiologi, kimia, radioaktivitas)'),
                Form::option(50, 'Sebagian memenuhi'),
                Form::option(0, 'Tidak memenuhi')]),
            Form::selects('f1004', 3, 'Kualitas Air untuk Keperluan Higiene dan Sanitasi (Bobot: 3)', [
                Form::option(100, 'Memenuhi syarat (fisik, mikrobiologi, kimia, radioaktivitas)'),
                Form::option(50, 'Sebagian memenuhi'),
                Form::option(0, 'Tidak memenuhi')]),
            Form::selects('f1005', 0, 'Apakah melakukan pemeriksaan air hemodialisa?', [
                Form::option('Ya', 'Ya'),
                Form::option('Tidak', 'Tidak')
            ]),
            Form::selects('f1006', 0, 'Apakah melakukan pemeriksaan endotoksin Air Hemodialisa setiap bulan?', [
                Form::option('Ya', 'Ya'),
                Form::option('Tidak', 'Tidak'),
                Form::option('Lainnya', 'Lainnya') // Value 'Lainnya' penting untuk logika JS & Controller
            ]),
            Form::selects('f1007', 0, 'Apakah hasil endotoksin memenuhi 0,25 IU/ml', [
                Form::option('Ya', 'Ya'),
                Form::option('Tidak', 'Tidak'),
                Form::option('Lainnya', 'Lainnya')
            ]),
            Form::h(2, 'II. KESEHATAN UDARA RUMAH SAKIT (Bobot: 10)'),
            Form::h(3, 'Standar Baku Mutu Mikrobiologi Udara (Bobot: 2)'),
            Form::selectc('2001a', 2, 50, 'Ruang operasi kosong: 35 CFU/m³'),
            Form::selectc('2001b', 2, 50, 'Ruang operasi ada aktivitas: 180 CFU/m³'),
            Form::selects('f2002', 2, 'Kelembaban Udara (Bobot: 2)', [
                Form::option(100, 'Semua ruangan memenuhi (40–60%)'),
                Form::option(50, 'Sebagian ruangan')]),
            Form::h(3, 'Pencahayaan (Bobot: 2)'),
            Form::selectc('2003a', 2, 10, 'Ruang pasien (tidak tidur: 100 lux; tidur: 50 lux)'),
            Form::selectc('2003b', 2, 10, 'Rawat jalan (100 lux)'),
            Form::selectc('2003c', 2, 10, 'IGD (100 lux, meja tindakan + lampu sorot)'),
            Form::selectc('2003d', 2, 10, 'Operasi umum (300–500 lux)'),
            Form::selectc('2003e', 2, 10, 'Meja operasi (10,000–20,000 lux)'),
            Form::selectc('2003f', 2, 10, 'Anestesi pemulihan (300–500 lux)'),
            Form::selectc('2003g', 2, 10, 'Endoskopi, lab (75–100 lux)'),
            Form::selectc('2003h', 2, 10, 'Sinar X (minimal 60 lux)'),
            Form::selectc('2003i', 2, 5, 'Koridor (minimal 100 lux)'),
            Form::selectc('2003j', 2, 5, 'Tangga (minimal 100 lux)'),
            Form::selectc('2003k', 2, 10, 'Administrasi/kantor (minimal100 lux)'),
            Form::h(3, 'Kebisingan (Bobot: 2)'),
            Form::selectc('2004a', 2, 15, 'Ruang pasien (tidak tidur: 65 dBA; tidur: 55 dBA)'),
            Form::selectc('2004b', 2, 10, 'Operasi umum (65 dBA)'),
            Form::selectc('2004c', 2, 5, 'Ruang umum (65 dBA)'),
            Form::selectc('2004d', 2, 5, 'Anestesi pemulihan (65 dBA)'),
            Form::selectc('2004e', 2, 5, 'Endoskopi, lab (65 dBA)'),
            Form::selectc('2004f', 2, 5, 'Sinar X (65 dBA)'),
            Form::selectc('2004g', 2, 5, 'Koridor (65 dBA)'),
            Form::selectc('2004h', 2, 5, 'Tangga (65 dBA)'),
            Form::selectc('2004i', 2, 5, 'Kantor/lobby (65 dBA)'),
            Form::selectc('2004j', 2, 5, 'Ruang alat/gudang (65 dBA)'),
            Form::selectc('2004k', 2, 5, 'Farmasi (65 dBA)'),
            Form::selectc('2004l', 2, 5, 'Ruang cuci (80 dBA)'),
            Form::selectc('2004m', 2, 10, 'Ruang isolasi (55 dBA)'),
            Form::selectc('2004n', 2, 5, 'Ruang poligigi (65 dBA)'),
            Form::selectc('2004o', 2, 5, 'Ruang ICU (65 dBA)'),
            Form::selectc('2004p', 2, 5, 'Ambulans (85 dBA)'),
            Form::h(3, 'Kualitas Udara (Bobot: 2)'),
            Form::selectc('2005a', 2, 10, 'Karbon monoksida maks. 10,000 µg/m³'),
            Form::selectc('2005b', 2, 10, 'Karbondioksida maks. 1 ppm'),
            Form::selectc('2005c', 2, 10, 'Timbal maks. 0.5 µg/m³'),
            Form::selectc('2005d', 2, 10, 'Nitrogen dioksida maks. 200 µg/m³'),
            Form::selectc('2005e', 2, 10, 'Sulfur dioksida maks. 125 µg/m³'),
            Form::selectc('2005f', 2, 10, 'Formaldehida maks. 100 µg/m³'),
            Form::selectc('2005g', 2, 10, 'Total senyawa organik yang mudah menguap (T.VOC) maks. 3 ppm'),
            Form::selectc('2005h', 2, 15, 'Tidak berbau (bebas H₂S, amonia)'),
            Form::selectc('2005i', 2, 15, 'Kadar debu (diameter <10 mikron atau tidak melebihi 150 μg/m3 dan tidak mengandung debu asbes, diameter<2,5 mikron atau tidak melebihi 25 µg/m3)'),
            Form::input('text', 'Hasil Pengukuran PM 2,5 µg/m³', 'hasil_pengukuran_pm25'),
            Form::selects('pengelolaan_pangan_oleh', 0, 'Pengelolaan Pangan oleh', [
                Form::option('Internal RS', 'Internal RS'),
                Form::option('Pihak Ketiga', 'Pihak Ketiga')
            ]),
            Form::h(2, 'III. KESEHATAN PANGAN SIAP SAJI RUMAH SAKIT (Bobot: 10)'),
            Form::selects('f3001', 5, 'Standar Mutu Pangan Siap Saji (Bobot: 5)', [
                Form::option(100, 'Sertifikat jasa boga golongan B'),
                Form::option(0, 'Tidak memiliki')]),
            Form::selects('f3002', 5, 'Hasil IKL Jasa Boga B (Bobot: 5)', [
                Form::option(100, 'Ya'),
                Form::option(0, 'Tidak')]),
            Form::h(2, 'IV. KESEHATAN SARANA DAN BANGUNAN (Bobot: 10)'),
            Form::selects('f4001', 2, 'Toilet Pengunjung (Bobot: 2)', [
                Form::option(100, 'Wanita 1:20, pria 1:30'),
                Form::option(50, 'Tidak sesuai')]),
            Form::selects('f4002', 2, 'Toilet Disabilitas (Bobot: 2)', [
                Form::option(100, 'Tersedia di rawat jalan, penunjang medik, IGD'),
                Form::option(0, 'Tidak')]),
            Form::h(3, 'Lantai Rumah Sakit (Bobot: 2)'),
            Form::selectc('4003a', 2, 25, 'Permukaan rata, tidak licin, warna terang, mudah dibersihkan'),
            Form::selectc('4003b', 2, 25, 'Kemiringan cukup ke saluran pembuangan'),
            Form::selectc('4003c', 2, 25, 'Pertemuan lantai dengan dinding harus berbentuk Konus atau lengkung agar mudah dibersihkan'),
            Form::selectc('4003d', 2, 25, 'Permukaan dinding harus kuat rata, berwarna terang dan menggunakan cat yang tidak luntur serta tidak menggunakan cat yang mengandung logam berat'),
            Form::h(3, 'Pintu Rumah Sakit (Bobot: 2)'),
            Form::selectc('4004a', 2, 20, 'Pintu utama dan pintu-pintu yang dilalui brankar/tempat tidur pasien memiliki lebar bukaan minimal 120 cm, dan pintu-pintu yang tidak menjadi akses tempat tidur pasien memiliki lebar bukaan minimal 90 cm'),
            Form::selectc('4004b', 2, 20, 'Di daerah sekitar pintu masuk tidak boleh ada perbedaan ketinggian lantai'),
            Form::selectc('4004c', 2, 15, 'Pintu untuk kamar mandi di ruangan perawatan pasien dan pintu toilet untuk aksesibel, harus terbuka ke luar, dan lebar'),
            Form::selectc('4004d', 2, 15, 'Pintu-pintu yang menjadi akses tempat tidur pasien harus dilapisi bahan anti benturan.'),
            Form::selectc('4004e', 2, 15, 'Ruang perawatan pasien harus memiliki bukaan jendela yang dapat terbuka secara maksimal untuk kepentingan pertukaran udara.'),
            Form::selectc('4004f', 2, 15, 'Pada bangunan rumah sakit bertingkat, lebar bukaan jendela harus aman dari kemungkinan pasien dapat melarikan/meloloskan diri.'),
            Form::selects('f4005', 1, 'Atap Rumah Sakit (Bobot: 1)', [
                Form::option(100, 'Kuat, tidak bocor, tahan lama, tidak jadi sarang'),
                Form::option(50, 'Memenuhi sebagian'),
                Form::option(0, 'Tidak Memenuhi')]),
            Form::h(3, 'Langit-langit Rumah Sakit (Bobot: 2)'),
            Form::selectc('4006a', 2, 40, 'Kuat, warna terang, mudah dibersihkan, tidak berjamur'),
            Form::selectc('4006b', 2, 40, 'Tinggi langit-langit diruangan minimal 2,80 m, dan tinggi di selasa (koridor) minimal 2,40 m.'),
            Form::selectc('4006c', 2, 40, 'Ruang operasi minimal 3 m'),
            Form::selectc('4006d', 2, 40, 'Pada ruang operasi dan ruang perawatan intensif, bahan langit-langit harus memiliki tingkat ketahanan api (TKA) minimal 2 jam'),
            Form::selectc('4006e', 2, 40, 'Pada tempat-tempat yang membutuhkan tingkat kebersihan ruangan tertentu, maka lampu-lampu penerangan ruangan dipasang dibenamkan pada plafon (recessed).'),
            Form::h(2, 'V. PENGENDALIAN VEKTOR DAN BINATANG PEMBAWA PENYAKIT (Bobot: 10)'),
            Form::h(3, 'Angka Kepadatan Vektor (Bobot: 5)'),
            Form::input('text', 'Nama Pest Control', 'nama_pest_control'),
            Form::input('text', 'Nomor Izin dan Tahun Perijinan Pest Control', 'nomor_izin_tahun_pest_control'),
            Form::selectc('5001a', 5, 10, 'Nyamuk Anopheles sp. MBR (Man biting rate) <0,025'),
            Form::selectc('5001b', 5, 10, 'Larva Anopheles sp. indeks habitat <1'),
            Form::selectc('5001c', 5, 10, 'Nyamuk Aedes aegypti dan/atau Aedes albopictus Angka Istirahat (Resting rate) <0,025'),
            Form::selectc('5001d', 5, 10, 'Larva Aedes aegypti/albopictus ABJ minimal 95'),
            Form::selectc('5001e', 5, 10, 'Nyamuk Culex sp. MHD (Man Hour Density) <1'),
            Form::selectc('5001f', 5, 10, 'Larva Culex sp. indeks habitat <5'),
            Form::selectc('5001g', 5, 10, 'Mansonia sp. MHD <5'),
            Form::selectc('5001h', 5, 10, 'Pinjal indeks khusus <1'),
            Form::selectc('5001i', 5, 10, 'Lalat indeks populasi <2'),
            Form::selectc('5001j', 5, 10, 'Kecoa indeks populasi <2'),
            Form::selects('f5002', 5, 'Angka Kepadatan Binatang Pembawa Penyakit (Bobot: 5)', [
                Form::option(100, 'Tikus success trap <1'),
                Form::option(0, 'Tikus success trap >1')]),
            Form::h(2, 'VI. PENGAMANAN LIMBAH (Bobot: 16)'),
            Form::h(3, 'Limbah Padat Domestik (Bobot: 5)'),
            Form::selectc('6001a', 5, 40, 'Penanganan limbah dengan 3R'),
            Form::selectc('6001b', 5, 30, 'Memiliki TPS limbah domestik'),
            Form::selectc('6001c', 5, 30, 'Pengangkutan TPS maks. 2x24 jam'),
            Form::h(3, 'Limbah Domestik'), // Judul baru untuk pertanyaan deskriptif
            Form::selects('6001d', 0, 'Melakukan penanganan limbah dengan 3R*', [
                Form::option('Ya', 'Ya'),
                Form::option('Tidak', 'Tidak')
            ]),
            Form::selects('6001e', 0, 'Pemilahan Limbah Domestik yang dilakukan*', [
                Form::option('Organik, Anorganik Recycle dan Anorganik Residu', 'Organik, Anorganik Recycle dan Anorganik Residu'),
                Form::option('Organik dan Anorganik', 'Organik dan Anorganik'),
                Form::option('Anorganik Recycle', 'Anorganik Recycle'),
                Form::option('Lainnya', 'Lainnya')
            ]),
            Form::selects('6001f', 0, 'Memiliki TPS limbah domestik*', [
                Form::option('Ya', 'Ya'),
                Form::option('Tidak', 'Tidak')
            ]),
            Form::h(3, 'Limbah Padat B3 (Bobot: 5)'),
            Form::selectc('6002a', 5, 20, 'Pemilahan medis & non-medis'),
            Form::selectc('6002b', 5, 20, 'Penyimpanan sesuai ketentuan'),
            Form::selectc('6002c', 5, 20, 'TPS B3 berizin'),
            Form::selectc('6002d', 5, 40, 'Pengolahan limbah B3 berizin (internal/eksternal)'),
            Form::input('text', 'Sebutkan Pihak Ketiga Berizin', 'pihak_ketiga_berizin'),
            Form::input('text', 'Nomor dan tahun perizinan Pihak Ketiga', 'nomor_tahun_perizinan_pihak_ketiga'),
            Form::input('date', 'Masa Berlaku MOU dengan pihak ketiga', 'masa_berlaku_mou_pihak_ketiga'),

            Form::h(3, 'Limbah Cair (Bobot: 4)'),
            Form::selectc('6003b', 4, 50, 'Hasil olahan limbah cair sesuai baku mutu'),
            Form::input('textarea', 'Sebutkan parameter limbah yang tidak memenuhi bakumutu dan Kendala pengelolaan limbah cair selama 1 tahun', 'parameter_limbah_kendala'),
            Form::h(3, 'Limbah Gas (Bobot: 2)'),
            Form::selectc('6004a', 2, 20, 'Memenuhi penaatan dalam frekuensi  pengambilan contoh pemeriksaan emisi gas buang dan udara ambien luar'),
            Form::selectc('6004b', 2, 20, 'Kualitas emisi gas buang dan partikulat dari cerobong memenuhi standar kualitas udara sesuai dengan ketentuan peraturan perundangundangan tentang standar kualitas gas emisi sumber tidak bergerak'),
            Form::selectc('6004c', 2, 20, 'Memenuhi penaatan pelaporan hasil uji atau pengukuran laboratorium limbah gas kepada instansi pemerintah sesuai ketentuan, minimal setiap 1 kali setahun'),
            Form::selectc('6004d', 2, 20, 'Setiap sumber emisi gas berbentuk cerobong tinggi seperti generator set, boiler dilengkapi dengan fasilitas penunjang uji emisi.'),
            Form::selectc('6004e', 2, 20, 'cerobong gas buang di rumah sakit dilengkapi dengan alat kelengkapan cerobong.'),
            Form::h(2, 'VII. PENGAMANAN RADIASI (Bobot: 10)'),
            Form::h(3, 'Pengamanan Radiasi (Bobot: 10)'),
            Form::selectc('f7001', 10, 40, 'Izin penggunaan alat dari BAPETEN'),
            Form::selectc('f7002', 10, 30, 'Peralatan proteksi radiasi'),
            Form::selectc('f7003', 10, 30, 'Pemantauan pekerja radiasi dengan APD'),
            Form::selectc('f7004', 10, 20, 'Memiliki alat rontgen portable'),
            Form::selectc('f7005', 10, 20, 'Jika memiliki Alat Rotgen Portable, apakah memiliki Shielding radiasi yang berbentuk segitiga/shieling mengelilingi pada saat proses dilakukan'),
            Form::selects('penyelenggaraan_linen_rs_oleh', 0, 'Penyelenggaraan Linen RS oleh', [
                Form::option('Internal RS', 'Internal RS'),
                Form::option('Eksternal RS', 'Eksternal RS')
            ]),
            Form::h(2, 'VIII. PENYELENGGARAAN LINEN (Bobot: 10)'),
            Form::h(3, 'Penyelenggaraan Linen Internal (Bobot: 7)'),
            Form::selectc('8001a', 7, 20, 'Terdapat keran air keperluan higiene dan sanitasi dengan tekanan cukup dan kualitas air yang memenuhi persyaratan baku mutu, juga tersedia air panas dengan tekanan dan suhu yang memadai.'),
            Form::selectc('8001b', 7, 20, 'Dilakukan pemilahan antara linen infeksius dan non infeksius'),
            Form::selectc('8001c', 7, 20, 'Dilakukan pencucian secara terpisah antara linen infeksius dan noninfeksius.'),
            Form::selectc('8001d', 7, 20, 'Tersedia ruang pemisah antara linen bersih dan linen kotor'),
            Form::selectc('8001e', 7, 20, 'Perlakuan memenuhi persyaratan'),
            Form::h(3, 'Penyelenggaraan Linen Eksternal (Bobot: 3)'),
            Form::selectc('8002a', 3, 50, 'Ada MoU dengan pihak ketiga'),
            Form::selectc('8002b', 3, 50, 'Pengawasan rutin'),
            Form::h(2, 'IX. MANAJEMEN KESEHATAN LINGKUNGAN RUMAH SAKIT (Bobot: 10)'),
            Form::h(3, 'Manajemen Kesling RS (Bobot: 4)'),
            Form::selectc('9001a', 4, 25, 'Ada unit/instalasi sanitasi'),
            Form::selectc('9001b', 4, 15, 'Memiliki dokumen administrasi kesehatan lingkungan rumah sakit yang meliputi panduan/pedoman (seperti SK,SOP)'),
            Form::selectc('9001c', 4, 20, 'Memiliki dokumen lingkungan hidup yang telah disahkan oleh instansi Pemerintah atau sesuai dengan ketentuan peraturan perundang-undangan'),
            Form::selectc('9001d', 4, 20, 'Rencana kerja bidang kesling'),
            Form::selectc('9001e', 4, 10, 'Melaksanakan monitoring dan evaluasi kegiatan kesehatan lingkungan rumah sakit'),
            Form::selectc('9001f', 4, 10, 'Membuat laporan rutin ke direksi/pimpinan rumah sakit dan instansi yang berwenang'),
            Form::selects('f9002', 3, 'Peralatan Kesling (Bobot: 3)', [
                Form::option(100, 'Semua peralatan'),
                Form::option(50, 'Sebagian peralatan'),
                Form::option(0, 'Tidak ada')]),
            Form::selects('f9003', 3, 'Tenaga Kesehatan Lingkungan (Bobot: 3)', [
                Form::option(100, 'Penanggung jawab kesehatan lingkungan rumah sakit, baik pemerintah maupun swasta, harus memiliki pendidikan di bidang kesehatan lingkungan, sanitasi, teknik lingkungan, atau teknik penyehatan, dengan kualifikasi minimal Sarjana (S1) atau Diploma IV untuk kelas A dan B, serta minimal Diploma III (D3) untuk kelas C dan D'),
                Form::option(25, 'Tidak sesuai kriteria')])
            ];
    }

    protected function formPenilaianName()
    {
        $fields = [];
        foreach ($this->formPenilaian() as $item) {
            if (isset($item['name'])) {
                $fields[] = $item['name'];
            }
        }
        return $fields;
    }





    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = RumahSakit::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Rumah Sakit',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Rumah Sakit', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::RUMAH_SAKIT)],

                        ['Kelas', $item['kelas']],
                        ['Jumlah Tempat Tidur', $item['jumlah-tempat-tidur']]],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_RUMAH_SAKIT_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return RumahSakit::withTrashed()->get()->map(function ($item) {
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
                            'ID',
                            'User ID',
                            'Nama Rumah Sakit',
                            'Nama Penanggung Jawab Kesehatan Lingkungan Rumah Sakit',
                            'Alamat',
                            'Kelurahan',
                            'Kecamatan',
                            'Kontak Penanggung Jawab Kesehatan Lingkungan Rumah Sakit',
                            'Status Operasi',
                            'Titik GPS',
                            'Nama Pemeriksa',
                            'Instansi Pemeriksa',
                            'Tanggal Penilaian',
                            'Skor',
                            'Pelaporan Elektronik',
                            'Pengamanan Radiasi',
                            'Penyehatan Air Hemodialisa',
                            'Catatan Lain',
                            'Rencana Tindak Lanjut',
                            'Apakah Memiliki Dokumen Rintek TPS B3?',
                            'No Dokumen Rintek TPS B3 (Jika Ya)',
                            'Apakah Memiliki Dokumen Pertek IPAL?',
                            'No Dokumen Pertek IPAL (Jika Ya)',
                            'Apakah Melakukan Pengisian SIKELIM Setiap Bulan?',
                            'Alasan Tidak Mengisi SIKELIM (Jika Tidak)',
                            'Apakah Melakukan Pengisian DSMILING Setiap Bulan?',
                            'Alasan Tidak Mengisi DSMILING (Jika Tidak)',
                            'Dibuat',
                            'Diperbarui',
                            'Dihapus',
                            'Kelas Rumah Sakit',
                            'Jumlah Tempat Tidur',
                            'Kuantitas Air Minum (Bobot: 4)',
                            'RS kelas A dan B di ruang rawat inap 400 - 450 liter/TT/hari atau RS kelas C dan D di ruang rawat inap 200 - 300 liter/TT/hari (Bobot: 4)',
                            'Di unit rawat jalan semua kelas rumah sakit 5 L/orang/hari (Bobot: 4)',
                            'Tidak memenuhi persyaratan kuantitas air keperluan higiene dan sanitasi (Bobot: 4)',
                            'Kualitas Air Minum (Bobot: 3)',
                            'Kualitas Air untuk Keperluan Higiene dan Sanitasi (Bobot: 3)',
                            'Ruang operasi kosong: 35 CFU/m³',
                            'Ruang operasi ada aktivitas: 180 CFU/m³',
                            'Kelembaban Udara (Bobot: 2)',
                            'Ruang pasien (tidak tidur: 100 lux; tidur: 50 lux)',
                            'Rawat jalan (100 lux)',
                            'IGD (100 lux, meja tindakan + lampu sorot)',
                            'Operasi umum (300–500 lux)',
                            'Meja operasi (10,000–20,000 lux)',
                            'Anestesi pemulihan (300–500 lux)',
                            'Endoskopi, lab (75–100 lux)',
                            'Sinar X (minimal 60 lux)',
                            'Koridor (minimal 100 lux)',
                            'Tangga (minimal 100 lux)',
                            'Administrasi/kantor (minimal100 lux)',
                            'Ruang pasien (tidak tidur: 65 dBA; tidur: 55 dBA)',
                            'Operasi umum (65 dBA)',
                            'Ruang umum (65 dBA)',
                            'Anestesi pemulihan (65 dBA)',
                            'Endoskopi, lab (65 dBA)',
                            'Sinar X (65 dBA)',
                            'Koridor (65 dBA)',
                            'Tangga (65 dBA)',
                            'Kantor/lobby (65 dBA)',
                            'Ruang alat/gudang (65 dBA)',
                            'Farmasi (65 dBA)',
                            'Ruang cuci (80 dBA)',
                            'Ruang isolasi (55 dBA)',
                            'Ruang poligigi (65 dBA)',
                            'Ruang ICU (65 dBA)',
                            'Ambulans (85 dBA)',
                            'Karbon monoksida maks. 10,000 µg/m³',
                            'Karbondioksida maks. 1 ppm',
                            'Timbal maks. 0.5 µg/m³',
                            'Nitrogen dioksida maks. 200 µg/m³',
                            'Sulfur dioksida maks. 125 µg/m³',
                            'Formaldehida maks. 100 µg/m³',
                            'Total senyawa organik yang mudah menguap (T.VOC) maks. 3 ppm',
                            'Tidak berbau (bebas H₂S, amonia)',
                            'Kadar debu (diameter <10 mikron atau tidak melebihi 150 μg/m3 dan tidak mengandung debu asbes, diameter<2,5 mikron atau tidak melebihi 25 µg/m3)',
                            'Hasil Pengukuran PM 2,5 µg/m³',
                            'Pengelolaan Pangan oleh',
                            'Standar Mutu Pangan Siap Saji (Bobot: 5)',
                            'Hasil IKL Jasa Boga B (Bobot: 5)',
                            'Toilet Pengunjung (Bobot: 2)',
                            'Toilet Disabilitas (Bobot: 2)',
                            'Permukaan rata, tidak licin, warna terang, mudah dibersihkan',
                            'Kemiringan cukup ke saluran pembuangan',
                            'Pertemuan lantai dengan dinding harus berbentuk Konus atau lengkung agar mudah dibersihkan',
                            'Permukaan dinding harus kuat rata, berwarna terang dan menggunakan cat yang tidak luntur serta tidak menggunakan cat yang mengandung logam berat',
                            'Pintu utama dan pintu-pintu yang dilalui brankar/tempat tidur pasien memiliki lebar bukaan minimal 120 cm, dan pintu-pintu yang tidak menjadi akses tempat tidur pasien memiliki lebar bukaan minimal 90 cm',
                            'Di daerah sekitar pintu masuk tidak boleh ada perbedaan ketinggian lantai',
                            'Pintu untuk kamar mandi di ruangan perawatan pasien dan pintu toilet untuk aksesibel, harus terbuka ke luar, dan lebar',
                            'Pintu-pintu yang menjadi akses tempat tidur pasien harus dilapisi bahan anti benturan.',
                            'Ruang perawatan pasien harus memiliki bukaan jendela yang dapat terbuka secara maksimal untuk kepentingan pertukaran udara.',
                            'Pada bangunan rumah sakit bertingkat, lebar bukaan jendela harus aman dari kemungkinan pasien dapat melarikan/meloloskan diri.',
                            'Atap Rumah Sakit (Bobot: 1)',
                            'Kuat, warna terang, mudah dibersihkan, tidak berjamur',
                            'Tinggi langit-langit diruangan minimal 2,80 m, dan tinggi di selasa (koridor) minimal 2,40 m.',
                            'Ruang operasi minimal 3 m',
                            'Pada ruang operasi dan ruang perawatan intensif, bahan langit-langit harus memiliki tingkat ketahanan api (TKA) minimal 2 jam',
                            'Pada tempat-tempat yang membutuhkan tingkat kebersihan ruangan tertentu, maka lampu-lampu penerangan ruangan dipasang dibenamkan pada plafon (recessed).',
                            'Nama Pest Control',
                            'Nomor Izin dan Tahun Perijinan Pest Control',
                            'Nyamuk Anopheles sp. MBR (Man biting rate) <0,025',
                            'Larva Anopheles sp. indeks habitat <1',
                            'Nyamuk Aedes aegypti dan/atau Aedes albopictus Angka Istirahat (Resting rate) <0,025',
                            'Larva Aedes aegypti/albopictus ABJ minimal 95',
                            'Nyamuk Culex sp. MHD (Man Hour Density) <1',
                            'Larva Culex sp. indeks habitat <5',
                            'Mansonia sp. MHD <5',
                            'Pinjal indeks khusus <1',
                            'Lalat indeks populasi <2',
                            'Kecoa indeks populasi <2',
                            'Angka Kepadatan Binatang Pembawa Penyakit (Bobot: 5)',
                            'Penanganan limbah dengan 3R',
                            'Memiliki TPS limbah domestik',
                            'Pengangkutan TPS maks. 2x24 jam',
                            'Pemilahan medis & non-medis',
                            'Penyimpanan sesuai ketentuan',
                            'TPS B3 berizin',
                            'Pengolahan limbah B3 berizin (internal/eksternal)',
                            'Nomor dan Tahun perizinan TPS LB3',
                            'Sebutkan Pihak Ketiga Berizin',
                            'Nomor dan tahun perizinan Pihak Ketiga',
                            'Masa Berlaku MOU dengan pihak ketiga',
                            'Memiliki IPAL berizin',
                            'Sebutkan nomor dan tahun perizinan',
                            'Hasil olahan limbah cair sesuai baku mutu',
                            'Sebutkan parameter limbah yang tidak memenuhi bakumutu dan Kendala pengelolaan limbah cair selama 1 tahun',
                            'Memenuhi penaatan dalam frekuensi  pengambilan contoh pemeriksaan emisi gas buang dan udara ambien luar',
                            'Kualitas emisi gas buang dan partikulat dari cerobong memenuhi standar kualitas udara sesuai dengan ketentuan peraturan perundangundangan tentang standar kualitas gas emisi sumber tidak bergerak',
                            'Memenuhi penaatan pelaporan hasil uji atau pengukuran laboratorium limbah gas kepada instansi pemerintah sesuai ketentuan, minimal setiap 1 kali setahun',
                            'Setiap sumber emisi gas berbentuk cerobong tinggi seperti generator set, boiler dilengkapi dengan fasilitas penunjang uji emisi.',
                            'cerobong gas buang di rumah sakit dilengkapi dengan alat kelengkapan cerobong.',
                            'Izin penggunaan alat dari BAPETEN',
                            'Peralatan proteksi radiasi',
                            'Pemantauan pekerja radiasi dengan APD',
                            'Memiliki alat rontgen portable',
                            'Jika memiliki Alat Rotgen Portable, apakah memiliki Shielding radiasi yang berbentuk segitiga/shieling mengelilingi pada saat proses dilakukan',
                            'Penyelenggaraan Linen RS oleh',
                            'Terdapat keran air keperluan higiene dan sanitasi dengan tekanan cukup dan kualitas air yang memenuhi persyaratan baku mutu, juga tersedia air panas dengan tekanan dan suhu yang memadai.',
                            'Dilakukan pemilahan antara linen infeksius dan non infeksius',
                            'Dilakukan pencucian secara terpisah antara linen infeksius dan noninfeksius.',
                            'Tersedia ruang pemisah antara linen bersih dan linen kotor',
                            'Perlakuan memenuhi persyaratan',
                            'Ada MoU dengan pihak ketiga',
                            'Pengawasan rutin',
                            'Ada unit/instalasi sanitasi',
                            'Memiliki dokumen administrasi kesehatan lingkungan rumah sakit yang meliputi panduan/pedoman (seperti SK,SOP)',
                            'Memiliki dokumen lingkungan hidup yang telah disahkan oleh instansi Pemerintah atau sesuai dengan ketentuan peraturan perundang-undangan',
                            'Rencana kerja bidang kesling',
                            'Melaksanakan monitoring dan evaluasi kegiatan kesehatan lingkungan rumah sakit',
                            'Membuat laporan rutin ke direksi/pimpinan rumah sakit dan instansi yang berwenang',
                            'Peralatan Kesling (Bobot: 3)',
                            'Tenaga Kesehatan Lingkungan (Bobot: 3)',
                            'Jenis RS'
                        ]; 
                    }
                    }, 'REPORT_RUMAH_SAKIT_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspection.rumah-sakit.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Validasi input request (Lengkap tanpa dipotong)
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'kelas' => 'required|string|max:255',
                'jumlah-tempat-tidur' => 'required|numeric|min:1',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
                'status-operasi' => 'required|in:0,1',
                'dokumen-rintek-tps-b3' => 'required|in:Ya,Tidak',
                'nomor-dokumen-rintek-tps-b3' => 'nullable|string|max:255',
                'dokumen-pertek-ipal' => 'required|in:Ya,Tidak',
                'nomor-dokumen-pertek-ipal' => 'nullable|string|max:255',
                'pengisian-sikelim' => 'required|in:Ya,Tidak',
                'alasan-sikelim' => 'nullable|string|max:500',
                'pengisian-dsmiling' => 'required|in:Ya,Tidak',
                'alasan-dsmiling' => 'nullable|string|max:500',
                'hasil_pengukuran_pm25' => 'nullable|string',
                'pengelolaan_pangan_oleh' => 'nullable|in:Internal RS,Pihak Ketiga',
                'nama_pest_control' => 'nullable|string',
                'nomor_izin_tahun_pest_control' => 'nullable|string',
                'pihak_ketiga_berizin' => 'nullable|string',
                'nomor_tahun_perizinan_pihak_ketiga' => 'nullable|string',
                'masa_berlaku_mou_pihak_ketiga' => 'nullable|string',
                'parameter_limbah_kendala' => 'nullable|string',
                'penyelenggaraan_linen_rs_oleh' => 'nullable|in:Internal RS,Eksternal RS',
            ]);

            // 2. Ambil daftar field yang boleh diisi dari Model
            $fillableFields = (new RumahSakit)->getFillable();

            // 3. Ambil data dari request secara aman menggunakan only()
            $data = $request->only($fillableFields);
            
            // 4. Handle instansi-lainnya logic
            if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->has('instansi-lainnya')) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // 5. Handle field 'Lainnya...' untuk f1006, f1007, dan 6001e
            $lainnyaFields = ['f1006', 'f1007', '6001e'];
            foreach ($lainnyaFields as $field) {
                if ($request->input($field) === 'Lainnya' && $request->has($field . '_lainnya') && !empty($request->input($field . '_lainnya'))) {
                    $data[$field] = $request->input($field . '_lainnya');
                }
            }

            // 5. Tambahkan user_id
            $data['user_id'] = Auth::id();

            // 6. Hitung skor
            $totalScoreObtained = array_reduce($this->formPenilaianName(), function ($carry, $column) use ($request) {
                $value = $request->input($column, 0);
                return $carry + (is_numeric($value) ? (int)$value : 0);
            }, 0);

            $normalizedScore = round(($totalScoreObtained / 11000) * 10000);
            $data['skor'] = $normalizedScore;

            // 7. Buat record baru menggunakan ::create()
            $insert = RumahSakit::create($data);

            if (!$insert) {
                return redirect()->back()->with('error', 'Penilaian / inspeksi Rumah Sakit gagal dibuat, silahkan coba lagi')->withInput();
            }

            return redirect(route('rumah-sakit.show', ['rumah_sakit' => $insert->id]))->with('success', 'Penilaian / inspeksi Rumah Sakit berhasil dibuat');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in store method', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Terjadi kesalahan validasi.');
        } catch (\Exception $e) {
            Log::error('Error creating rumah sakit', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RumahSakit $rumahSakit)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $rumahSakit,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Rumah Sakit',
            'edit_route' => route('rumah-sakit.edit', ['rumah_sakit' => $rumahSakit['id']]),
            'destroy_route' => route('rumah-sakit.destroy', ['rumah_sakit' => $rumahSakit['id']]),
            'export_route' => route(
                'rumah-sakit.index',
                [
                    'export' => 'pdf',
                    'id' => $rumahSakit['id']],
            )]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RumahSakit $rumahSakit)
    {
        return view('pages.inspection.rumah-sakit.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $rumahSakit]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RumahSakit $rumahSakit)
    {
        try {
            // Cek apakah aksi yang diminta adalah duplikat
            if ($request->input('action') == 'duplicate') {
                return $this->handleDuplicate($request, $rumahSakit);
            }

            // 1. Validasi input request (Lengkap tanpa dipotong)
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'kelas' => 'required|string|max:255',
                'jumlah-tempat-tidur' => 'required|numeric|min:1',
                'pengelola' => 'required|string|max:255',
                'kontak' => 'required|string|max:255',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
                'status-operasi' => 'required|in:0,1',
                'dokumen-rintek-tps-b3' => 'required|in:Ya,Tidak',
                'nomor-dokumen-rintek-tps-b3' => 'nullable|string|max:255',
                'dokumen-pertek-ipal' => 'required|in:Ya,Tidak',
                'nomor-dokumen-pertek-ipal' => 'nullable|string|max:255',
                'pengisian-sikelim' => 'required|in:Ya,Tidak',
                'alasan-sikelim' => 'nullable|string|max:500',
                'pengisian-dsmiling' => 'required|in:Ya,Tidak',
                'alasan-dsmiling' => 'nullable|string|max:500',
                'hasil_pengukuran_pm25' => 'nullable|string',
                'pengelolaan_pangan_oleh' => 'nullable|in:Internal RS,Pihak Ketiga',
                'nama_pest_control' => 'nullable|string',
                'nomor_izin_tahun_pest_control' => 'nullable|string',
                'pihak_ketiga_berizin' => 'nullable|string',
                'nomor_tahun_perizinan_pihak_ketiga' => 'nullable|string',
                'masa_berlaku_mou_pihak_ketiga' => 'nullable|string',
                'parameter_limbah_kendala' => 'nullable|string',
                'penyelenggaraan_linen_rs_oleh' => 'nullable|in:Internal RS,Eksternal RS',
            ]);

            // 2. Ambil daftar field yang boleh diisi dari Model
            $fillableFields = (new RumahSakit)->getFillable();

            // 3. Ambil data dari request secara aman
            $data = $request->only($fillableFields);
            
            // 4. Handle instansi-lainnya logic
            if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->has('instansi-lainnya')) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // 5. Handle field 'Lainnya...' untuk f1006, f1007, dan 6001e
            $lainnyaFields = ['f1006', 'f1007', '6001e'];
            foreach ($lainnyaFields as $field) {
                if ($request->input($field) === 'Lainnya' && $request->has($field . '_lainnya') && !empty($request->input($field . '_lainnya'))) {
                    $data[$field] = $request->input($field . '_lainnya');
                }
            }

            // 6. Hitung ulang skor
            $totalScoreObtained = array_reduce($this->formPenilaianName(), function ($carry, $column) use ($request) {
                $value = $request->input($column, 0);
                return $carry + (is_numeric($value) ? (int)$value : 0);
            }, 0);

            $normalizedScore = round(($totalScoreObtained / 11000) * 10000);
            $data['skor'] = $normalizedScore;

            // 6. Update record yang ada
            $update = $rumahSakit->update($data);

            if (!$update) {
                return redirect()->back()->with('error', 'Form informasi dan penilaian Rumah Sakit gagal diubah')->withInput();
            }

            // Hapus cache untuk memastikan data tampil segar
            Artisan::call('cache:clear');

            return redirect(route('rumah-sakit.show', ['rumah_sakit' => $rumahSakit->id]))->with('success', 'Form informasi dan penilaian Rumah Sakit berhasil diubah');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in update method', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Terjadi kesalahan validasi.');
        } catch (\Exception $e) {
            Log::error('Error updating rumah sakit', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $rumahSakit = RumahSakit::where('id', $id)->withTrashed()->first();

        if ($rumahSakit['deleted_at']) {
            $rumahSakit->update([
                'deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $rumahSakit->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Rumah Sakit berhasil dihapus');
    }

    /**
     * Handle duplicate operation
     */
     private function handleDuplicate(Request $request, RumahSakit $rumahSakit)
    {
        // Logika untuk duplikasi sama persis dengan membuat data baru (store)
        
        // 1. Ambil daftar field yang boleh diisi dari Model
        $fillableFields = (new RumahSakit)->getFillable();

        // 2. Ambil data dari request secara aman
        $data = $request->only($fillableFields);
        
        // 3. Handle instansi-lainnya logic
        if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->has('instansi-lainnya')) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // 4. Handle field 'Lainnya...' untuk f1006, f1007, dan 6001e
        $lainnyaFields = ['f1006', 'f1007', '6001e'];
        foreach ($lainnyaFields as $field) {
            if ($request->input($field) === 'Lainnya' && $request->has($field . '_lainnya') && !empty($request->input($field . '_lainnya'))) {
                $data[$field] = $request->input($field . '_lainnya');
            }
        }

        // 5. Tambahkan user_id baru (pengguna yang sedang login)
        $data['user_id'] = Auth::id();

        // 6. Hitung ulang skor berdasarkan data yang disubmit
        $totalScoreObtained = array_reduce($this->formPenilaianName(), function ($carry, $column) use ($request) {
            $value = $request->input($column, 0);
            return $carry + (is_numeric($value) ? (int)$value : 0);
        }, 0);
        
        $normalizedScore = round(($totalScoreObtained / 11000) * 10000);
        $data['skor'] = $normalizedScore;

        // 7. Buat record baru
        $insert = RumahSakit::create($data);
        
        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'Duplikasi penilaian gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('rumah-sakit.show', ['rumah_sakit' => $insert->id]))->with('success', 'Duplikasi penilaian berhasil dibuat');
    }
}