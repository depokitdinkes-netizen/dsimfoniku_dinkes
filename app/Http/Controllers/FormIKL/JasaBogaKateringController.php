<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\JasaBogaKatering;
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

class JasaBogaKateringController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Jasa Boga/Katering', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Jumlah Penjamah Pangan', 'u004'),
            Form::input('number', 'Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat', 'u005'),
            Form::input('number', 'Nomor Izin Usaha (Opsional)', 'u006'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('select', 'Tujuan IKL', 'tujuan-ikl'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Tipe Jasa Boga / Katering', 'u009'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Luar TPP'),

            Form::h(3, 'A Lokasi', 'lokasi'),

            Form::select('Lokasi bebas banjir', 'l001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu', 'l002'),
            Form::select('Lokasi bebas dari sumber bebas vektor dan binatang pembawa penyakit', 'l003'),

            Form::h(3, 'B Bangunan dan Fasilitasnya', 'bangunan-dan-fasilitasnya'),

            Form::select('Bangunan pengolahan pangan memiliki pagar pembatas', 'b001'),
            Form::select('Area parkir kendaraan jauh dari pintu masuk bangunan pengolahan pangan untuk mencegah kontaminasi asap kendaraan masuk ke ruang pengolahan pangan', 'b002'),
            Form::select('Halaman bangunan pengolahan pangan bebas vektor dan binatang pembawa penyakit atau binatang peliharaan', 'b003'),
            Form::select('Jika halaman memiliki tanaman, tanaman tidak menempel langsung bangunan/dinding pengolahan pangan', 'b004'),
            Form::select('Dinding bangunan tidak ada retakan yang membuka ke dalam area pengolahan pangan', 'b005'),

            Form::select('Tersedia tempat sampah di area luar', 'b006'),
            Form::select('Tertutup rapat', 'b007'),
            Form::select('Tidak ada bau yang menyengat', 'b008'),
            Form::select('Tidak ada tumpukan sampah menggunung', 'b009'),

            Form::select('Terdapat sistem drainase di area luar', 'b010'),
            Form::select('Bersih', 'b011'),
            Form::select('Tidak ada luapan air/sumbatan', 'b012'),
            Form::select('Memiliki grease trap/penangkap lemak', 'b013'),

            Form::h(4, 'Plafon bagian luar bangunan pengolahan pangan: tidak ada lubang ke area dalam bangunan pengolahan (tempat sarang atau akses vektor dan binatang pembawa penyakit masuk ke area pengolahan)'),
            Form::select('Tidak ada lubang ke area dalam bangunan pengolahan (tempat sarang atau akses vektor dan binatang pembawa penyakit masuk ke area pengolahan)', 'b014'),
            Form::select('Tidak ada sawang/bebas kotoran', 'b015'),

            Form::h(4, 'Pintu masuk TPP'),
            Form::select('Bahan kuat dan tahan lama', 'b016'),
            Form::select('Desain halus/rata', 'b017'),
            Form::select('Dapat menutup rapat', 'b018'),
            Form::select('Membuka ke arah luar', 'b019'),
            Form::select('Selalu tertutup untuk menghindari akses vektor dan binatang pembawa penyakit (atau memiliki penghalang bebas vektor dan binatang pembawa penyakit seperti plastic curtain atau air curtain)', 'b020'),
            Form::select('Khusus Golongan B dan C: pintu masuk bahan baku dan produk matang dibuat terpisah', 'b021'),

            Form::select('Memiliki ventilasi udara (jendela/exhaust/AC/lainnya)', 'b022'),
            Form::select('Bahan kuat dan tahan lama', 'b023'),
            Form::select('Jika terbuka, memiliki kasa anti serangga yang mudah dilepas dan dibersihkan', 'b024'),
            Form::select('Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih', 'b025'),

            Form::select('Tersedia ruang/area khusus untuk istirahat karyawan (jika lokasi TPP di gedung minimal disediakan kursi untuk istirahat karyawan)', 'b026'),
            Form::select('Tersedia wastafel sebelum masuk bangunan', 'b027'),

            Form::h(4, 'Wastafel'),
            Form::select('Terdapat media petunjuk cuci tangan (poster atau tulisan)', 'b028'),
            Form::select('Terdapat sabun cuci tangan', 'b029', 2),
            Form::select('Tersedia air mengalir', 'b030', 2),
            Form::select('Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)', 'b031'),
            Form::select('Bahan kuat', 'b032'),
            Form::select('Desain mudah dibersihkan', 'b033'),

            Form::h(3, 'C Penanganan Pangan', 'penanganan-pangan'),

            Form::select('Tidak ada pengolahan pangan di area luar bangunan pengolahan pangan yang tidak memiliki pelindung', 'p001', 3),
            Form::select('Pangan matang tidak disimpan dalam kondisi terbuka di area luar bangunan pengolahan pangan', 'p002', 3),

            Form::h(3, 'D Fasilitas Karyawan', 'fasilitas-karyawan'),
            Form::h(4, 'Loker karyawan (jika lokasi TPP di dalam gedung, posisi loker tidak boleh menyebabkan kontaminasi silang)'),
            Form::select('Terdapat loker karyawan terpisah (perempuan dan laki-laki)', 'f001'),
            Form::select('Terdapat tata tertib penggunaan loker', 'f002'),
            Form::select('Loker tidak digunakan sebagai tempat penyimpanan makanan karyawan', 'f003'),
            Form::select('Loker tidak digunakan sebagai tempat penyimpanan peralatan pengolahan pangan', 'f004', 2),

            Form::h(3, 'E Area Penerimaan Bahan Baku', 'area-penerimaan-bahan-baku'),

            Form::select('Area penerimaan bersih dan rapih', 'eb001'),
            Form::select('Kendaraan untuk mengangkut bahan pangan bersih, dan tidak digunakan untuk selain bahan pangan', 'eb002', 2),
            Form::select('Transit time bahan baku pangan cukup untuk memastikan bahan baku yang memerlukan pengendalian suhu (suhu chiller dan freezer) tidak rusak', 'eb003', 2),
            Form::select('Suhu kendaraan yang mengangkut pangan segar (jika kondisi suhu dikendalikan sesuai suhu chiller atau freezer) harus sesuai', 'eb004', 2),

            Form::h(3, 'F Persyaratan Bahan Baku', 'persyaratan-bahan-baku'),

            Form::select('Bahan pangan yang diterima disimpan dalam wadah dan suhu yang sesuai dengan jenis pangan tersebut', 'fb001', 2),

            Form::h(4, 'Bahan baku pangan dalam kemasan'),
            Form::select('Memiliki label', 'fb002', 2),
            Form::select('Terdaftar atau ada izin edar', 'fb003', 2),
            Form::select('Tidak kedaluwarsa', 'fb004', 2),
            Form::select('Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)', 'fb005', 2),
            Form::select('Bahan pangan yang tidak dikemas/berlabel berasal dari sumber yang jelas/dipercaya', 'fb006', 2),
            Form::select('Jika bahan pangan tidak langsung digunakan maka bahan pangan diberikan label tanggal penerimaan', 'fb007'),
            Form::select('Tidak menggunakan makanan sisa yang sudah busuk sebagai bahan pangan untuk diolah menjadi makanan baru', 'fb008', 2),
            Form::select('Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'fb009', 2),
            Form::select('Air untuk pengolahan pangan memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'fb0010', 2),

            Form::h(2, 'Inspeksi Area Dalam'),

            Form::h(3, 'A Area Penyimpanan Umum', 'area-penyimpanan-umum'),

            Form::h(4, 'Dinding ruang penyimpanan'),
            Form::select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'adu001'),
            Form::select('Tidak retak', 'adu002'),

            Form::h(4, 'Lantai ruang penyimpanan'),
            Form::select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'adu003'),
            Form::select('Tidak retak atau kuat', 'adu004'),
            Form::select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'adu005'),
            Form::select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'adu006'),

            Form::h(4, 'Langit-langit'),
            Form::select('Tinggi minimal 2,4 meter dari lantai', 'adu007'),
            Form::select('Bersih', 'adu008'),
            Form::select('Tertutup rapat', 'adu009'),
            Form::select('Tidak ada jamur', 'adu010', 2),
            Form::select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)', 'adu011'),
            Form::select('Tidak ada kondensasi air yang jatuh langsung ke bahan pangan', 'adu012', 2),

            Form::select('Penyimpanan bahan baku menggunakan kartu stok First In First Out/First Expired First Out (FIFO/FEFO) (untuk bahan baku yang langsung habis, persyaratan ini dapat diabaikan)', 'adu013'),

            Form::h(4, 'Personil yang bekerja pada area ini'),
            Form::select('Sehat', 'adu014', 2),
            Form::select('Menggunakan APD (masker) dengan benar', 'adu015'),
            Form::select('Menggunakan pakaian kerja', 'adu016'),

            Form::select('Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)', 'adu017'),

            Form::h(4, 'Tempat sampah'),
            Form::select('Tertutup dan tidak rusak penutupnya', 'adu018'),
            Form::select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'adu019'),
            Form::select('Dilapisi plastik', 'adu020'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'adu021'),
            Form::select('Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)', 'adu022'),

            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'adu023', 3),

            Form::select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan', 'adu024', 3),

            Form::select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)', 'adu025', 2),

            Form::h(3, 'Area Penyimpanan Bahan Pangan', 'area-penyimpanan-bahan-pangan'),

            Form::h(4, 'Ruang atau alat penyimpanan bahan pangan'),
            Form::select('Untuk bahan mentah dari hewan disimpan pada suhu ≤ 4°C', 'pbp001', 2),
            Form::select('Bahan mentah lain yang membutuhkan pendinginan, misalnya sayuran harus disimpan pada suhu yang sesuai', 'pbp002', 2),
            Form::select('Bahan pangan beku yang tidak langsung digunakan disimpan pada suhu -18°C atau di bawahnya', 'pbp003', 2),

            Form::h(4, 'Penyimpanan bahan pangan'),
            Form::select('Disimpan di atas palet atau alas (jarak minimal 15 cm dari lantai)', 'pbp004'),
            Form::select('Jarak penyimpanan dengan dinding minimal 5 cm', 'pbp005'),
            Form::select('Jarak penyimpanan dengan langit-langit minimal 60 cm', 'pbp006'),

            Form::select('Suhu gudang bahan pangan kering dan kaleng dijaga kurang dari 25°C.', 'pbp007', 2),
            Form::select('Tidak terdapat bahan baku pangan yang kedaluwarsa', 'pbp008', 2),
            Form::select('Tidak terdapat pangan yang busuk', 'pbp009', 2),

            Form::h(4, 'Chiller/freezer (jika ada)'),
            Form::select('Khusus menyimpan bahan baku (tidak menyatu dengan pangan matang)', 'pbp010', 3),
            Form::select('Chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'pbp011', 2),
            Form::select('Suhu chiller sesuai (≤ 4°C)', 'pbp012', 2),
            Form::select('Terdapat rekaman monitoring suhu chiller', 'pbp013'),
            Form::select('Suhu freezer sesuai (≤ -15°C)', 'pbp014', 2),
            Form::select('Terdapat rekaman monitoring suhu freezer', 'pbp015'),

            Form::h(3, 'Area Penyimpanan Kemasan'),
            Form::select('Terdapat area khusus penyimpanan kemasan', 'pbp016'),

            Form::h(4, 'Penyimpanan kemasan'),
            Form::select('Disimpan di atas palet (jarak minimal 15 cm dari lantai)', 'pbp017'),
            Form::select('Jarak penyimpanan dengan dinding minimal 5 cm', 'pbp018'),
            Form::select('Jarak penyimpanan dengan langit-langit minimal 60 cm', 'pbp019'),
            Form::select('Kemasan khusus untuk pangan atau food grade', 'pbp020', 2),

            Form::h(3, 'Area Penyimpanan Bahan Kimia Non Pangan', 'area-penyimpanan-bahan-kimia-non-pangan'),

            Form::select('Terdapat area/ruangan khusus (tidak menyatu dengan penyimpanan pangan siap saji atau bahan baku pangan)', 'pbk001', 2),
            Form::select('Ruangan penyimpanan memiliki akses terbatas (dikunci atau dengan metode lainnya yang sesuai)', 'pbk002', 2),
            Form::select('Bahan kimia memiliki label yang memuat informasi tentang identitas dan cara penggunaan', 'pbk003', 2),

            Form::h(3, 'B Area Pencucian', 'area-pencucian'),

            Form::select('Area/tempat pencucian peralatan terpisah dengan area/tempat pencucian pangan', 'ap001'),
            Form::select('Area pencucian peralatan dan pangan tidak digunakan untuk sanitasi karyawan seperti cuci tangan', 'ap002'),
            Form::select('Sarana pencucian peralatan terbuat dari bahan yang kuat, permukaan halus dan mudah dibersihkan', 'ap003'),
            Form::select('Proses pencucian peralatan dilakukan dengan 3 (tiga) proses yaitu pencucian, pembersihan dan sanitasi', 'ap004'),
            Form::select('Penggunaan disinfektan untuk pencucian bahan pangan, takarannya sesuai dengan persyaratan kesehatan/standar disinfektan', 'ap005', 2),
            Form::select('Pencucian bahan pangan menggunakan air dengan kualitas air minum/air yang sudah diolah/dimasak', 'ap006', 2),
            Form::select('Tersedia tempat sampah', 'ap007'),
            Form::select('Tertutup dan tidak rusak', 'ap008'),
            Form::select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'ap009'),
            Form::select('Dilapisi plastik', 'ap010'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'ap011'),
            Form::select('Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)', 'ap012'),
            Form::select('Pengeringan dengan menggunakan lap/kain majun yang bersih dan diganti secara rutin', 'ap013', 2),

            Form::h(3, 'C Area Persiapan, Pengolahan dan Pengemasan Pangan', 'area-persiapan-pengolahan-dan-pengemasan-pangan'),

            Form::h(4, 'Umum'),

            Form::select('Dapur jasaboga terpisah dengan dapur keluarga', 'ppp001'),

            Form::select('Luas lantai dapur yang bebas dari peralatan minimal 2 meter persegi (2m²) untuk setiap penjamah pangan yang sedang bekerja', 'ppp002'),
            Form::select('Tata letak peralatan sesuai alur pengelolaan pangan (alur linear: persiapan - pengolahan pangan - pengemasan, dan seterusnya)', 'ppp003'),

            Form::h(4, 'Dinding ruangan'),
            Form::select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'ppp004', 2),
            Form::select('Tidak retak', 'ppp005', 2),
            Form::select('Bagian dinding yang terkena percikan air/minyak dilapisi bahan kedap air/minyak', 'ppp006', 2),

            Form::h(4, 'Lantai ruangan'),
            Form::select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'ppp007', 2),
            Form::select('Tidak retak atau kuat', 'ppp008', 2),
            Form::select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'ppp009', 2),
            Form::select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'ppp010'),

            Form::h(4, 'Langit-langit'),
            Form::select('Tinggi minimal 2,4 meter dari lantai', 'ppp011'),
            Form::select('Bersih', 'ppp012', 2),
            Form::select('Tertutup rapat', 'ppp013', 2),
            Form::select('Tidak ada jamur', 'ppp014', 2),
            Form::select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)', 'ppp015', 2),
            Form::select('Tidak ada kondensasi air yang langsung jatuh ke pangan', 'ppp016', 3),

            Form::select('Penyimpanan bahan yang akan diolah tidak langsung di atas lantai (harus menggunakan wadah atau alas)', 'ppp017', 2),

            Form::h(4, 'Personel yang bekerja pada area ini'),
            Form::select('Sehat', 'ppp018', 3),

            Form::h(4, 'Menggunakan APD berupa'),
            Form::select('Celemek', 'ppp019', 2),
            Form::select('Masker', 'ppp020', 3),
            Form::select('Hairnet/penutup rambut', 'ppp021', 3),
            Form::select('Menggunakan pakaian kerja yang hanya digunakan di tempat kerja', 'ppp022', 2),
            Form::select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'ppp023', 3),
            Form::select('Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'ppp024', 3),
            Form::select('Tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros, dan lain-lain) ketika mengolah pangan', 'ppp025', 3),

            Form::h(4, 'Pada saat mengolah pangan, tidak'),
            Form::select('merokok', 'ppp026', 3),
            Form::select('bersin atau batuk di atas pangan langsung', 'ppp027', 3),
            Form::select('meludah', 'ppp028', 3),
            Form::select('mengunyah makanan/permen', 'ppp029', 3),
            Form::select('menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian menyentuh pangan', 'ppp030', 3),
            Form::select('Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'ppp031', 3),
            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'ppp032', 3),

            Form::h(4, 'Pencahayaan'),
            Form::select('Cukup terang', 'ppp033'),
            Form::select('Lampu tercover di semua area dan cover tidak terbuat dari bahan kaca/yang mudah pecah', 'ppp034', 2),
            Form::select('Sumber pencahayaan alami seperti jendela tidak terbuka atau membuka langsung ke area luar', 'ppp035', 2),

            // #######################3
            Form::select('Tersedia tempat sampah', 'ppp036', 2),
            Form::select('Tertutup dan tidak rusak penutupnya', 'ppp037'),
            Form::select('Desain tidak berlubang-lubang', 'ppp038'),
            Form::select('Tidak dibuka dengan tangan (bisa dengan pedal kaki)', 'ppp039', 3),
            Form::select('Dilapisi plastik', 'ppp040'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'ppp041'),
            Form::select('Tidak ada tumpukan sampah (pembuangan keluar minimal 1 x 24 jam)', 'ppp042', 2),

            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'ppp043', 3),

            Form::select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan', 'ppp044', 3),

            Form::select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)', 'ppp045', 3),

            Form::select('Pembuangan asap dapur dikeluarkan melalui cerobong yang dilengkapi dengan sungkup asap atau penyedot udara', 'ppp046', 2),

            Form::select('Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak', 'ppp047', 2),
            Form::select('Melakukan thawing/pelunakan dengan benar', 'ppp048', 2),
            Form::select('Pangan dimasak sampai matang sempurna', 'ppp049', 3),
            Form::select('Penyiapan buah dan sayuran segar yang langsung dikonsumsi dicuci dengan menggunakan air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak ', 'ppp050', 3),
            Form::select('Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'ppp051', 3),

            Form::h(3, 'Fasilitas Higiene Sanitasi Personil', 'fasilitas-higiene-sanitasi-personil'),

            Form::select('Tersedia wastafel', 'fhsp001', 3),
            Form::select('Terdapat petunjuk cuci tangan', 'fhsp002', 2),
            Form::select('Terdapat sabun cuci tangan', 'fhsp003', 3),
            Form::select('Tersedia air mengalir', 'fhsp004', 3),
            Form::select('Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)', 'fhsp005', 2),
            Form::select('Bahan kuat', 'fhsp006'),
            Form::select('Desain mudah dibersihkan', 'fhsp007'),

            Form::select('Tersedia toilet untuk karyawan yang mudah diakses (lokasi bisa berada di luar area pengolahan) dan tidak boleh membuka langsung ke ruangan/area pengolahan pangan', 'fhsp008', 3),

            Form::h(4, 'Desain'),
            Form::select('Kuat', 'fhsp009'),
            Form::select('Permukaan halus', 'fhsp010'),
            Form::select('Mudah dibersihkan', 'fhsp011'),
            Form::select('Jumlah cukup', 'fhsp012'),

            Form::h(4, 'Tersedia'),
            Form::select('Air mengalir', 'fhsp013', 3),
            Form::select('Sabun cuci tangan', 'fhsp014', 3),
            Form::select('Tempat sampah', 'fhsp015'),
            Form::select('Tisu/pengering', 'fhsp016', 2),
            Form::select('Ventilasi yang baik dan tidak membuka langsung ke ruang pengolahan', 'fhsp017', 3),
            Form::select('Dilengkapi petunjuk cuci tangan setelah dari toilet', 'fhsp018', 2),
            Form::select('Dilengkapi wastafel dan fasilitasnya (sabun dan air mengalir) untuk cuci tangan', 'fhsp019', 2),
            Form::select('Toilet terpisah antara laki-laki dan perempuan', 'fhsp020'),

            Form::h(3, 'Peralatan', 'peralatan'),

            Form::h(4, 'Peralatan untuk pengolahan pangan'),
            Form::select('Bahan kuat', 'pl001', 2),
            Form::select('Tidak terbuat dari kayu (contoh: talenan, alat pengaduk)', 'pl002', 3),
            Form::select('Tidak berkarat', 'pl003', 3),
            Form::select('Tara pangan (food grade)', 'pl004', 3),
            Form::select('Bersih sebelum digunakan', 'pl005', 3),
            Form::select('Setelah digunakan kondisi bersih dan kering', 'pl006', 2),
            Form::select('Berbeda untuk pangan matang dan pangan mentah', 'pl007', 3),
            Form::select('Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade', 'pl008', 3),
            Form::select('Tersedia termometer yang berfungsi dan akurat', 'pl009', 2),
            Form::select('Peralatan personal (misalnya handphone), peralatan kantor, dan lain-lain yang tidak diperlukan tidak diletakkan di area pengolahan pangan', 'pl010', 2),
            Form::select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'pl011', 2),
            Form::select('Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)', 'pl012', 2),

            Form::h(3, 'Penyimpanan Pangan Matang', 'penyimpanan-pangan-matang'),

            Form::select('Penyimpanan pangan matang tidak dicampur dengan bahan pangan mentah', 'ppm001', 3),
            Form::select('Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan', 'ppm002', 2),
            Form::select('Menyimpan pangan matang untuk bank sample yang disimpan di kulkas dalam jangka waktu 2 x 24 jam.', 'ppm003', 2),

            Form::h(4, 'Chiller/freezer (jika ada)'),
            Form::select('Khusus menyimpan pangan matang dengan kondisi terkemas', 'ppm004', 3),
            Form::select('Suhu chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'ppm005', 2),
            Form::select('Suhu chiller sesuai (≤ 4°C)', 'ppm006', 2),
            Form::select('Terdapat dokumen monitoring chiller', 'ppm007', 2),
            Form::select('Suhu freezer sesuai (≤ -18°C)', 'ppm008', 2),
            Form::select('Terdapat dokumen monitoring freezer', 'ppm009', 2),

            Form::h(3, 'Pengemasan Pangan Matang', 'pengemasan-pangan-matang'),

            Form::select('Pengemasan dilakukan secara higiene (personil cuci tangan dan menggunakan sarung tangan dengan kondisi baik)', 'ppm010', 3),
            Form::select('Pangan matang harus dikemas dalam wadah tertutup dan tara pangan (food grade)', 'ppm011', 3),

            Form::h(4, 'Kotak/kemasan untuk pangan yang matang'),
            Form::select('Diberikan tanda batas waktu (expired date) tanggal dan waktu makanan boleh dikonsumsi', 'ppm012', 2),
            Form::select('Dicantumkan nomor sertifikat laik higiene sanitasi', 'ppm013'),

            Form::h(3, 'Pengangkutan Pangan Matang', 'pengangkutan-pangan-matang'),

            Form::select('Selama pengangkutan, pangan harus dilindungi dari debu dan jenis kontaminasi lainnya', 'ppm014', 3),
            Form::select('Pangan matang diangkut pada suhu yang sesuai menggunakan tempat yang dapat menjaga suhu panas dan atau dingin', 'ppm015', 3),

            Form::h(4, 'Khusus jasa boga golongan B dan C: Tersedia kendaraan khusus pengangkut pangan matang, dengan kriteria'),

            Form::select('Bersih', 'ppm016', 2),
            Form::select('Bebas vektor dan binatang pembawa penyakit', 'ppm017', 2),
            Form::select('Terdapat pembersihan secara berkala', 'ppm018', 2),

            Form::h(3, 'D Dokumentasi dan Rekaman (di akses di ruangan administrasi)', 'dokumentasi-dan-rekaman'),

            Form::select('Rekaman Khusus Golongan B dan C (catatan: ruang pengolahan dan administrasi pada jasa boga golongan C harus terpisah)', 'dr001'),
            Form::select('Tersedia dokumentasi/jadwal pemeliharaan bangunan', 'dr002'),
            Form::select('Tersedia dokumentasi/jadwal pembersihan dan sanitasi', 'dr003', 2),
            Form::select('Tersedia dokumentasi/jadwal pemeliharaan peralatan seperti pengecekan suhu alat pendingin (kalibrasi)', 'dr004', 2),
            Form::select('Tersedia dokumentasi/jadwal pemeliharaan sistem penanganan limbah', 'dr005'),
            Form::select('Tersedia dokumentasi/jadwal pengendalian vektor dan binatang pembawa penyakit', 'dr006', 2),
            Form::select('Tersedia dokumentasi penerimaan bahan pangan', 'dr007'),
            Form::select('Tersedia hasil analisa pengujian air yang sesuai dengan persyaratan air minum dan memiliki hasil yang sesuai persyaratan', 'dr008', 3),
            Form::select('Tersedia dokumentasi pengawasan internal secara berkala (menggunakan buku rapor/formulir self assessment IKL)', 'dr009', 2),

            Form::h(3, 'Rekaman Personel', 'rekaman-personel'),

            Form::select('Pengelola/pemilik memiliki sertifikat pelatihan keamanan pangan siap saji', 'rp001', 2),
            Form::select('Golongan A: Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 20%)', 'rp002', 3),
            Form::select('Golongan B: Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 50%)', 'rp003', 3),

            Form::h(4, 'Golongan C'),

            Form::select('Penjamah pangan dilakukan pemeriksaan kesehatan di awal masuk kerja dibuktikan dengan surat keterangan sehat dari fasyankes', 'rp004', 2),
            Form::select('Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (100%)', 'rp005', 3),
            Form::select('Tersedia jadwal/program pelatihan untuk penjamah pangan', 'rp006', 2),
            Form::select('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali setahun', 'rp007', 2),

            Form::h(3, 'E Keselamatan, Kesehatan Kerja, dan lainnya', 'keselamatan-kesehatan-kerja-dan-lain-lainnya'),

            Form::select('Tersedia alat pemadam api ringan (APAR) gas yang mudah dijangkau untuk situasi darurat disertai dengan petunjuk penggunaan yang jelas', 'kkl001'),
            Form::select('Tersedia personil yang bertanggung jawab dan dapat menggunakan APAR', 'kkl002'),
            Form::select('APAR tidak kadaluwarsa', 'kkl003'),
            Form::select('Tersedia perlengkapan P3K dan obat-obatan yang tidak kadaluwarsa', 'kkl004'),
            Form::select('Tersedia petunjuk jalur evakuasi yang jelas pada setiap ruangan ke arah titik kumpul ', 'kkl005'),
            Form::select('Terdapat pos satpam di pintu masuk TPP dan dilakukan pengecekan terhadap karyawan dan visitor', 'kkl006'),
            Form::select('Menerapkan kawasan tanpa rokok (KTR)', 'kkl007'),

        ];
    }

    protected function formPenilaianName()
    {
        return [
            //  a lokasi
            'l001',
            'l002',
            'l003',
            //  b bangunan dan fasilitasnya
            'b001',
            'b002',
            'b003',
            'b004',
            'b005',
            'b006',
            'b007',
            'b008',
            'b009',
            'b010',
            'b011',
            'b012',
            'b013',
            'b014',
            'b015',
            'b016',
            'b017',
            'b018',
            'b019',
            'b020',
            'b021',
            'b022',
            'b023',
            'b024',
            'b025',
            'b026',
            'b027',
            'b028',
            'b029',
            'b030',
            'b031',
            'b032',
            'b033',
            //  c penanganan pangan
            'p001',
            'p002',
            //  d fasilitas karyawan
            'f001',
            'f002',
            'f003',
            'f004',
            //  e area penerimaan bahan baku
            'eb001',
            'eb002',
            'eb003',
            'eb004',
            //  f persyaratan bahan baku
            'fb001',
            'fb002',
            'fb003',
            'fb004',
            'fb005',
            'fb006',
            'fb007',
            'fb008',
            'fb009',
            'fb0010',
            //  a area penyimpanan umum
            'adu001',
            'adu002',
            'adu003',
            'adu004',
            'adu005',
            'adu006',
            'adu007',
            'adu008',
            'adu009',
            'adu010',
            'adu011',
            'adu012',
            'adu013',
            'adu014',
            'adu015',
            'adu016',
            'adu017',
            'adu018',
            'adu019',
            'adu020',
            'adu021',
            'adu022',
            'adu023',
            'adu024',
            'adu025',
            //  area penyimpanan bahan pangan
            'pbp001',
            'pbp002',
            'pbp003',
            'pbp004',
            'pbp005',
            'pbp006',
            'pbp007',
            'pbp008',
            'pbp009',
            'pbp010',
            'pbp011',
            'pbp012',
            'pbp013',
            'pbp014',
            'pbp015',
            //  area penyimpanan kemasan
            'pbp016',
            'pbp017',
            'pbp018',
            'pbp019',
            'pbp020',
            //  area penyimpanan bahan baku kimia non pangan
            'pbk001',
            'pbk002',
            'pbk003',
            //  b area pencucian
            'ap001',
            'ap002',
            'ap003',
            'ap004',
            'ap005',
            'ap006',
            'ap007',
            'ap008',
            'ap009',
            'ap010',
            'ap011',
            'ap012',
            'ap013',
            //  c area persiapan pengolahan dan pengemasan pangan
            'ppp001',
            'ppp002',
            'ppp003',
            'ppp004',
            'ppp005',
            'ppp006',
            'ppp007',
            'ppp008',
            'ppp009',
            'ppp010',
            'ppp011',
            'ppp012',
            'ppp013',
            'ppp014',
            'ppp015',
            'ppp016',
            'ppp017',
            'ppp018',
            'ppp019',
            'ppp020',
            'ppp021',
            'ppp022',
            'ppp023',
            'ppp024',
            'ppp025',
            'ppp026',
            'ppp027',
            'ppp028',
            'ppp029',
            'ppp030',
            'ppp031',
            'ppp032',
            'ppp033',
            'ppp034',
            'ppp035',
            'ppp036',
            'ppp037',
            'ppp038',
            'ppp039',
            'ppp040',
            'ppp041',
            'ppp042',
            'ppp043',
            'ppp044',
            'ppp045',
            'ppp046',
            'ppp047',
            'ppp048',
            'ppp049',
            'ppp050',
            'ppp051',
            //  fasilitas higiene sanitasi personil
            'fhsp001',
            'fhsp002',
            'fhsp003',
            'fhsp004',
            'fhsp005',
            'fhsp006',
            'fhsp007',
            'fhsp008',
            'fhsp009',
            'fhsp010',
            'fhsp011',
            'fhsp012',
            'fhsp013',
            'fhsp014',
            'fhsp015',
            'fhsp016',
            'fhsp017',
            'fhsp018',
            'fhsp019',
            'fhsp020',
            //  peralatan
            'pl001',
            'pl002',
            'pl003',
            'pl004',
            'pl005',
            'pl006',
            'pl007',
            'pl008',
            'pl009',
            'pl010',
            'pl011',
            'pl012',
            //  penyimpanan pangan matang
            'ppm001',
            'ppm002',
            'ppm003',
            'ppm004',
            'ppm005',
            'ppm006',
            'ppm007',
            'ppm008',
            'ppm009',
            //  pengemasan pangan matang
            'ppm010',
            'ppm011',
            'ppm012',
            'ppm013',
            //  pengangkutan pangan matang
            'ppm014',
            'ppm015',
            'ppm016',
            'ppm017',
            'ppm018',
            //  d dokumentasi dan rekaman di akses di ruangan administrasi
            'dr001',
            'dr002',
            'dr003',
            'dr004',
            'dr005',
            'dr006',
            'dr007',
            'dr008',
            'dr009',
            //  rekaman personel
            'rp001',
            'rp002',
            'rp003',
            'rp004',
            'rp005',
            'rp006',
            'rp007',
            //  e keselamatan kesehatan kerja dan lainnya
            'kkl001',
            'kkl002',
            'kkl003',
            'kkl004',
            'kkl005',
            'kkl006',
            'kkl007',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = JasaBogaKatering::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Jasa Boga/Katering',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Jasa Boga/Katering', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Nomor Izin Usaha', $item['u006'] ?? '-'],
                        ['Penjamah Pangan (Bersertifikat/Total)', $item['u005'] . '/' . $item['u004']],
                        ['Tipe Jasa Boga/Katering', $item['u009']],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_JASA_BOGA_KATERING_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return JasaBogaKatering::withTrashed()->get()->map(function ($item) {
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

                            'Penjamah Pangan Total',
                            'Penjamah Pangan Bersertifikat',
                            'Nomor Izin Usaha',
                            'Tipe Jasa Boga / Katering',

                            'Lokasi bebas banjir',
                            'Lokasi bebas dari pencemaran bau/asap/debu',
                            'Lokasi bebas dari sumber bebas vektor dan binatang pembawa penyakit',
                            'Bangunan pengolahan pangan memiliki pagar pembatas',
                            'Area parkir kendaraan jauh dari pintu masuk bangunan pengolahan pangan untuk mencegah kontaminasi asap kendaraan masuk ke ruang pengolahan pangan',
                            'Halaman bangunan pengolahan pangan bebas vektor dan binatang pembawa penyakit atau binatang peliharaan',
                            'Jika halaman memiliki tanaman, tanaman tidak menempel langsung bangunan/dinding pengolahan pangan',
                            'Dinding bangunan tidak ada retakan yang membuka ke dalam area pengolahan pangan',
                            'Tersedia tempat sampah di area luar',
                            'Tertutup rapat',
                            'Tidak ada bau yang menyengat',
                            'Tidak ada tumpukan sampah menggunung',
                            'Terdapat sistem drainase di area luar',
                            'Bersih',
                            'Tidak ada luapan air/sumbatan',
                            'Memiliki grease trap/penangkap lemak',
                            'Tidak ada lubang ke area dalam bangunan pengolahan (tempat sarang atau akses vektor dan binatang pembawa penyakit masuk ke area pengolahan)',
                            'Tidak ada sawang/bebas kotoran',
                            'Bahan kuat dan tahan lama',
                            'Desain halus/rata',
                            'Dapat menutup rapat',
                            'Membuka ke arah luar',
                            'Selalu tertutup untuk menghindari akses vektor dan binatang pembawa penyakit (atau memiliki penghalang bebas vektor dan binatang pembawa penyakit seperti plastic curtain atau air curtain)',
                            'Khusus Golongan B dan C: pintu masuk bahan baku dan produk matang dibuat terpisah',
                            'Memiliki ventilasi udara (jendela/exhaust/AC/lainnya)',
                            'Bahan kuat dan tahan lama',
                            'Jika terbuka, memiliki kasa anti serangga yang mudah dilepas dan dibersihkan',
                            'Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih',
                            'Tersedia ruang/area khusus untuk istirahat karyawan (jika lokasi TPP di gedung minimal disediakan kursi untuk istirahat karyawan)',
                            'Tersedia wastafel sebelum masuk bangunan',
                            'Terdapat media petunjuk cuci tangan (poster atau tulisan)',
                            'Terdapat sabun cuci tangan',
                            'Tersedia air mengalir',
                            'Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)',
                            'Bahan kuat',
                            'Desain mudah dibersihkan',
                            'Tidak ada pengolahan pangan di area luar bangunan pengolahan pangan yang tidak memiliki pelindung',
                            'Pangan matang tidak disimpan dalam kondisi terbuka di area luar bangunan pengolahan pangan',
                            'Terdapat loker karyawan terpisah (perempuan dan laki-laki)',
                            'Terdapat tata tertib penggunaan loker',
                            'Loker tidak digunakan sebagai tempat penyimpanan makanan karyawan',
                            'Loker tidak digunakan sebagai tempat penyimpanan peralatan pengolahan pangan',
                            'Area penerimaan bersih dan rapih',
                            'Kendaraan untuk mengangkut bahan pangan bersih, dan tidak digunakan untuk selain bahan pangan',
                            'Transit time bahan baku pangan cukup untuk memastikan bahan baku yang memerlukan pengendalian suhu (suhu chiller dan freezer) tidak rusak',
                            'Suhu kendaraan yang mengangkut pangan segar (jika kondisi suhu dikendalikan sesuai suhu chiller atau freezer) harus sesuai',
                            'Bahan pangan yang diterima disimpan dalam wadah dan suhu yang sesuai dengan jenis pangan tersebut',
                            'Memiliki label',
                            'Terdaftar atau ada izin edar',
                            'Tidak kedaluwarsa',
                            'Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)',
                            'Bahan pangan yang tidak dikemas/berlabel berasal dari sumber yang jelas/dipercaya',
                            'Jika bahan pangan tidak langsung digunakan maka bahan pangan diberikan label tanggal penerimaan',
                            'Tidak menggunakan makanan sisa yang sudah busuk sebagai bahan pangan untuk diolah menjadi makanan baru',
                            'Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Air untuk pengolahan pangan memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Bersih (tidak ada kotoran, jamur atau cat mengelupas)',
                            'Tidak retak',
                            'Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)',
                            'Tidak retak atau kuat',
                            'Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)',
                            'Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)',
                            'Tinggi minimal 2,4 meter dari lantai',
                            'Bersih',
                            'Tertutup rapat',
                            'Tidak ada jamur',
                            'Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)',
                            'Tidak ada kondensasi air yang jatuh langsung ke bahan pangan',
                            'Penyimpanan bahan baku menggunakan kartu stok First In First Out/First Expired First Out (FIFO/FEFO) (untuk bahan baku yang langsung habis, persyaratan ini dapat diabaikan)',
                            'Sehat',
                            'Menggunakan APD (masker) dengan benar',
                            'Menggunakan pakaian kerja',
                            'Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)',
                            'Tertutup dan tidak rusak penutupnya',
                            'Tidak dibuka dengan tangan (dibuka dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan',
                            'Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)',
                            'Untuk bahan mentah dari hewan disimpan pada suhu ≤ 4°C',
                            'Bahan mentah lain yang membutuhkan pendinginan, misalnya sayuran harus disimpan pada suhu yang sesuai',
                            'Bahan pangan beku yang tidak langsung digunakan disimpan pada suhu -18°C atau di bawahnya',
                            'Disimpan di atas palet atau alas (jarak minimal 15 cm dari lantai)',
                            'Jarak penyimpanan dengan dinding minimal 5 cm',
                            'Jarak penyimpanan dengan langit-langit minimal 60 cm',
                            'Suhu gudang bahan pangan kering dan kaleng dijaga kurang dari 25°C.',
                            'Tidak terdapat bahan baku pangan yang kedaluwarsa',
                            'Tidak terdapat pangan yang busuk',
                            'Khusus menyimpan bahan baku (tidak menyatu dengan pangan matang)',
                            'Chiller/freezer atau termometer untuk monitoring sudah dikalibrasi',
                            'Suhu chiller sesuai (≤ 4°C)',
                            'Terdapat rekaman monitoring suhu chiller',
                            'Suhu freezer sesuai (≤ -15°C)',
                            'Terdapat rekaman monitoring suhu freezer',
                            'Terdapat area khusus penyimpanan kemasan',
                            'Disimpan di atas palet (jarak minimal 15 cm dari lantai)',
                            'Jarak penyimpanan dengan dinding minimal 5 cm',
                            'Jarak penyimpanan dengan langit-langit minimal 60 cm',
                            'Kemasan khusus untuk pangan atau food grade',
                            'Terdapat area/ruangan khusus (tidak menyatu dengan penyimpanan pangan siap saji atau bahan baku pangan)',
                            'Ruangan penyimpanan memiliki akses terbatas (dikunci atau dengan metode lainnya yang sesuai)',
                            'Bahan kimia memiliki label yang memuat informasi tentang identitas dan cara penggunaan',
                            'Area/tempat pencucian peralatan terpisah dengan area/tempat pencucian pangan',
                            'Area pencucian peralatan dan pangan tidak digunakan untuk sanitasi karyawan seperti cuci tangan',
                            'Sarana pencucian peralatan terbuat dari bahan yang kuat, permukaan halus dan mudah dibersihkan',
                            'Proses pencucian peralatan dilakukan dengan 3 (tiga) proses yaitu pencucian, pembersihan dan sanitasi',
                            'Penggunaan disinfektan untuk pencucian bahan pangan, takarannya sesuai dengan persyaratan kesehatan/standar disinfektan',
                            'Pencucian bahan pangan menggunakan air dengan kualitas air minum/air yang sudah diolah/dimasak',
                            'Tersedia tempat sampah',
                            'Tertutup dan tidak rusak',
                            'Tidak dibuka dengan tangan (dibuka dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)',
                            'Pengeringan dengan menggunakan lap/kain majun yang bersih dan diganti secara rutin',
                            'Dapur jasaboga terpisah dengan dapur keluarga',
                            'Luas lantai dapur yang bebas dari peralatan minimal 2 meter persegi (2m²) untuk setiap penjamah pangan yang sedang bekerja',
                            'Tata letak peralatan sesuai alur pengelolaan pangan (alur linear: persiapan - pengolahan pangan - pengemasan, dan seterusnya)',
                            'Bersih (tidak ada kotoran, jamur atau cat mengelupas)',
                            'Tidak retak',
                            'Bagian dinding yang terkena percikan air/minyak dilapisi bahan kedap air/minyak',
                            'Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)',
                            'Tidak retak atau kuat',
                            'Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)',
                            'Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)',
                            'Tinggi minimal 2,4 meter dari lantai',
                            'Bersih',
                            'Tertutup rapat',
                            'Tidak ada jamur',
                            'Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)',
                            'Tidak ada kondensasi air yang langsung jatuh ke pangan',
                            'Penyimpanan bahan yang akan diolah tidak langsung di atas lantai (harus menggunakan wadah atau alas)',
                            'Sehat',
                            'Celemek',
                            'Masker',
                            'Hairnet/penutup rambut',
                            'Menggunakan pakaian kerja yang hanya digunakan di tempat kerja',
                            'Berkuku pendek, bersih dan tidak memakai pewarna kuku',
                            'Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan',
                            'Tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros, dan lain-lain) ketika mengolah pangan',
                            'merokok',
                            'bersin atau batuk di atas pangan langsung',
                            'meludah',
                            'mengunyah makanan/permen',
                            'menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian menyentuh pangan',
                            'Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                            'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                            'Cukup terang',
                            'Lampu tercover di semua area dan cover tidak terbuat dari bahan kaca/yang mudah pecah',
                            'Sumber pencahayaan alami seperti jendela tidak terbuka atau membuka langsung ke area luar',
                            'Tersedia tempat sampah',
                            'Tertutup dan tidak rusak penutupnya',
                            'Desain tidak berlubang-lubang',
                            'Tidak dibuka dengan tangan (bisa dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pembuangan keluar minimal 1 x 24 jam)',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengontaminasi pangan',
                            'Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)',
                            'Pembuangan asap dapur dikeluarkan melalui cerobong yang dilengkapi dengan sungkup asap atau penyedot udara',
                            'Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak',
                            'Melakukan thawing/pelunakan dengan benar',
                            'Pangan dimasak sampai matang sempurna',
                            'Penyiapan buah dan sayuran segar yang langsung dikonsumsi dicuci dengan menggunakan air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak ',
                            'Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Tersedia wastafel',
                            'Terdapat petunjuk cuci tangan',
                            'Terdapat sabun cuci tangan',
                            'Tersedia air mengalir',
                            'Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)',
                            'Bahan kuat',
                            'Desain mudah dibersihkan',
                            'Tersedia toilet untuk karyawan yang mudah diakses (lokasi bisa berada di luar area pengolahan) dan tidak boleh membuka langsung ke ruangan/area pengolahan pangan',
                            'Kuat',
                            'Permukaan halus',
                            'Mudah dibersihkan',
                            'Jumlah cukup',
                            'Air mengalir',
                            'Sabun cuci tangan',
                            'Tempat sampah',
                            'Tisu/pengering',
                            'Ventilasi yang baik dan tidak membuka langsung ke ruang pengolahan',
                            'Dilengkapi petunjuk cuci tangan setelah dari toilet',
                            'Dilengkapi wastafel dan fasilitasnya (sabun dan air mengalir) untuk cuci tangan',
                            'Toilet terpisah antara laki-laki dan perempuan',
                            'Bahan kuat',
                            'Tidak terbuat dari kayu (contoh: talenan, alat pengaduk)',
                            'Tidak berkarat',
                            'Tara pangan (food grade)',
                            'Bersih sebelum digunakan',
                            'Setelah digunakan kondisi bersih dan kering',
                            'Berbeda untuk pangan matang dan pangan mentah',
                            'Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade',
                            'Tersedia termometer yang berfungsi dan akurat',
                            'Peralatan personal (misalnya handphone), peralatan kantor, dan lain-lain yang tidak diperlukan tidak diletakkan di area pengolahan pangan',
                            'Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang',
                            'Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)',
                            'Penyimpanan pangan matang tidak dicampur dengan bahan pangan mentah',
                            'Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan',
                            'Menyimpan pangan matang untuk bank sample yang disimpan di kulkas dalam jangka waktu 2 x 24 jam.',
                            'Khusus menyimpan pangan matang dengan kondisi terkemas',
                            'Suhu chiller/freezer atau termometer untuk monitoring sudah dikalibrasi',
                            'Suhu chiller sesuai (≤ 4°C)',
                            'Terdapat dokumen monitoring chiller',
                            'Suhu freezer sesuai (≤ -18°C)',
                            'Terdapat dokumen monitoring freezer',
                            'Pengemasan dilakukan secara higiene (personil cuci tangan dan menggunakan sarung tangan dengan kondisi baik)',
                            'Pangan matang harus dikemas dalam wadah tertutup dan tara pangan (food grade)',
                            'Diberikan tanda batas waktu (expired date) tanggal dan waktu makanan boleh dikonsumsi',
                            'Dicantumkan nomor sertifikat laik higiene sanitasi',
                            'Selama pengangkutan, pangan harus dilindungi dari debu dan jenis kontaminasi lainnya',
                            'Pangan matang diangkut pada suhu yang sesuai menggunakan tempat yang dapat menjaga suhu panas dan atau dingin',
                            'Bersih',
                            'Bebas vektor dan binatang pembawa penyakit',
                            'Terdapat pembersihan secara berkala',
                            'Rekaman Khusus Golongan B dan C (catatan: ruang pengolahan dan administrasi pada jasa boga golongan C harus terpisah)',
                            'Tersedia dokumentasi/jadwal pemeliharaan bangunan',
                            'Tersedia dokumentasi/jadwal pembersihan dan sanitasi',
                            'Tersedia dokumentasi/jadwal pemeliharaan peralatan seperti pengecekan suhu alat pendingin (kalibrasi)',
                            'Tersedia dokumentasi/jadwal pemeliharaan sistem penanganan limbah',
                            'Tersedia dokumentasi/jadwal pengendalian vektor dan binatang pembawa penyakit',
                            'Tersedia dokumentasi penerimaan bahan pangan',
                            'Tersedia hasil analisa pengujian air yang sesuai dengan persyaratan air minum dan memiliki hasil yang sesuai persyaratan',
                            'Tersedia dokumentasi pengawasan internal secara berkala (menggunakan buku rapor/formulir self assessment IKL)',
                            'Pengelola/pemilik memiliki sertifikat pelatihan keamanan pangan siap saji',
                            'Golongan A: Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 20%)',
                            'Golongan B: Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 50%)',
                            'Penjamah pangan dilakukan pemeriksaan kesehatan di awal masuk kerja dibuktikan dengan surat keterangan sehat dari fasyankes',
                            'Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (100%)',
                            'Tersedia jadwal/program pelatihan untuk penjamah pangan',
                            'Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali setahun',
                            'Tersedia alat pemadam api ringan (APAR) gas yang mudah dijangkau untuk situasi darurat disertai dengan petunjuk penggunaan yang jelas',
                            'Tersedia personil yang bertanggung jawab dan dapat menggunakan APAR',
                            'APAR tidak kadaluwarsa',
                            'Tersedia perlengkapan P3K dan obat-obatan yang tidak kadaluwarsa',
                            'Tersedia petunjuk jalur evakuasi yang jelas pada setiap ruangan ke arah titik kumpul ',
                            'Terdapat pos satpam di pintu masuk TPP dan dilakukan pengecekan terhadap karyawan dan visitor',
                            'Menerapkan kawasan tanpa rokok (KTR)',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_JASA_BOGA_KATERING_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create(Request $request)
    {
        $golongan = strtoupper($request->query('golongan', 'A'));

        $formPenilaian = $this->formPenilaian();

        switch ($golongan) {
            case 'A':
                $bukanGolA = [];
                foreach (
                    [
                        //  b bangunan dan fasilitasnya
                        'b013',
                        'b021',
                        'b026',
                        'b027',
                        'b028',
                        'b029',
                        'b030',
                        'b031',
                        'b032',
                        'b033',
                        //  d fasilitas karyawan
                        'f001',
                        'f002',
                        'f003',
                        'f004',
                        //  e area penerimaan bahan baku
                        'eb003',
                        'eb004',
                        //  a area penyimpanan umum
                        'adu006',
                        //  area penyimpanan bahan pangan
                        'pbp011',
                        'pbp013',
                        'pbp015',
                        //  area penyimpanan bahan baku kimia non pangan
                        'pbk002',
                        //  c area persiapan pengolahan dan pengemasan pangan
                        'ppp001',
                        'ppp002',
                        'ppp003',
                        'ppp010',
                        'ppp046',
                        //  fasilitas higiene sanitasi personil
                        'fhsp020',
                        //  peralatan
                        'pl009',
                        //  penyimpanan pangan matang
                        'ppm007',
                        'ppm009',
                        //  pengangkutan pangan matang
                        'ppm016',
                        'ppm017',
                        'ppm018',
                        //  d dokumentasi dan rekaman di akses di ruangan administrasi
                        'dr001',
                        'dr002',
                        'dr003',
                        'dr004',
                        'dr005',
                        'dr006',
                        'dr007',
                        //  rekaman personel
                        'rp003',
                        'rp004',
                        'rp005',
                        'rp006',
                        //  e keselamatan kesehatan kerja dan lainnya
                        'kkl006',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolA, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolA as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }

                unset($formPenilaian[35]);
                unset($formPenilaian[45]);
                unset($formPenilaian[46]);
                unset($formPenilaian[261]);
                break;
            case 'B':
                $bukanGolB = [];
                foreach (
                    [
                        //  rekaman personel
                        'rp002',
                        'rp004',
                        'rp005',
                        'rp006',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolB, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolB as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            case 'C':
                $bukanGolC = [];
                foreach (
                    [
                        //  rekaman personel
                        'rp002',
                        'rp003',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolC, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolC as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'Golongan Jasa Boga Tidak Diketahui');
        }

        return view('pages.inspection.jasa-boga-katering.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'golongan' => $golongan,
        ]);
    }

    public function store(Request $request)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to submit Jasa Boga Katering form', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.');
        }
        
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

        
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A' => 358, 'B' => 416, 'C' => 420][strtoupper($request->input('u009', 'A'))]) * 100);

        $insert = JasaBogaKatering::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Jasa Boga / Katering gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('jasa-boga-katering.show', ['jasa_boga_katering' => $insert->id]))->with('success', 'penilaian / inspeksi Jasa Boga / Katering Gol ' . $request->input('u009') . ' berhasil dibuat');
    }

    public function show(JasaBogaKatering $jasaBogaKatering)
    {
        return view('pages.inspection.show', [
            'page_name' => 'inspection',
            'form_data' => $jasaBogaKatering,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Jasa Boga / Katering Gol ' . $jasaBogaKatering['u8'],
            'edit_route' => route('jasa-boga-katering.edit', ['jasa_boga_katering' => $jasaBogaKatering['id']]),
            'destroy_route' => route('jasa-boga-katering.destroy', ['jasa_boga_katering' => $jasaBogaKatering['id']]),
            'export_route' => route(
                'jasa-boga-katering.index',
                [
                    'export' => 'pdf',
                    'id' => $jasaBogaKatering['id'],
                ],
            ),
        ]);
    }

    public function edit(JasaBogaKatering $jasaBogaKatering)
    {
        $formPenilaian = $this->formPenilaian();

        switch ($jasaBogaKatering->u009) {
            case 'A':
                $bukanGolA = [];
                foreach (
                    [
                        //  b bangunan dan fasilitasnya
                        'b013',
                        'b021',
                        'b026',
                        'b027',
                        'b028',
                        'b029',
                        'b030',
                        'b031',
                        'b032',
                        'b033',
                        //  d fasilitas karyawan
                        'f001',
                        'f002',
                        'f003',
                        'f004',
                        //  e area penerimaan bahan baku
                        'eb003',
                        'eb004',
                        //  a area penyimpanan umum
                        'adu006',
                        //  area penyimpanan bahan pangan
                        'pbp011',
                        'pbp013',
                        'pbp015',
                        //  area penyimpanan bahan baku kimia non pangan
                        'pbk002',
                        //  c area persiapan pengolahan dan pengemasan pangan
                        'ppp001',
                        'ppp002',
                        'ppp003',
                        'ppp010',
                        'ppp046',
                        //  fasilitas higiene sanitasi personil
                        'fhsp020',
                        //  peralatan
                        'pl009',
                        //  penyimpanan pangan matang
                        'ppm007',
                        'ppm009',
                        //  pengangkutan pangan matang
                        'ppm016',
                        'ppm017',
                        'ppm018',
                        //  d dokumentasi dan rekaman di akses di ruangan administrasi
                        'dr001',
                        'dr002',
                        'dr003',
                        'dr004',
                        'dr005',
                        'dr006',
                        'dr007',
                        //  rekaman personel
                        'rp003',
                        'rp004',
                        'rp005',
                        'rp006',
                        //  e keselamatan kesehatan kerja dan lainnya
                        'kkl006',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolA, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolA as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }

                unset($formPenilaian[35]);
                unset($formPenilaian[45]);
                unset($formPenilaian[46]);
                break;
            case 'B':
                $bukanGolB = [];
                foreach (
                    [
                        //  rekaman personel
                        'rp002',
                        'rp004',
                        'rp005',
                        'rp006',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolB, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolB as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            case 'C':
                $bukanGolC = [];
                foreach (
                    [
                        //  rekaman personel
                        'rp002',
                        'rp003',
                    ] as $i
                ) {
                    foreach ($this->formPenilaian() as $index => $input) {
                        if ($input['type'] == 'select' && $input['name'] == $i) {
                            array_push($bukanGolC, $index);
                            break;
                        }
                    }
                }

                foreach ($bukanGolC as $indexArr) {
                    unset($formPenilaian[$indexArr]);
                }
                break;
            default:
                return redirect(route('inspection'))->with('error', 'Golongan Jasa Boga Tidak Diketahui');
        }

        return view('pages.inspection.jasa-boga-katering.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'form_data' => $jasaBogaKatering,
        ]);
    }

    public function update(Request $request, JasaBogaKatering $jasaBogaKatering)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to update Jasa Boga Katering form', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return redirect()->route('login')->with('error', 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.');
        }
        
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
        
        // Handle file upload if present
        if ($request->hasFile('dokumen_slhs')) {
            $file = $request->file('dokumen_slhs');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['A' => 358, 'B' => 416, 'C' => 420][strtoupper($request->input('u8', 'A'))]) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($jasaBogaKatering->kelurahan)) {
                $data['kelurahan'] = $jasaBogaKatering->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($jasaBogaKatering->kecamatan)) {
                $data['kecamatan'] = $jasaBogaKatering->kecamatan;
            }
            if (empty($data['subjek']) && !empty($jasaBogaKatering->subjek)) {
                $data['subjek'] = $jasaBogaKatering->subjek;
            }
            if (empty($data['alamat']) && !empty($jasaBogaKatering->alamat)) {
                $data['alamat'] = $jasaBogaKatering->alamat;
            }

            $insert = JasaBogaKatering::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Jasa Boga / Katering gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('jasa-boga-katering.show', ['jasa_boga_katering' => $insert->id]))->with('success', 'penilaian / inspeksi Jasa Boga / Katering berhasil dibuat');
        }

        $update = $jasaBogaKatering->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Jasa Boga / Katering gagal diubah, silahkan coba lagi');
        }

        return redirect(route('jasa-boga-katering.show', ['jasa_boga_katering' => $jasaBogaKatering['id']]))->with('success', 'form informasi dan penilaian Jasa Boga / Katering Gol ' . $request->input('u8') . ' berhasil diubah');
    }

    public function destroy(String $id)
    {
        $jasaBogaKatering = JasaBogaKatering::where('id', $id)->withTrashed()->first();

        if ($jasaBogaKatering['deleted_at']) {
            $jasaBogaKatering->update(['deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $jasaBogaKatering->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Jasa Boga Katering berhasil dihapus');
    }
}