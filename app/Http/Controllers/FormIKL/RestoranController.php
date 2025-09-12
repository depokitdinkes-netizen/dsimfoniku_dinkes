<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Restoran;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class RestoranController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Restoran', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Jumlah Penjamah Pangan', 'u004'),
            Form::input('number', 'Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat', 'u005'),
            Form::input('number', 'Nomor Izin Usaha (Opsional)', 'u006'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('text', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('select', 'Tujuan IKL', 'tujuan-ikl'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Tipe Restoran', 'u009'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Luar TPP'),

            Form::select('Lokasi bebas banjir', 'l001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'l002'),
            Form::select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'l003'),

            Form::h(2, 'Inspeksi Area Pelayanan Konsumen'),

            Form::select('Area ruang makan konsumen bersih', 'p001'),
            Form::select('Meja makan bersih', 'p002', 2),
            Form::select('Kursi/alas duduk bersih', 'p003'),
            Form::select('Peralatan makan bersih dan tertutup', 'p004', 3),
            Form::select('Tempat bumbu tertutup dan bersih', 'p005', 2),
            Form::select('Luas ruangan sesuai dengan rasio kapasitas tempat duduk', 'p006'),
            Form::select('Dinding ruang makan bersih (jika tidak ada dinding, dapat diabaikan)', 'p007'),

            Form::select('Memiliki tempat sampah', 'p008'),
            Form::select('Tertutup dan tidak rusak', 'p009'),
            Form::select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'p010'),
            Form::select('Dilapisi plastik', 'p011'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'p012'),
            Form::select('Tidak ada tumpukan sampah (pengangkutan minimal 1 x 24 jam)', 'p013'),
            Form::select('Ventilasi udara baik (bisa menggunakan ventilasi alami atau buatan)', 'p014'),
            Form::select('Toilet tidak berhubungan langsung dengan area makan atau ada upaya penyekatan jamban/toilet', 'p015', 2),
            Form::select('Toilet dilengkapi dengan sabun', 'p016', 2),
            Form::select('Tersedia air mengalir', 'p017', 2),
            Form::select('Dilengkapi tempat sampah', 'p018'),
            Form::select('Memiliki wastafel untuk cuci tangan konsumen', 'p019', 2),
            Form::select('Dilengkapi sabun cuci tangan', 'p020', 2),
            Form::select('Dilengkapi air mengalir', 'p021', 2),
            Form::select('Mudah dibersihkan', 'p022'),
            Form::select('Dilengkapi tisu/pengering tangan', 'p023'),
            Form::select('Ada petunjuk atau pedoman cara cuci tangan (poster atau tulisan)', 'p024'),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'p025', 2),
            Form::select('Personel yang melayani pembayaran tidak menyentuh pangan secara langsung setelah menyentuh uang/kartu pembayaran/mesin pembayaran', 'p026', 3),

            Form::h(2, 'Inspeksi Area Fasilitas Karyawan dan Penerimaan Bahan Baku'),

            Form::h(3, 'A Fasilitas Karyawan', 'fasilitas-karyawan'),

            Form::select('Terdapat loker', 'fk001'),
            Form::select('Terpisah antara laki-laki dan perempuan', 'fk002'),
            Form::select('Terdapat aturan tertulis penggunaan loker', 'fk003'),
            Form::select('Loker tidak digunakan sebagai tempat penyimpanan makanan', 'fk004', 2),
            Form::select('Loker tidak digunakan sebagai tempat penyimpanan peralatan pengolahan pangan', 'fk005', 2),
            Form::select('Terdapat tempat istirahat untuk karyawan/penjamah pangan', 'fk006'),

            Form::h(3, 'B Area Penerimaan Bahan Baku', 'area-penerimaan-bahan-baku'),

            Form::select('Area penerimaan bersih', 'apbb001'),
            Form::select('Kendaraan untuk mengangkut bahan pangan bersih, tidak digunakan untuk selain bahan pangan', 'apbb002', 2),
            Form::select('Transit time cukup untuk memastikan bahan baku yang memerlukan pengendalian suhu (suhu chiller dan freezer) tidak rusak', 'apbb003', 2),
            Form::select('Suhu kendaraan yang mengangkut pangan segar (jika kondisi suhu dikendalikan sesuai suhu chiller atau freezer) harus sesuai', 'apbb004', 2),
            Form::select('Bahan pangan pada saat diterima berada pada wadah dan suhu yang sesuai dengan jenis pangan', 'apbb005', 2),

            Form::h(4, 'Bahan baku pangan dalam kemasan'),

            Form::select('Memiliki label', 'apbb006', 2),
            Form::select('Terdaftar atau ada izin edar', 'apbb007', 2),
            Form::select('Tidak kadaluwarsa', 'apbb008', 2),
            Form::select('Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)', 'apbb009', 2),
            Form::select('Bahan pangan yang tidak dikemas/berlabel berasal dari sumber yang jelas/dipercaya', 'apbb010', 2),
            Form::select('Jika bahan pangan tidak langsung digunakan maka bahan pangan diberikan label tanggal penerimaan ', 'apbb011'),

            Form::select('Tidak menggunakan makanan sisa sebagai bahan pangan untuk diolah menjadi makanan baru ', 'apbb012', 2),
            Form::select('Jika terdapat menu yang menggunakan bahan baku es batu, maka es batu kualitas air minum dan disimpan dalam tempat khusus', 'apbb013', 2),
            Form::select('Air untuk pengolahan pangan memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'apbb014', 2),
            Form::select('Restoran hotel harus memiliki pintu masuk bahan baku dan pintu keluar pangan matang terpisah', 'apbb015'),

            Form::h(2, 'Inspeksi Area Dapur'),

            Form::h(3, 'A Area Penyimpanan', 'area-penyimpanan'),

            Form::h(4, 'Dinding ruang penyimpanan'),

            Form::select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'ap001', 2),
            Form::select('Tidak retak', 'ap002', 2),

            Form::h(4, 'Lantai ruang penyimpanan'),

            Form::select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'ap003', 2),
            Form::select('Tidak retak atau kuat', 'ap004', 2),
            Form::select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'ap005', 2),
            Form::select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'ap006'),

            Form::h(4, 'Langit-langit'),

            Form::select('Tinggi minimal 2,4 meter dari lantai', 'ap007'),
            Form::select('Bersih', 'ap008'),
            Form::select('Tertutup rapat', 'ap009'),
            Form::select('Tidak ada jamur', 'ap010', 2),
            Form::select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)', 'ap011'),
            Form::select('Tidak ada kondensasi air yang jatuh langsung ke bahan pangan', 'ap012', 2),

            Form::select('Menggunakan kartu stok (FIFO/FEFO)', 'ap013'),

            Form::h(4, 'Personel yang bekerja pada area ini'),

            Form::select('Sehat', 'ap014', 2),
            Form::select('Menggunakan APD (masker) dengan benar', 'ap015'),
            Form::select('Menggunakan pakaian kerja', 'ap016'),

            Form::select('Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)', 'ap017'),

            Form::select('Tersedia tempat sampah', 'ap018'),
            Form::select('Tertutup dan tidak rusak', 'ap019'),
            Form::select('Tidak dibuka dengan tangan', 'ap020'),
            Form::select('Dilapisi plastik', 'ap021'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'ap022'),
            Form::select('Pembuangan minimal 1 X 24 jam', 'ap023'),

            Form::select('Memiliki ventilasi udara', 'ap024'),
            Form::select('Bahan kuat dan tahan lama', 'ap025'),
            Form::select('Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih', 'ap026'),

            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'ap027', 3),
            Form::select('Metode pengendalian vektor dan binatang pembawa penyakit, tidak menggunakan racun, tetapi jebakan/perangkap yang tidak mengkontaminasi pangan', 'ap028', 3),

            Form::select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)', 'ap029', 2),

            Form::h(3, 'Area Penyimpanan Bahan Pangan', 'area-penyimpanan-bahan-pangan'),

            Form::h(4, 'Ruang penyimpanan atau alat penyimpanan bahan pangan'),

            Form::select('Bahan mentah dari hewan disimpan pada suhu ≤ 4°C', 'ap030', 2),
            Form::select('Bahan mentah lain yang membutuhkan pendinginan, misalnya sayuran harus disimpan pada suhu yang sesuai', 'ap031', 2),
            Form::select('Bahan pangan beku yang tidak langsung digunakan disimpan pada suhu ≤18°C atau dibawahnya ', 'ap032', 2),

            Form::select('Semua bahan pangan disimpan pada rak-rak (pallet) dengan jarak minimal 15 cm dari lantai, 5 cm dari dinding dan 60 cm dari langit-langit', 'ap033'),
            Form::select('Suhu gudang bahan pangan kering dan kaleng dijaga kurang dari 25°C', 'ap034', 2),
            Form::select('Tidak terdapat bahan baku pangan yang kadaluwarsa (FIFO/FEFO tidak berjalan)', 'ap035', 2),
            Form::select('Tidak terdapat pangan yang busuk', 'ap036', 2),

            Form::h(4, 'Chiller/Freezer (jika ada)'),

            Form::select('Khusus menyimpan bahan baku (tidak menyatu dengan pangan matang)', 'ap037', 3),
            Form::select('Chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'ap038', 2),
            Form::select('Suhu chiller sesuai (≤ 4°C) & terdapat dokumen monitoringnya yang dilakukan setiap hari', 'ap039', 2),
            Form::select('Suhu freezer sesuai (≤ -18°C) & terdapat dokumen monitoringnya yang dilakukan setiap hari', 'ap040', 2),

            Form::h(3, 'Area Penyimpanan Kemasan', 'area-penyimpanan-kemasan'),

            Form::select('Terdapat area khusus penyimpanan kemasan', 'pk001'),

            Form::h(4, 'Penyimpanan kemasan'),

            Form::select('Kemasan produk akhir disimpan dalam kondisi terkemas rapat untuk menghindari kontaminasi', 'pk002', 2),
            Form::select('Tidak menempel dinding (minimal jarak 5 cm dari dinding)', 'pk003'),
            Form::select('Tidak diletakkan langsung di atas lantai / menggunakan pallet (minimal 15 cm dari lantai)', 'pk004'),
            Form::select('Tidak menempel langit-langit (minimal 60 cm dari langit-langit)', 'pk005'),

            Form::select('Kemasan untuk pangan harus food grade', 'pk006', 2),

            Form::h(3, 'Area Penyimpanan Bahan Kimia Non Pangan', 'area-penyimpanan-bahan-kimia-non-pangan'),

            Form::select('Terdapat area/ruangan khusus penyimpanan bahan kimia non pangan', 'bp001', 2),
            Form::select('Ruangan penyimpanan memiliki akses terbatas (dikunci atau dengan metode lainnya yang sesuai)', 'bp002', 2),
            Form::select('Bahan kimia memiliki label yang memuat informasi tentang identitas dan cara penggunaan', 'bp003', 2),

            Form::h(3, 'B Area Pencucian', 'g-area-pencucian'),

            Form::select('Area/tempat pencucian peralatan terpisah dengan area/tempat pencucian pangan', 'pc001'),
            Form::select('Area pencucian peralatan dan pangan tidak digunakan untuk sanitasi karyawan seperti cuci tangan', 'pc002'),
            Form::select('Sarana pencucian peralatan terbuat dari bahan yang kuat, permukaan halus dan mudah dibersihkan', 'pc003'),
            Form::select('Proses pencucian peralatan dilakukan dengan 3 (tiga) proses yaitu pencucian, pembersihan dan sanitasi', 'pc004'),
            Form::select('Penggunaan disinfektan untuk pencucian bahan pangan, takarannya sesuai dengan persyaratan kesehatan/standar desinfektan', 'pc005', 2),
            Form::select('Pencucian bahan pangan menggunakan air dengan kualitas air minum/air yang sudah diolah/dimasak', 'pc006', 2),
            Form::select('Saluran air limbah terdapat grease trap/penangkap lemak', 'pc007'),

            Form::select('Tersedia tempat sampah', 'pc008'),
            Form::select('Tertutup dan tidak rusak', 'pc009'),
            Form::select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'pc010'),
            Form::select('Dilapisi plastik', 'pc011'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'pc012'),
            Form::select('Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)', 'pc013'),
            Form::select('Pengeringan dengan menggunakan lap/kain majun yang bersih dan diganti secara rutin', 'pc014', 2),

            Form::h(3, 'Area Persiapan, Pengolahan dan Pengemasan Pangan', 'area-persiapan-pengolahan-dan-pengemasan-pangan'),

            Form::h(4, 'Dinding ruangan:'),

            Form::select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'pg001', 2),
            Form::select('Tidak retak', 'pg002', 2),
            Form::select('Bagian dinding yang terkena percikan air/minyak dilapisi bahan kedap air/minyak', 'pg003', 2),

            Form::h(4, 'Lantai ruangan:'),

            Form::select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'pg004', 2),
            Form::select('Tidak retak atau kuat', 'pg005', 2),
            Form::select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'pg006', 2),
            Form::select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'pg007'),

            Form::h(4, 'Langit-langit'),

            Form::select('Tinggi minimal 2,4 meter dari lantai', 'pg008'),
            Form::select('Bersih', 'pg009', 2),
            Form::select('Tertutup rapat', 'pg010', 2),
            Form::select('Tidak ada jamur', 'pg011', 2),
            Form::select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)', 'pg012', 2),
            Form::select('Tidak ada kondensasi air yang langsung jatuh ke pangan', 'pg013', 3),

            Form::h(4, 'Personel yang bekerja pada area ini'),

            Form::select('Sehat', 'pg014', 3),

            Form::h(4, 'Menggunakan APD'),

            Form::select('Celemek', 'pg015', 2),
            Form::select('Masker', 'pg016', 3),
            Form::select('Hairnet/penutup rambut', 'pg017', 3),
            Form::select('Menggunakan pakaian kerja yang hanya digunakan di tempat kerja', 'pg018', 2),
            Form::select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'pg019', 3),
            Form::select('Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'pg020', 3),
            Form::select('Tidak menggunakan perhiasan dan aksesoris  lain  (cincin,  gelang,  bros,  dan lain-lain) ketika mengolah pangan ', 'pg021', 3),

            Form::h(5, 'Pada saat mengolah pahan'),
            Form::select('Tidak merokok', 'pg022', 3),
            Form::select('Tidak bersin atau batuk di atas pangan langsung', 'pg023', 3),
            Form::select('Tidak meludah', 'pg024', 3),
            Form::select('Tidak mengunyah makanan/permen', 'pg025', 3),
            Form::select('Menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian menyentuh pangan langsung', 'pg026', 3),
            Form::select('Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'pg027', 3),
            Form::select('Jika  terluka  maka  luka  ditutup  dengan perban/sejenisnya  dan  ditutup  penutup tahan air dan kondisi bersih', 'pg028', 3),

            // ##############23123123123

            Form::h(4, 'Pencahayaan'),

            Form::select('Cukup terang', 'pg029'),
            Form::select('Lampu tercover di semua area dan cover tidak terbuat dari bahan kaca/yang mudah pecah', 'pg030', 2),

            Form::select('Tersedia tempat sampah', 'pg031', 2),
            Form::select('Tertutup dan tidak rusak', 'pg032'),
            Form::select('Desain tiadk berlubang-lubang', 'pg033'),
            Form::select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'pg034', 3),
            Form::select('Dilapisi plastik', 'pg035'),
            Form::select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'pg036'),
            Form::select('Tidak ada tumpukan sampah (pembuangan minimal 1 x 24 jam)', 'pg037', 2),

            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'pg038', 3),
            Form::select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengkontaminasi pangan', 'pg039', 3),
            Form::select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)', 'pg040', 3),
            Form::select('Pembuangan asap dapur dikeluarkan melalui cerobong yang dilengkapi dengan sungkup asap atau penyedot udara', 'pg041', 2),
            Form::select('Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak', 'pg042', 2),
            Form::select('Melakukan thawing/pelunakan pangan dengan benar', 'pg043', 2),
            Form::select('Pangan dimasak sampai matang sempurna', 'pg044', 3),
            Form::select('Penyiapan buah dan sayuran segar yang langsung dikonsumsi dicuci dengan menggunakan air standar kualitas air minum', 'pg045', 3),

            Form::h(3, 'Fasilitas Higiene Sanitasi Personel', 'fasilitas-higiene-sanitasi-personel'),

            Form::select('Memiliki wastafel yang mudah diakses', 'fh001', 3),
            Form::select('Petunjuk cuci tangan', 'fh002', 2),
            Form::select('Sabun cuci tangan', 'fh003', 3),
            Form::select('Tersedia air mengalir', 'fh004', 3),
            Form::select('Pengering tangan (bisa hand dryer atau tisu dan bukan serbet)', 'fh005', 2),
            Form::select('Tersedia  toilet  (jika  rumah  makan  merupakan satu kesatuan dengan manajemen gedung maka tetap akses ke jamban/toilet mudah untuk dijangkau)', 'fh006', 3),

            Form::select('Jumlah cukup', 'fh007'),
            Form::select('Toilet tidak membuka langsung ke ruang pengolahan/ventilasi toilet tidak membuka langsung ke area pengolahan', 'fh008', 3),
            Form::select('Dilengkapi dengan wastafel dan fasilitasnya (sabun, air mengalir dan pengering) atau sejenisnya', 'fh009', 3),
            Form::select('Dilengkapi petunjuk cuci tangan setelah dari toilet', 'fh010', 2),
            Form::select('Dilengkapi pengering tangan (bisa hand dryer atau tissue dan bukan serbet)', 'fh011', 2),
            Form::select('Toilet terpisah antara laki-laki dan perempuan', 'fh012'),

            Form::h(3, 'Peralatan', 'peralatan'),

            Form::h(4, 'Peralatan untuk pengolahan pangan'),

            Form::select('Bahan kuat', 'pr001', 2),
            Form::select('Tidak terbuat dari kayu (contoh: talenan, alat pengaduk)', 'pr002', 3),
            Form::select('Tidak berkarat', 'pr003', 3),
            Form::select('Tara pangan (food grade)', 'pr004', 3),
            Form::select('Bersih sebelum digunakan', 'pr005', 3),
            Form::select('Setelah digunakan kondisi bersih dan kering', 'pr006', 2),
            Form::select('Berbeda untuk pangan matang dan pangan mentah', 'pr007', 3),
            Form::select('Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade', 'pr008', 3),
            Form::select('Tersedia termometer yang berfungsi dan akurat ', 'pr009', 2),
            Form::select('Peralatan personal, peralatan kantor, dan lain-lain yang tidak diperlukan tidak diletakkan di area pengolahan pangan', 'pr010', 2),
            Form::select('Wadah/pengangkut peralatan makan/minum kotor terbuat dari bahan yang kuat dan mudah dibersihkan', 'pr011', 2),
            Form::select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'pr012', 2),
            Form::select('Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)', 'pr013', 2),

            Form::h(3, 'Penyimpanan Pangan Matang', 'penyimpanan-pangan-matang'),

            Form::select('Penyimpanan pangan matang tidak dicampur dengan bahan pangan mentah', 'pm001', 3),
            Form::select('Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan', 'pm002', 2),

            Form::h(4, 'Chiller/freezer (jika ada)'),

            Form::select('Khusus menyimpan pangan matang dengan kondisi terkemas', 'pm003', 3),
            Form::select('Suhu chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'pm004', 2),
            Form::select('Suhu chiller sesuai (≤ 4°C)', 'pm005', 2),
            Form::select('Terdapat dokumen monitoring chiller yang dilakukan setiap hari', 'pm006', 2),
            Form::select('Suhu freezer sesuai (≤ -18°C)', 'pm007', 2),
            Form::select('Terdapat dokumen monitoring freezer yang dilakukan setiap hari', 'pm008', 2),

            Form::h(3, 'Pengemasan Pangan Matang', 'pengemasan-pangan-matang'),

            Form::select('Pengemasan dilakukan secara higiene (personel cuci tangan dan menggunakan sarung tangan dengan kondisi baik)', 'pp001', 3),
            Form::select('Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)', 'pp002', 3),

            Form::h(4, 'Pangan matang yang disajikan di dalam kotak/kemasan'),

            Form::select('Terdapat keterangan tanda batas waktu (expired dated) tanggal dan waktu makanan boleh dikonsumsi', 'pp003', 2),
            Form::select('Terdapat keterangan nomor sertifikat laik higiene sanitasi', 'pp004'),

            Form::h(3, 'Pengangkutan Pangan Matang', 'pengangkutan-pangan-matang'),

            Form::select('Selama pengangkutan, pangan harus dilindungi dari debu dan jenis kontaminasi lainnya (termasuk penjualan online) atau dikemas rapat', 'pp005', 3),

            Form::h(4, 'Alat pengangkut pangan matang'),

            Form::select('Bebas dari debu', 'pp006', 2),
            Form::select('Bebas dari bebas vektor dan binatang pembawa penyakit', 'pp007', 3),
            Form::select('Bebas dari bahan kimia non pangan', 'pp008', 3),
            Form::select('Dilakukan sanitasi secara rutin', 'pp009', 3),
            Form::select('Pangan matang diangkut pada suhu yang sesuai menggunakan tempat yang dapat menjaga suhu panas dan atau dingin', 'pp010', 3),

            Form::h(3, 'Penyajian Pangan Matang', 'penyajian-pangan-matang'),

            Form::select('Pangan matang yang mudah rusak harus sudah dikonsumsi 4 jam setelah matang', 'pp011', 3),
            Form::select('Pangan matang panas dijaga pada suhu > 60°C', 'pp012', 3),
            Form::select('Pangan matang dingin dijaga pada suhu < 5°C', 'pp013', 3),
            Form::select('Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5°C (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)', 'pp014', 3),
            Form::select('Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'pp015', 3),
            Form::select('Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi', 'pp016', 3),
            Form::select('Air untuk minum sesuai dengan standar kualitas air minum/air yang sudah diolah/dimasak', 'pp017', 3),

            Form::h(4, 'Tempat yang digunakan untuk menyajikan pangan'),

            Form::select('Piring bersih dan tara pangan/food grade', 'pp018', 3),
            Form::select('Gelas bersih dan tara pangan/food grade', 'pp019', 3),
            Form::select('Sendok bersih dan tara pangan/food grade', 'pp020', 3),
            Form::select('Sedotan bersih dan tara pangan/food grade', 'pp021', 3),

            // ####################################

            Form::h(3, 'D Dokumentasi dan Rekaman (diakses di ruangan adminitrasi)', 'dokumentasi-rekaman'),

            Form::h(4, 'Khusus Restoran Hotel'),

            Form::select('Tersedia dokumentasi/jadwal pemeliharaan bangunan', 'drh001'),
            Form::select('Tersedia dokumentasi/jadwal pembersihan dan sanitasi', 'drh002', 2),
            Form::select('Tersedia dokumentasi/jadwal pemeliharaan peralatan seperti pengecekan suhu alat pendingin (kalibrasi)', 'drh003', 2),
            Form::select('Tersedia dokumentasi/jadwal pemeliharaan sistem penanganan limbah', 'drh004'),
            Form::select('Tersedia dokumentasi/jadwal pengendalian vektor dan binatang pembawa penyakit', 'drh005', 2),
            Form::select('Tersedia dokumentasi penerimaan bahan pangan', 'drh006'),
            Form::select('Tersedia hasil analisis pengujian air yang sesuai dengan persyaratan air minum', 'drh007', 3),
            Form::select('Tersedia dokumentasi pengawasan internal secara berkala (menggunakan buku rapor)', 'drh008', 2),
            Form::select('Tersedia pesan-pesan tentang higiene sanitasi bagi penjamah pangan', 'drh009'),

            // ########################

            Form::h(3, 'Rekaman Personel', 'rekaman-personel'),

            Form::select('Sehat dan bebas dari penyakit menular (contohnya diare, demam tifoid/tifus, hepatitis A dan lain-lain dibuktikan dengan surat keterangan sehat', 'rp001', 2),
            Form::select('Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 50%)', 'rp002', 3),

            Form::h(4, 'Untuk Restoran Hotel'),

            Form::select('Penjamah pangan dilakukan pemeriksaan kesehatan di awal masuk kerja dibuktikan dengan surat keterangan sehat dari fasyankes', 'rp003', 2),
            Form::select('Tersedia jadwal/program pelatihan untuk penjamah pangan', 'rp004', 2),
            Form::select('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali setahun', 'rp005', 2),

            // #########################

            Form::h(3, 'Keselamatan dan Kesehatan Kerja', 'keselamatan-dan-kesehatan-kerja'),

            Form::select('Tersedia alat pemadam api ringan (APAR) gas yang mudah dijangkau untuk situasi darurat disertai dengan petunjuk penggunaan yang jelas', 'kk001'),
            Form::select('Tersedia personil yang bertanggung jawab dan dapat menggunakan APAR', 'kk002'),
            Form::select('APAR tidak kadaluwarsa', 'kk003'),
            Form::select('Tersedia perlengkapan P3K dan obat-obatan yang tidak kadaluwarsa', 'kk004'),
            Form::select('Tersedia petunjuk jalur evakuasi yang jelas pada setiap ruangan ke arah titik kumpul', 'kk005'),
            Form::select('Menerapkan kawasan tanpa rokok (KTR)', 'kk006'),
        ];
    }

    protected function formPenilaianName()
    {
        return [
            //  inspeksi area luar tpp
            'l001',
            'l002',
            'l003',
            //  inspeksi area pelayanan konsumen
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
            'p022',
            'p023',
            'p024',
            'p025',
            'p026',
            //  a fasilitas karyawan
            'fk001',
            'fk002',
            'fk003',
            'fk004',
            'fk005',
            'fk006',
            //  b area penerimaan bahan baku
            'apbb001',
            'apbb002',
            'apbb003',
            'apbb004',
            'apbb005',
            'apbb006',
            'apbb007',
            'apbb008',
            'apbb009',
            'apbb010',
            'apbb011',
            'apbb012',
            'apbb013',
            'apbb014',
            'apbb015',
            //  a area penyimpanan
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
            'ap014',
            'ap015',
            'ap016',
            'ap017',
            'ap018',
            'ap019',
            'ap020',
            'ap021',
            'ap022',
            'ap023',
            'ap024',
            'ap025',
            'ap026',
            'ap027',
            'ap028',
            'ap029',
            //  area penyimpanan bahan pangan
            'ap030',
            'ap031',
            'ap032',
            'ap033',
            'ap034',
            'ap035',
            'ap036',
            'ap037',
            'ap038',
            'ap039',
            'ap040',
            //  area penyimpanan kemasan
            'pk001',
            'pk002',
            'pk003',
            'pk004',
            'pk005',
            'pk006',
            //  area penyimpanan bahan kimia non pangan
            'bp001',
            'bp002',
            'bp003',
            //  b area pencucian
            'pc001',
            'pc002',
            'pc003',
            'pc004',
            'pc005',
            'pc006',
            'pc007',
            'pc008',
            'pc009',
            'pc010',
            'pc011',
            'pc012',
            'pc013',
            'pc014',
            //  c area persiapan pengolahan dan pengemasan pangan umum
            'pg001',
            'pg002',
            'pg003',
            'pg004',
            'pg005',
            'pg006',
            'pg007',
            'pg008',
            'pg009',
            'pg010',
            'pg011',
            'pg012',
            'pg013',
            'pg014',
            'pg015',
            'pg016',
            'pg017',
            'pg018',
            'pg019',
            'pg020',
            'pg021',
            'pg022',
            'pg023',
            'pg024',
            'pg025',
            'pg026',
            'pg027',
            'pg028',
            'pg029',
            'pg030',
            'pg031',
            'pg032',
            'pg033',
            'pg034',
            'pg035',
            'pg036',
            'pg037',
            'pg038',
            'pg039',
            'pg040',
            'pg041',
            'pg042',
            'pg043',
            'pg044',
            'pg045',
            //  fasilitas higiene sanitasi personel
            'fh001',
            'fh002',
            'fh003',
            'fh004',
            'fh005',
            'fh006',
            'fh007',
            'fh008',
            'fh009',
            'fh010',
            'fh011',
            'fh012',
            //  peralatan
            'pr001',
            'pr002',
            'pr003',
            'pr004',
            'pr005',
            'pr006',
            'pr007',
            'pr008',
            'pr009',
            'pr010',
            'pr011',
            'pr012',
            'pr013',
            //  penyimpanan pangan matang
            'pm001',
            'pm002',
            'pm003',
            'pm004',
            'pm005',
            'pm006',
            'pm007',
            'pm008',
            //  pengemasan pangan matang
            'pp001',
            'pp002',
            'pp003',
            'pp004',
            //  pengangkutan pangan matang
            'pp005',
            'pp006',
            'pp007',
            'pp008',
            'pp009',
            'pp010',
            //  penyajian pangan matang
            'pp011',
            'pp012',
            'pp013',
            'pp014',
            'pp015',
            'pp016',
            'pp017',
            'pp018',
            'pp019',
            'pp020',
            'pp021',
            //  d dokumentasi dan rekaman diakses di ruangan administrasi
            'drh001',
            'drh002',
            'drh003',
            'drh004',
            'drh005',
            'drh006',
            'drh007',
            'drh008',
            'drh009',
            //  rekaman personel
            'rp001',
            'rp002',
            'rp003',
            'rp004',
            'rp005',
            //  e keselamatan dan kesehatan kerja
            'kk001',
            'kk002',
            'kk003',
            'kk004',
            'kk005',
            'kk006',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Restoran::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Restoran',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Restoran', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Nomor Izin Usaha', $item['u006'] ?? '-'],
                        ['Penjamah Pangan (Bersertifikat/Total)', $item['u005'] . '/' . $item['u004']],
                        ['Tipe Restoran', $item['u009']],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                ])->download('BAIKL_RESTORAN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return Restoran::withTrashed()->get()->map(function ($item) {
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
                            'Tipe Restoran',

                            'Lokasi bebas banjir',
                            'Lokasi bebas dari pencemaran bau/asap/debu/kotoran',
                            'Lokasi bebas dari sumber vektor dan binatang pembawa penyakit',
                            'Area ruang makan konsumen bersih',
                            'Meja makan bersih',
                            'Kursi/alas duduk bersih',
                            'Peralatan makan bersih dan tertutup',
                            'Tempat bumbu tertutup dan bersih',
                            'Luas ruangan sesuai dengan rasio kapasitas tempat duduk',
                            'Dinding ruang makan bersih (jika tidak ada dinding, dapat diabaikan)',
                            'Memiliki tempat sampah',
                            'Tertutup dan tidak rusak',
                            'Tidak dibuka dengan tangan (dibuka dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pengangkutan minimal 1 x 24 jam)',
                            'Ventilasi udara baik (bisa menggunakan ventilasi alami atau buatan)',
                            'Toilet tidak berhubungan langsung dengan area makan atau ada upaya penyekatan jamban/toilet',
                            'Toilet dilengkapi dengan sabun',
                            'Tersedia air mengalir',
                            'Dilengkapi tempat sampah',
                            'Memiliki wastafel untuk cuci tangan konsumen',
                            'Dilengkapi sabun cuci tangan',
                            'Dilengkapi air mengalir',
                            'Mudah dibersihkan',
                            'Dilengkapi tisu/pengering tangan',
                            'Ada petunjuk atau pedoman cara cuci tangan (poster atau tulisan)',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Personel yang melayani pembayaran tidak menyentuh pangan secara langsung setelah menyentuh uang/kartu pembayaran/mesin pembayaran',
                            'Terdapat loker',
                            'Terpisah antara laki-laki dan perempuan',
                            'Terdapat aturan tertulis penggunaan loker',
                            'Loker tidak digunakan sebagai tempat penyimpanan makanan',
                            'Loker tidak digunakan sebagai tempat penyimpanan peralatan pengolahan pangan',
                            'Terdapat tempat istirahat untuk karyawan/penjamah pangan',
                            'Area penerimaan bersih',
                            'Kendaraan untuk mengangkut bahan pangan bersih, tidak digunakan untuk selain bahan pangan',
                            'Transit time cukup untuk memastikan bahan baku yang memerlukan pengendalian suhu (suhu chiller dan freezer) tidak rusak',
                            'Suhu kendaraan yang mengangkut pangan segar (jika kondisi suhu dikendalikan sesuai suhu chiller atau freezer) harus sesuai',
                            'Bahan pangan pada saat diterima berada pada wadah dan suhu yang sesuai dengan jenis pangan',
                            'Memiliki label',
                            'Terdaftar atau ada izin edar',
                            'Tidak kadaluwarsa',
                            'Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)',
                            'Bahan pangan yang tidak dikemas/berlabel berasal dari sumber yang jelas/dipercaya',
                            'Jika bahan pangan tidak langsung digunakan maka bahan pangan diberikan label tanggal penerimaan ',
                            'Tidak menggunakan makanan sisa sebagai bahan pangan untuk diolah menjadi makanan baru ',
                            'Jika terdapat menu yang menggunakan bahan baku es batu, maka es batu kualitas air minum dan disimpan dalam tempat khusus',
                            'Air untuk pengolahan pangan memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Restoran hotel harus memiliki pintu masuk bahan baku dan pintu keluar pangan matang terpisah',
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
                            'Menggunakan kartu stok (FIFO/FEFO)',
                            'Sehat',
                            'Menggunakan APD (masker) dengan benar',
                            'Menggunakan pakaian kerja',
                            'Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)',
                            'Tersedia tempat sampah',
                            'Tertutup dan tidak rusak',
                            'Tidak dibuka dengan tangan',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Pembuangan minimal 1 X 24 jam',
                            'Memiliki ventilasi udara',
                            'Bahan kuat dan tahan lama',
                            'Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Metode pengendalian vektor dan binatang pembawa penyakit, tidak menggunakan racun, tetapi jebakan/perangkap yang tidak mengkontaminasi pangan',
                            'Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)',
                            'Bahan mentah dari hewan disimpan pada suhu ≤ 4°C',
                            'Bahan mentah lain yang membutuhkan pendinginan, misalnya sayuran harus disimpan pada suhu yang sesuai',
                            'Bahan pangan beku yang tidak langsung digunakan disimpan pada suhu ≤18°C atau dibawahnya ',
                            'Semua bahan pangan disimpan pada rak-rak (pallet) dengan jarak minimal 15 cm dari lantai, 5 cm dari dinding dan 60 cm dari langit-langit',
                            'Suhu gudang bahan pangan kering dan kaleng dijaga kurang dari 25°C',
                            'Tidak terdapat bahan baku pangan yang kadaluwarsa (FIFO/FEFO tidak berjalan)',
                            'Tidak terdapat pangan yang busuk',
                            'Khusus menyimpan bahan baku (tidak menyatu dengan pangan matang)',
                            'Chiller/freezer atau termometer untuk monitoring sudah dikalibrasi',
                            'Suhu chiller sesuai (≤ 4°C) & terdapat dokumen monitoringnya yang dilakukan setiap hari',
                            'Suhu freezer sesuai (≤ -18°C) & terdapat dokumen monitoringnya yang dilakukan setiap hari',
                            'Terdapat area khusus penyimpanan kemasan',
                            'Kemasan produk akhir disimpan dalam kondisi terkemas rapat untuk menghindari kontaminasi',
                            'Tidak menempel dinding (minimal jarak 5 cm dari dinding)',
                            'Tidak diletakkan langsung di atas lantai / menggunakan pallet (minimal 15 cm dari lantai)',
                            'Tidak menempel langit-langit (minimal 60 cm dari langit-langit)',
                            'Kemasan untuk pangan harus food grade',
                            'Terdapat area/ruangan khusus penyimpanan bahan kimia non pangan',
                            'Ruangan penyimpanan memiliki akses terbatas (dikunci atau dengan metode lainnya yang sesuai)',
                            'Bahan kimia memiliki label yang memuat informasi tentang identitas dan cara penggunaan',
                            'Area/tempat pencucian peralatan terpisah dengan area/tempat pencucian pangan',
                            'Area pencucian peralatan dan pangan tidak digunakan untuk sanitasi karyawan seperti cuci tangan',
                            'Sarana pencucian peralatan terbuat dari bahan yang kuat, permukaan halus dan mudah dibersihkan',
                            'Proses pencucian peralatan dilakukan dengan 3 (tiga) proses yaitu pencucian, pembersihan dan sanitasi',
                            'Penggunaan disinfektan untuk pencucian bahan pangan, takarannya sesuai dengan persyaratan kesehatan/standar desinfektan',
                            'Pencucian bahan pangan menggunakan air dengan kualitas air minum/air yang sudah diolah/dimasak',
                            'Saluran air limbah terdapat grease trap/penangkap lemak',
                            'Tersedia tempat sampah',
                            'Tertutup dan tidak rusak',
                            'Tidak dibuka dengan tangan (dibuka dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pengangkutan keluar minimal 1 x 24 jam)',
                            'Pengeringan dengan menggunakan lap/kain majun yang bersih dan diganti secara rutin',
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
                            'Sehat',
                            'Celemek',
                            'Masker',
                            'Hairnet/penutup rambut',
                            'Menggunakan pakaian kerja yang hanya digunakan di tempat kerja',
                            'Berkuku pendek, bersih dan tidak memakai pewarna kuku',
                            'Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan',
                            'Tidak menggunakan perhiasan dan aksesoris  lain  (cincin,  gelang,  bros,  dan lain-lain) ketika mengolah pangan ',
                            'Tidak merokok',
                            'Tidak bersin atau batuk di atas pangan langsung',
                            'Tidak meludah',
                            'Tidak mengunyah makanan/permen',
                            'Menggaruk-garuk atau menyentuh anggota badan yang kotor dan kemudian menyentuh pangan langsung',
                            'Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                            'Jika  terluka  maka  luka  ditutup  dengan perban/sejenisnya  dan  ditutup  penutup tahan air dan kondisi bersih',
                            'Cukup terang',
                            'Lampu tercover di semua area dan cover tidak terbuat dari bahan kaca/yang mudah pecah',
                            'Tersedia tempat sampah',
                            'Tertutup dan tidak rusak',
                            'Desain tiadk berlubang-lubang',
                            'Tidak dibuka dengan tangan (dibuka dengan pedal kaki)',
                            'Dilapisi plastik',
                            'Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)',
                            'Tidak ada tumpukan sampah (pembuangan minimal 1 x 24 jam)',
                            'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                            'Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengkontaminasi pangan',
                            'Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar)',
                            'Pembuangan asap dapur dikeluarkan melalui cerobong yang dilengkapi dengan sungkup asap atau penyedot udara',
                            'Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak',
                            'Melakukan thawing/pelunakan pangan dengan benar',
                            'Pangan dimasak sampai matang sempurna',
                            'Penyiapan buah dan sayuran segar yang langsung dikonsumsi dicuci dengan menggunakan air standar kualitas air minum',
                            'Memiliki wastafel yang mudah diakses',
                            'Petunjuk cuci tangan',
                            'Sabun cuci tangan',
                            'Tersedia air mengalir',
                            'Pengering tangan (bisa hand dryer atau tisu dan bukan serbet)',
                            'Tersedia  toilet  (jika  rumah  makan  merupakan satu kesatuan dengan manajemen gedung maka tetap akses ke jamban/toilet mudah untuk dijangkau)',
                            'Jumlah cukup',
                            'Toilet tidak membuka langsung ke ruang pengolahan/ventilasi toilet tidak membuka langsung ke area pengolahan',
                            'Dilengkapi dengan wastafel dan fasilitasnya (sabun, air mengalir dan pengering) atau sejenisnya',
                            'Dilengkapi petunjuk cuci tangan setelah dari toilet',
                            'Dilengkapi pengering tangan (bisa hand dryer atau tissue dan bukan serbet)',
                            'Toilet terpisah antara laki-laki dan perempuan',
                            'Bahan kuat',
                            'Tidak terbuat dari kayu (contoh: talenan, alat pengaduk)',
                            'Tidak berkarat',
                            'Tara pangan (food grade)',
                            'Bersih sebelum digunakan',
                            'Setelah digunakan kondisi bersih dan kering',
                            'Berbeda untuk pangan matang dan pangan mentah',
                            'Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade',
                            'Tersedia termometer yang berfungsi dan akurat ',
                            'Peralatan personal, peralatan kantor, dan lain-lain yang tidak diperlukan tidak diletakkan di area pengolahan pangan',
                            'Wadah/pengangkut peralatan makan/minum kotor terbuat dari bahan yang kuat dan mudah dibersihkan',
                            'Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang',
                            'Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)',
                            'Penyimpanan pangan matang tidak dicampur dengan bahan pangan mentah',
                            'Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan',
                            'Khusus menyimpan pangan matang dengan kondisi terkemas',
                            'Suhu chiller/freezer atau termometer untuk monitoring sudah dikalibrasi',
                            'Suhu chiller sesuai (≤ 4°C)',
                            'Terdapat dokumen monitoring chiller yang dilakukan setiap hari',
                            'Suhu freezer sesuai (≤ -18°C)',
                            'Terdapat dokumen monitoring freezer yang dilakukan setiap hari',
                            'Pengemasan dilakukan secara higiene (personel cuci tangan dan menggunakan sarung tangan dengan kondisi baik)',
                            'Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)',
                            'Terdapat keterangan tanda batas waktu (expired dated) tanggal dan waktu makanan boleh dikonsumsi',
                            'Terdapat keterangan nomor sertifikat laik higiene sanitasi',
                            'Selama pengangkutan, pangan harus dilindungi dari debu dan jenis kontaminasi lainnya (termasuk penjualan online) atau dikemas rapat',
                            'Bebas dari debu',
                            'Bebas dari bebas vektor dan binatang pembawa penyakit',
                            'Bebas dari bahan kimia non pangan',
                            'Dilakukan sanitasi secara rutin',
                            'Pangan matang diangkut pada suhu yang sesuai menggunakan tempat yang dapat menjaga suhu panas dan atau dingin',
                            'Pangan matang yang mudah rusak harus sudah dikonsumsi 4 jam setelah matang',
                            'Pangan matang panas dijaga pada suhu > 60°C',
                            'Pangan matang dingin dijaga pada suhu < 5°C',
                            'Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5°C (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)',
                            'Jika menggunakan es batu yang dicampur dengan pangan matang, maka es batu harus dibuat dari air yang memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi',
                            'Air untuk minum sesuai dengan standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Piring bersih dan tara pangan/food grade',
                            'Gelas bersih dan tara pangan/food grade',
                            'Sendok bersih dan tara pangan/food grade',
                            'Sedotan bersih dan tara pangan/food grade',
                            'Tersedia dokumentasi/jadwal pemeliharaan bangunan',
                            'Tersedia dokumentasi/jadwal pembersihan dan sanitasi',
                            'Tersedia dokumentasi/jadwal pemeliharaan peralatan seperti pengecekan suhu alat pendingin (kalibrasi)',
                            'Tersedia dokumentasi/jadwal pemeliharaan sistem penanganan limbah',
                            'Tersedia dokumentasi/jadwal pengendalian vektor dan binatang pembawa penyakit',
                            'Tersedia dokumentasi penerimaan bahan pangan',
                            'Tersedia hasil analisis pengujian air yang sesuai dengan persyaratan air minum',
                            'Tersedia dokumentasi pengawasan internal secara berkala (menggunakan buku rapor)',
                            'Tersedia pesan-pesan tentang higiene sanitasi bagi penjamah pangan',
                            'Sehat dan bebas dari penyakit menular (contohnya diare, demam tifoid/tifus, hepatitis A dan lain-lain dibuktikan dengan surat keterangan sehat',
                            'Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 50%)',
                            'Penjamah pangan dilakukan pemeriksaan kesehatan di awal masuk kerja dibuktikan dengan surat keterangan sehat dari fasyankes',
                            'Tersedia jadwal/program pelatihan untuk penjamah pangan',
                            'Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali setahun',
                            'Tersedia alat pemadam api ringan (APAR) gas yang mudah dijangkau untuk situasi darurat disertai dengan petunjuk penggunaan yang jelas',
                            'Tersedia personil yang bertanggung jawab dan dapat menggunakan APAR',
                            'APAR tidak kadaluwarsa',
                            'Tersedia perlengkapan P3K dan obat-obatan yang tidak kadaluwarsa',
                            'Tersedia petunjuk jalur evakuasi yang jelas pada setiap ruangan ke arah titik kumpul',
                            'Menerapkan kawasan tanpa rokok (KTR)',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_RESTORAN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create(Request $request)
    {
        $tipe = ucfirst(strtolower($request->query('tipe', 'umum')));

        $formPenilaian = $this->formPenilaian();

        switch ($tipe) {
            case 'Umum':
                $bukanTipeUmum = [];
                foreach (
                    [
                        //  a area penyimpanan
                        'apbb015',
                        //  d dokumentasi dan rekaman diakses di ruangan administrasi
                        'drh001',
                        'drh002',
                        'drh003',
                        'drh004',
                        'drh005',
                        'drh006',
                        //  rekaman personel
                        'rp004',
                        'rp005',
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
            case 'Hotel':
                //  inspeksi area pelayanan konsumen
                unset($formPenilaian[28]);
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada restoran dengan tipe tersebut');
        }

        return view('pages.inspection.restoran.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'tipe' => strtoupper($tipe),
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
        
        // Handle instansi lainnya
        if ($request->input('instansi-pemeriksa') === 'Lainnya' && $request->filled('instansi-lainnya')) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / ['UMUM' => 432, 'HOTEL' => 445][strtoupper($request->input('u009', 'UMUM'))]) * 100);

        $insert = Restoran::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Restoran gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('restoran.show', ['restoran' => $insert->id]))->with('success', 'penilaian / inspeksi Restoran ' . $request->input('u009') . ' berhasil dibuat');
    }

    public function show(Restoran $restoran)
    {
        return view('pages.inspection.show', [
            'page_name' => 'inspection',
            'form_data' => $restoran,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Restoran ' . ucfirst(strtolower($restoran['u009'])),
            'edit_route' => route('restoran.edit', ['restoran' => $restoran['id']]),
            'destroy_route' => route('restoran.destroy', ['restoran' => $restoran['id']]),
            'export_route' => route(
                'restoran.index',
                [
                    'export' => 'pdf',
                    'id' => $restoran['id'],
                ],
            ),
        ]);
    }

    public function edit(Restoran $restoran)
    {
        $formPenilaian = $this->formPenilaian();

        switch ($restoran->u009) {
            case 'UMUM':
                $bukanTipeUmum = [];
                foreach (
                    [
                        //  a area penyimpanan
                        'apbb015',
                        //  d dokumentasi dan rekaman diakses di ruangan administrasi
                        'drh001',
                        'drh002',
                        'drh003',
                        'drh004',
                        'drh005',
                        'drh006',
                        //  rekaman personel
                        'rp004',
                        'rp005',
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
            case 'HOTEL':
                //  inspeksi area pelayanan konsumen
                unset($formPenilaian[28]);
                break;
            default:
                return redirect(route('inspection'))->with('error', 'tidak ada restoran dengan tipe tersebut');
        }

        return view('pages.inspection.restoran.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $formPenilaian,
            'form_data' => $restoran,
        ]);
    }

    public function update(Request $request, Restoran $restoran)
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
        
        // Handle file upload if present
        if ($request->hasFile('dokumen_slhs')) {
            $file = $request->file('dokumen_slhs');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }
        
        // Auto-calculate expire date from issued date + 1 year
        if (!empty($data['slhs_issued_date'])) {
            $issuedDate = \Carbon\Carbon::parse($data['slhs_issued_date']);
            $data['slhs_expire_date'] = $issuedDate->addYear()->format('Y-m-d');
        }

        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        // Calculate score based on type
        $scoreMap = ['UMUM' => 432, 'HOTEL' => 445];
        $totalQuestions = $scoreMap[strtoupper($request->input('u009', 'UMUM'))];
        $totalScore = array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0), 0);
        $data['skor'] = (int) (100 - ($totalScore / $totalQuestions) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($restoran->kelurahan)) {
                $data['kelurahan'] = $restoran->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($restoran->kecamatan)) {
                $data['kecamatan'] = $restoran->kecamatan;
            }
            if (empty($data['subjek']) && !empty($restoran->subjek)) {
                $data['subjek'] = $restoran->subjek;
            }
            if (empty($data['alamat']) && !empty($restoran->alamat)) {
                $data['alamat'] = $restoran->alamat;
            }

            $insert = Restoran::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Restoran gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('restoran.show', ['restoran' => $insert->id]))->with('success', 'penilaian / inspeksi Restoran berhasil dibuat');
        }

        // Remove user_id and action from update data to preserve original user
        unset($data['user_id']);
        unset($data['action']);
        
        $update = $restoran->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Restoran gagal diubah, silahkan coba lagi');
        }

        return redirect(route('restoran.show', ['restoran' => $restoran['id']]))->with('success', 'form informasi dan penilaian Restoran ' . $request->input('u009') . ' berhasil diubah');
    }

    public function destroy(String $id)
    {
        $restoran = Restoran::where('id', $id)->withTrashed()->first();

        if ($restoran['deleted_at']) {
            $restoran->update(['deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $restoran->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Restoran berhasil dihapus');
    }
}