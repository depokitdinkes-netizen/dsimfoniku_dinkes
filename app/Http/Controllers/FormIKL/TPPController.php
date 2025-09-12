<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\TPP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TPPController extends Controller
{
    protected function h($int, $label, $id = "")
    {
        return [
            'type' => 'h' . $int,
            'label' => $label,
            'id' => $id
        ];
    }

    protected function p($label)
    {
        return [
            'type' => 'p',
            'label' => $label,
        ];
    }

    protected function input($type, $label, $name)
    {
        return [
            'type' => $type,
            'label' => $label,
            'name' => $name,
        ];
    }

    protected function select($label, $name, $tidak_sesuai = 1)
    {
        return [
            'type' => 'select',
            'label' => $label,
            'name' => $name,
            'option' => [
                [
                    'label' => 'Sesuai',
                    'value' => 0,
                ],
                [
                    'label' => 'Tidak sesuai',
                    'value' => $tidak_sesuai,
                ],
            ],
        ];
    }

    protected function informasiUmum()
    {
        return [
            $this->input('text', 'Nama TPP Tertentu', 'u001'),
            $this->input('text', 'Alamat', 'u002'),
            $this->input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'u003'),
            $this->input('number', 'Jumlah Penjamah Pangan', 'u004'),
            $this->input('text', 'Nomor Izin Usaha', 'u005'),
            $this->input('text', 'Nama Pemeriksa', 'u006'),
            $this->input('text', 'Produk yang dijual', 'u007'),
            $this->input('date', 'Tanggal Penilaian', 'u008'),
            $this->input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            $this->input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            $this->input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
        ];
    }

    protected function formPenilaian()
    {
        return  $formPenilaian = [
            $this->h(2, 'Inspeksi Area Luar TPP'),

            $this->h(3, 'A Lokasi Sekitar TPP', 'lokasi-sekitar-tpp'),

            $this->select('Lokasi bebas banjir', 'lk001', 3),
            $this->select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'lk002'),
            $this->select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'lk003'),
            $this->select('Bangunan memiliki pagar pembatas', 'lk004'),
            $this->select('Area parkir kendaraan jauh dari pintu masuk bangunan pengolahan', 'lk005'),
            $this->select('Halaman bangunan bebas vektor dan binatang pembawa penyakit atau binatang peliharaan', 'lk006'),
            $this->select('Jika halaman memiliki tanaman, tanaman tidak menempel bangunan/dinding produksi', 'lk007'),

            $this->h(4, 'Tempat sampah'),

            $this->select('Tertutup rapat', 'lk008'),
            $this->select('Tidak ada bau yang menyengat', 'lk009'),
            $this->select('Tidak ada tumpukan sampah (pembuangan minimal 1 x 24 jam)', 'lk010'),

            $this->h(4, 'Drainase di area luar'),

            $this->select('Bersih', 'lk011'),
            $this->select('Tidak ada luapan air/sumbatan', 'lk012'),

            $this->h(3, 'B Bangunan dan Fasilitasnya', 'bangunan-dan-fasilitasnya'),

            $this->select('Dinding bagian luar tidak ada retakan yang membuka ke bagian dalam area bangunan', 'bf001'),

            $this->h(4, 'Plafon bangunan luar'),

            $this->select('Tidak ada lubang ke area dalam bangunan (tempat sarang atau akses bebas vektor dan binatang pembawa penyakit masuk ke area pengolahan)', 'bf002'),
            $this->select('Tidak ada sawang/bebas kotoran', 'bf003'),

            $this->h(4, 'Pintu masuk TPP'),

            $this->select('Bahan kuat dan tahan lama', 'bf004'),
            $this->select('Desain halus/rata', 'bf005'),
            $this->select('Dapat menutup rapat', 'bf006'),
            $this->select('Membuka ke arah luar', 'bf007'),
            $this->select('Selalu tertutup untuk menghindari akses vektor dan binatang pembawa penyakit (atau memiliki penghalang vektor dan binatang pembawa penyakit seperti plastik curtain)', 'bf008'),
            $this->select('Pintu masuk bahan baku dan produk matang dibuat terpisah', 'bf009'),

            $this->h(4, 'Memiliki ventilasi udara (jendela/exhaust/AC/lainnya)'),

            $this->select('Bahan kuat dan tahan lama', 'bf010'),
            $this->select('Jika terbuka, memiliki kasa anti serangga yang mudah dilepas dan dibersihkan', 'bf011'),
            $this->select('Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih', 'bf012'),

            $this->select('Tersedia wastafel sebelum masuk ruang atau bangunan pengolahan', 'bf013'),

            $this->h(4, 'Wastafel'),

            $this->select('Terdapat petunjuk cuci tangan', 'bf014'),
            $this->select('Terdapat sabun cuci tangan', 'bf015', 2),
            $this->select('Tersedia air mengalir', 'bf016', 2),
            $this->select('Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)', 'bf017'),
            $this->select('Bahan kuat', 'bf018'),
            $this->select('Desain mudah dibersihkan', 'bf019'),

            $this->h(3, 'C Penanganan Pangan', 'penanganan-pangan'),

            $this->select('Tidak ada pengolahan pangan di area luar bangunan yang tidak memiliki pelindung', 'pp001', 3),
            $this->select('Pangan matang tidak disimpan di area luar bangunan dalam kondisi terbuka', 'pp002', 3),

            $this->h(3, 'D Fasilitas Karyawan', 'fasilitas-karyawan'),

            $this->h(4, 'Loker Karyawan (jika lokasi TPP di dalam gedung, posisi loker tidak boleh menyebabkan kontaminasi silang)'),

            $this->select('Terdapat loker karyawan terpisah (perempuan dan laki-laki)', 'fk001'),
            $this->select('Terdapat tata tertib penggunaan loker', 'fk002'),
            $this->select('Loker tidak digunakan sebagai tempat penyimpanan makanan', 'fk003'),
            $this->select('Loker tidak digunakan sebagai tempat penyimpanan peralatan pengolahan pangan', 'fk004', 2),
            $this->select('Terdapat titik kumpul jika terjadi kejadian darurat', 'fk005'),

            $this->h(3, 'E Area Penerimaan Bahan Baku', 'area-penerimaan-bahan-baku'),

            $this->select('Area penerimaan bersih', 'ab001'),
            $this->select('Kendaraan untuk mengangkut bahan pangan bersih, tidak digunakan untuk selain bahan pangan', 'ab002', 2),

            $this->h(3, 'F Persyaratan Bahan Baku', 'persyaratan-bahan-baku'),

            $this->select('Bahan pangan saat diterima berada pada wadah dan suhu yang sesuai dengan jenis pangan', 'pb001', 2),

            $this->h(4, 'Bahan baku pangan dalam kemasan'),

            $this->select('Memiliki label', 'pb002', 2),
            $this->select('Terdaftar atau ada izin edar', 'pb003', 2),
            $this->select('Tidak kedaluwarsa', 'pb004', 2),
            $this->select('Kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)', 'pb005', 2),
            $this->select('Bahan pangan yang tidak dikemas/berlabel berasal dari sumber yang jelas/dipercaya', 'pb006', 2),
            $this->select('Jika bahan pangan tidak langsung digunakan maka bahan pangan diberikan label tanggal penerimaan', 'pb007'),
            $this->select('Tidak menggunakan makanan sisa yang sudah busuk sebagai bahan pangan untuk diolah menjadi makanan baru', 'pb008', 2),
            $this->select('Air untuk pengolahan pangan menggunakan air kualitas air minum/air sudah diolah/dimasak', 'pb009', 2),

            $this->h(2, 'Inspeksi Area Pengolahan'),

            $this->h(3, 'A Area Penyimpanan', 'area-penyimpanan'),

            $this->h(4, 'Dinding ruang penyimpanan'),

            $this->select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'ap001'),
            $this->select('Tidak retak', 'ap002'),

            $this->h(4, 'Lantai ruang penyimpanan'),

            $this->select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'ap003'),
            $this->select('Tidak retak atau kuat', 'ap004'),
            $this->select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'ap005'),
            $this->select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'ap006'),

            $this->h(4, 'Langit-langit'),

            $this->select('Tinggi minimal 2,4 meter dari lantai', 'ap007'),
            $this->select('Bersih', 'ap008'),
            $this->select('Tertutup rapat', 'ap009'),
            $this->select('Tidak ada jamur', 'ap010', 2),
            $this->select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau bebas vektor dan binatang pembawa penyakit)', 'ap011'),
            $this->select('Tidak ada kondensasi air yang jatuh langsung ke bahan pangan', 'ap012', 2),

            $this->h(4, 'Personil yang bekerja pada area ini'),

            $this->select('Sehat', 'ap013', 2),
            $this->select('Menggunakan Alat Pelindung Diri/APD (masker) dengan benar', 'ap014'),
            $this->select('Menggunakan pakaian kerja', 'ap015'),

            $this->select('Pencahayaan cukup dan lampu tercover (cover terbuat dari material yang tidak mudah pecah)', 'ap016'),

            $this->h(4, 'Tempat sampah'),

            $this->select('Tertutup dan tidak rusak', 'ap017'),
            $this->select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'ap018'),
            $this->select('Dilapisi plastik', 'ap019'),
            $this->select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'ap020'),
            $this->select('Tidak ada tumpukan sampah (pengangkutan minimal 1 x 24 jam)', 'ap021'),

            $this->select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'ap022', 3),

            $this->select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengkontaminasi pangan', 'ap023', 3),

            $this->h(3, 'Area Penyimpanan Bahan Pangan', 'area-penyimpanan-bahan-pangan'),

            $this->h(4, 'Ruang penyimpanan bahan pangan'),

            $this->select('Bahan mentah dari hewan disimpan pada suhu ≤ 4°C', 'bp001', 2),
            $this->select('Bahan mentah lain yang membutuhkan pendinginan, misalnya sayuran harus disimpan pada suhu yang sesuai', 'bp002', 2),
            $this->select('Bahan pangan beku yang tidak langsung digunakan disimpan pada suhu -18°C atau dibawahnya', 'bp003', 2),
            $this->select('Mengunakan kartu stok', 'bp004'),
            $this->select('Disimpan di atas pallet (jarak minimal 15 cm dari lantai)', 'bp005'),
            $this->select('Jarak penyimpanan dengan dinding minimal 5 cm', 'bp006'),
            $this->select('Jarak penyimpanan dengan langit-langit minimal 60 cm', 'bp007'),
            $this->select('Suhu gudang bahan pangan kering dan kaleng dijaga kurang dari 25°C', 'bp008', 2),
            $this->select('Tidak terdapat bahan baku pangan yang kedaluwarsa', 'bp009', 2),
            $this->select('Tidak terdapat pangan yang busuk', 'bp010', 2),

            $this->h(4, 'Chiller/freezer (jika ada)'),

            $this->select('Khusus menyimpan bahan baku (tidak menyatu dengan pangan matang)', 'bp011', 3),
            $this->select('Chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'bp012'),
            $this->select('Suhu chiller sesuai (≤ 4°C)', 'bp013', 2),
            $this->select('Terdapat rekaman monitoring suhu chiller', 'bp014'),
            $this->select('Suhu freezer sesuai (≤ -18°C)', 'bp015', 2),
            $this->select('Terdapat rekaman monitoring suhu freezer', 'bp016'),

            $this->h(3, 'Area Penyimpanan Kemasan', 'area-penyimpanan-kemasan'),

            $this->select('Terdapat area khusus penyimpanan kemasan', 'ak001'),

            $this->h(4, 'Penyimpanan kemasan'),

            $this->select('Disimpan di atas palet (jarak minimal 15 cm dari lantai)', 'ak002'),
            $this->select('Jarak penyimpanan dengan dinding minimal 5 cm', 'ak003'),
            $this->select('Jarak penyimpanan dengan langit-langit minimal 60 cm', 'ak004'),
            $this->select('Kemasan khusus pangan atau food grade', 'ak005', 2),

            $this->h(3, 'Area Penyimpanan Bahan Kimia Non Pangan', 'area-penyimpanan-bahan-kimia-non-pangan'),

            $this->select('Terdapat area/ruangan khusus penyimpanan bahan kimia non pangan', 'bpk001', 2),
            $this->select('Ruangan penyimpanan bahan kimia non pangan memiliki akses terbatas (dikunci atau dengan metode lainnya yang sesuai)', 'bpk002', 2),
            $this->select('Bahan kimia memiliki label yang memuat informasi tentang identitas dan cara penggunaan', 'bpk003', 2),

            $this->h(3, 'B Area Pencucian', 'area-pencucian'),

            $this->select('Area/tempat pencucian peralatan terpisah dengan area/tempat pencucian pangan', 'bap001'),
            $this->select('Area pencucian peralatan dan pangan tidak digunakan untuk sanitasi karyawan seperti cuci tangan', 'bap002'),
            $this->select('Sarana pencucian peralatan terbuat dari bahan yang kuat, permukaan halus dan mudah dibersihkan', 'bap003'),
            $this->select('Proses pencucian peralatan dilakukan dengan 3 (tiga) proses yaitu pencucian, pembersihan dan sanitasi', 'bap004'),
            $this->select('Penggunaan disinfektan untuk pencucian bahan pangan, takarannya sesuai dengan persyaratan kesehatan/standar disinfektan', 'bap005', 2),
            $this->select('Pencucian bahan pangan menggunakan air kualitas air minum/air yang sudah diolah/dimasak', 'bap006', 2),

            $this->select('Tersedia tempat sampah', 'bap007', 2),
            $this->select('Tertutup dan tidak rusak', 'bap008'),
            $this->select('Tidak dibuka dengan tangan (dibuka dengan pedal kaki)', 'bap009'),
            $this->select('Dilapisi plastik', 'bap010'),
            $this->select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'bap011'),
            $this->select('Tidak ada tumpukan sampah (pengangkutan minimal 1 x 24 jam)', 'bap012'),

            $this->select('Pengeringan dengan menggunakan lap/kain majun yang bersih dan diganti secara rutin', 'bap013', 2),

            $this->h(3, 'C Area Persiapan, Pengolahan dan Pengemasan Pangan', 'area-persiapan-pengolahan-pengemasan-pangan'),

            $this->h(3, 'Umum'),

            $this->h(4, 'Dinding ruangan'),

            $this->select('Bersih (tidak ada kotoran, jamur atau cat mengelupas)', 'app001', 2),
            $this->select('Tidak retak', 'app002', 2),
            $this->select('Bagian dinding yang terkena percikan air/minyak dilapisi bahan kedap air/minyak', 'app003', 2),

            $this->h(4, 'Lantai ruangan'),

            $this->select('Bersih (tidak ada kotoran, jamur atau ceceran pangan yang mengerak)', 'app004', 2),
            $this->select('Tidak retak atau kuat', 'app005', 2),
            $this->select('Tidak ada genangan air (struktur lantai landai ke arah pembuangan air)', 'app006', 2),
            $this->select('Pertemuan dengan dinding tidak membentuk sudut mati (jika tidak demikian, maka pembersihan harus efektif)', 'app007'),

            $this->h(4, 'Langit-langit'),

            $this->select('Tinggi minimal 2,4 meter dari lantai', 'app008'),
            $this->select('Bersih', 'app009', 2),
            $this->select('Tertutup rapat', 'app010', 2),
            $this->select('Tidak ada jamur', 'app011', 2),
            $this->select('Permukaan rata (jika tidak rata maka harus bersih, bebas debu atau vektor dan binatang pembawa penyakit)', 'app012', 2),
            $this->select('Tidak ada kondensasi air yang langsung jatuh ke pangan', 'app013', 3),

            $this->select('Penyimpanan bahan yang akan diolah tidak langsung disimpan di atas lantai (harus menggunakan wadah atau alas)', 'app014', 2),

            $this->h(4, 'Personil yang bekerja pada area ini'),

            $this->select('Sehat', 'app015', 3),

            $this->h(4, 'Menggunakan Alat Pelindung Diri/APD'),

            $this->select('Celemek', 'app016', 2),
            $this->select('Masker', 'app017', 3),
            $this->select('Hairnet/penutup rambut', 'app018', 3),

            $this->select('Menggunakan pakaian kerja yang hanya digunakan di tempat kerja', 'app019', 2),
            $this->select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'app020', 3),
            $this->select('Selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'app021', 3),
            $this->select('Tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros, dan lain-lain) ketika mengolah pangan', 'app022', 3),

            $this->h(4, 'Pada saat mengolah pangan tidak'),

            $this->select('merokok', 'app023', 3),
            $this->select('bersin atau batuk di atas pangan langsung', 'app024', 3),
            $this->select('meludah', 'app025', 3),
            $this->select('mengunyah makanan/permen', 'app026', 3),
            $this->select('Tidak menangani pangan langsung setelah menggaruk-garuk anggota badan tanpa mencuci tangan atau menggunakan hand sanitizer terlebih dahulu', 'app027', 3),
            $this->select('Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contohnya sendok, penjapit makanan)', 'app028', 3),
            $this->select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'app029', 3),

            $this->h(4, 'Pencahayaan'),

            $this->select('Cukup terang', 'app030'),
            $this->select('Lampu tercover disemua area dan cover tidak terbuat dari bahan kaca/yang mudah pecah', 'app031', 2),
            $this->select('Sumber pencahayaan alami seperti jendela tidak terbuka atau membuka langsung ke area luar', 'app032', 2),

            $this->h(4, 'Tempat sampah'),

            $this->select('Tertutup dan tidak rusak', 'app033'),
            $this->select('Desain tidak berlubang', 'app034'),
            $this->select('Tidak dibuka dengan tangan (bisa dengan pedal kaki)', 'app035', 3),
            $this->select('Dilapisi plastik', 'app036'),
            $this->select('Dipisahkan antara sampah basah (organik) dan sampah kering (anorganik)', 'app037'),
            $this->select('Tidak tumpukan sampah (pembuangan minimal 1 x 24 jam)', 'app038', 2),

            $this->select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'app039', 3),
            $this->select('Metode pengendalian vektor dan binatang pembawa penyakit tidak menggunakan racun tetapi jebakan/perangkap yang tidak mengkontaminasi pangan', 'app040', 3),
            $this->select('Bahan kimia non pangan yang digunakan pada area ini memiliki label identitas dengan volume sesuai penggunaan harian (bukan kemasan besar', 'app041', 3),
            $this->select('Pembuangan asap area pengolahan dikeluarkan melalui cerobong yang dilengkapi dengan sungkup asap atau penyedot udara', 'app042', 2),
            $this->select('Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak', 'app043', 2),
            $this->select('Melakukan thawing/pelunakan dengan benar', 'app044', 2),
            $this->select('Pangan dimasak sampai matang sempurna', 'app045'),

            $this->h(3, 'Fasilitas Higiene Sanitasi Personel', 'fasilitas-higiene-sanitasi-personel'),

            $this->h(4, 'Wastafel'),

            $this->select('Terdapat petunjuk cuci tangan', 'fhsp001', 2),
            $this->select('Terdapat sabun cuci tangan', 'fhsp002', 3),
            $this->select('Tersedia air mengalir', 'fhsp003', 3),
            $this->select('Tersedia pengering tangan (bisa hand dryer atau tisu, tetapi tidak boleh kain serbet)', 'fhsp004', 2),
            $this->select('Bahan kuat', 'fhsp005'),
            $this->select('Desain mudah dibersihkan', 'fhsp006'),
            $this->select('Terdapat toilet dan tidak membuka langsung ke ruang pengolahan pangan', 'fhsp007', 3),

            $this->h(4, 'Desain'),

            $this->select('Kuat', 'fhsp008'),
            $this->select('Permukaan halus', 'fhsp009'),
            $this->select('Mudah dibersihkan', 'fhsp010'),
            $this->select('Jumlah cukup', 'fhsp011'),

            $this->h(4, 'Tersedia'),

            $this->select('Air mengalir', 'fhsp012', 3),
            $this->select('Sabun cuci tangan', 'fhsp013', 3),
            $this->select('Tempat sampah', 'fhsp014'),
            $this->select('Tisu/pengering', 'fhsp015', 2),
            $this->select('Dilengkapi petunjuk cuci tangan setelah dari toilet', 'fhsp016', 2),
            $this->select('Dilengkapi wastafel dan fasilitasnya (sabun & air mengalir) untuk cuci tangan', 'fhsp017', 2),

            $this->h(3, 'Peralatan', 'peralatan'),

            $this->h(4, 'Peralatan untuk pengolahan pangan'),

            $this->select('Bahan kuat', 'pup001', 2),
            $this->select('Tidak terbuat dari kayu (contoh: talenan, alat pengaduk)', 'pup002', 3),
            $this->select('Tidak berkarat', 'pup003', 3),
            $this->select('Tara pangan (food grade)', 'pup004', 3),
            $this->select('Bersih sebelum digunakan', 'pup005', 3),
            $this->select('Setelah digunakan kondisi bersih dan kering', 'pup006', 2),
            $this->select('Berbeda untuk pangan matang dan pangan mentah', 'pup007', 3),
            $this->select('Peralatan masak/makan sekali pakai tidak dipakai ulang dan food grade', 'pup008', 3),

            $this->select('Tersedia termometer yang berfungsi dan akurat', 'pup009', 2),
            $this->select('Peralatan personal, peralatan kantor, dll yang tidak diperlukan tidak diletakkan di area pengolahan pangan', 'pup010', 2),

            $this->select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'pup011', 2),
            $this->select('Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu injuk atau kemoceng)', 'pup012', 2),

            $this->h(3, 'Penyimpanan Pangan Setengah Matang / Matang', 'penyimpanan-pangan-setengah-matang-atau-matang'),

            $this->select('Penyimpanan pangan terpisah (pangan mentah, setengah matang dan matang)', 'pps001', 3),
            $this->select('Wadah penyimpanan pangan matang atau produk akhir terpisah untuk setiap jenis pangan', 'pps002', 2),

            $this->h(4, 'Chiller/freezer (jika ada)'),

            $this->select('Khusus menyimpan pangan setengah matang/matang dengan kondisi terkemas', 'pps003', 3),
            $this->select('Suhu chiller/freezer atau termometer untuk monitoring sudah dikalibrasi', 'pps004', 2),
            $this->select('Suhu chiller sesuai (≤ 4oC)', 'pps005', 2),
            $this->select('Terdapat dokumen monitoring chiller', 'pps006', 2),
            $this->select('Suhu freezer sesuai (≤ -18oC)', 'pps007', 2),
            $this->select('Terdapat dokumen monitoring freezer', 'pps008', 2),

            $this->h(3, 'Pengemasan Pangan Matang / Produk Akhir', 'pengemasan-pangan-matang-atau-produk-akhir'),

            $this->select('Pengemasan dilakukan secara higiene (personil cuci tangan dan menggunakan sarung tangan dengan kondisi baik)', 'ppm001', 3),
            $this->select('Pengemasan pangan matang atau produk akhir harus dalam wadah tertutup dan tara pangan (food grade)', 'ppm002', 3),
            $this->select('Pangan matang atau produk akhir yang disajikan di dalam kotak/kemasan diberikan tanda batas waktu (expired date) tanggal dan waktu makanan boleh dikonsumsi serta nomor sertifikat laik higiene sanitasi', 'ppm003', 2),

            $this->h(3, 'Pengangkutan Pangan Matang / Produk Akhir', 'pengangkutan-pangan-matang-atau-produk-akhir'),

            $this->select('Selama pengangkutan, pangan harus dilindungi dari debu dan jenis kontaminasi lainnya', 'ppm004', 3),

            $this->h(4, 'Tersedia kendaraan khusus pengangkut pangan matang atau produk akhir'),

            $this->select('Bersih', 'ppm005', 2),
            $this->select('Bebas vektor dan binatang pembawa penyakit', 'ppm006', 2),
            $this->select('Terdapat pembersihan secara berkala', 'ppm007', 2),

            $this->h(3, 'D Dokumentasi dan Rekaman (diakses di ruangan adminitrasi)', 'dokumentasi-dan-rekaman'),

            $this->select('Tersedia hasil analisis pengujian air yang sesuai dengan persyaratan air minum dan memiliki hasil yang sesuai persyaratan', 'dr001', 3),
            $this->select('Tersedia dokumentasi pengawasan internal secara berkala (menggunakan buku rapor/formulir self assessment)', 'dr002', 2),

            $this->h(3, 'Rekaman Personil', 'rekaman-personil'),

            $this->select('Sehat dan bebas dari penyakit menular (contoh diare, demam tifoid/tifus, hepatitis A dan lain-lain, dibuktikan dengan surat keterangan sehat', 'rp001', 2),
            $this->select('Pengelola/pemilik/penanggung jawab TPP memiliki sertifikat pelatihan keamanan pangan siap saji', 'rp002', 3),
            $this->select('Penjamah pangan sudah memiliki sertifikat pelatihan keamanan pangan siap saji atau sertifikat kompetensi (minimal 50%)', 'rp003', 3),
            $this->select('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali setahun', 'rp004', 2),

            $this->h(3, 'E Keselamatan dan Kesehatan Kerja', 'keselamatan-dan-kesehatan-kerja'),

            $this->select('Tersedia alat pemadam api ringan (APAR) gas yang mudah dijangkau untuk situasi darurat disertai dengan petunjuk penggunaan yang jelas', 'kkk001'),
            $this->select('Tersedia personil yang bertanggung jawab dan dapat menggunakan APAR', 'kkk002'),
            $this->select('APAR tidak kedaluwarsa', 'kkk003'),
            $this->select('Tersedia perlengkapan P3K dan obat-obatan yang tidak kadaluwarsa', 'kkk004'),
            $this->select('Tersedia petunjuk jalur evakuasi yang jelas pada setiap ruangan ke arah titik kumpul', 'kkk005'),
            $this->select('Menerapkan kawasan tanpa rokok (KTR)', 'kkk006'),

        ];
    }

    protected function informasiUmumName()
    {
        return [
            'u001',
            'u002',
            'u003',
            'u004',
            'u005',
            'u006',
            'u007',
            'u008',
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'lk001',
            'lk002',
            'lk003',
            'lk004',
            'lk005',
            'lk006',
            'lk007',
            'lk008',
            'lk009',
            'lk010',
            'lk011',
            'lk012',

            'bf001',
            'bf002',
            'bf003',
            'bf004',
            'bf005',
            'bf006',
            'bf007',
            'bf008',
            'bf009',
            'bf010',
            'bf011',
            'bf012',
            'bf013',
            'bf014',
            'bf015',
            'bf016',
            'bf017',
            'bf018',
            'bf019',

            'pp001',
            'pp002',

            'fk001',
            'fk002',
            'fk003',
            'fk004',
            'fk005',

            'ab001',
            'ab002',

            'pb001',
            'pb002',
            'pb003',
            'pb004',
            'pb005',
            'pb006',
            'pb007',
            'pb008',
            'pb009',

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
            'bp015',
            'bp016',

            'ak001',
            'ak002',
            'ak003',
            'ak004',
            'ak005',

            'bpk001',
            'bpk002',
            'bpk003',

            'bap001',
            'bap002',
            'bap003',
            'bap004',
            'bap005',
            'bap006',
            'bap007',
            'bap008',
            'bap009',
            'bap010',
            'bap011',
            'bap012',
            'bap013',

            'app001',
            'app002',
            'app003',
            'app004',
            'app005',
            'app006',
            'app007',
            'app008',
            'app009',
            'app010',
            'app011',
            'app012',
            'app013',
            'app014',
            'app015',
            'app016',
            'app017',
            'app018',
            'app019',
            'app020',
            'app021',
            'app022',
            'app023',
            'app024',
            'app025',
            'app026',
            'app027',
            'app028',
            'app029',
            'app030',
            'app031',
            'app032',
            'app033',
            'app034',
            'app035',
            'app036',
            'app037',
            'app038',
            'app039',
            'app040',
            'app041',
            'app042',
            'app043',
            'app044',
            'app045',

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

            'pup001',
            'pup002',
            'pup003',
            'pup004',
            'pup005',
            'pup006',
            'pup007',
            'pup008',
            'pup009',
            'pup010',
            'pup011',
            'pup012',

            'pps001',
            'pps002',
            'pps003',
            'pps004',
            'pps005',
            'pps006',
            'pps007',
            'pps008',

            'ppm001',
            'ppm002',
            'ppm003',
            'ppm004',
            'ppm005',
            'ppm006',
            'ppm007',

            'dr001',
            'dr002',

            'rp001',
            'rp002',
            'rp003',
            'rp004',

            'kkk001',
            'kkk002',
            'kkk003',
            'kkk004',
            'kkk005',
            'kkk006',
        ];
    }

    public function create()
    {
        return view('pages.inspection.tpp.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian()
        ]);
    }

    public function store(Request $request)
    {
        $total = 0;
        $formData = [];

        foreach ($this->informasiUmumName() as $column) {
            $formData[$column] = $request->input($column);
        }
        foreach ($this->formPenilaianName() as $column) {
            $total += $request->input($column);
            $formData[$column] = $request->input($column);
        }

        $formData['skor'] = (int) (100 - ($total / 370) * 100);
        $formData['catatan-lain'] = $request->input('catatan-lain');

        $insert = TPP::create($formData);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi TPP gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('tpp-tertentu.show', ['tpp_tertentu' => $insert->id]))->with('success', 'penilaian / inspeksi TPP berhasil dibuat');
    }

    public function show(TPP $tpp)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $tpp,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Tempat Pengolahan Pangan',
            'edit_route' => route('tpp-tertentu.edit', ['tpp_tertentu' => $tpp['id']]),
            'destroy_route' => route('tpp-tertentu.destroy', ['tpp_tertentu' => $tpp['id']]),
        ]);
    }

    public function edit(TPP $tpp)
    {
        $informasiUmum = $this->informasiUmum();
        $formPenilaian = $this->formPenilaian();

        return view('pages.inspection.tpp.edit', [
            'page_name' => 'history',
            'informasi_umum' => $informasiUmum,
            'form_penilaian' => $formPenilaian,
            'form_data' => $tpp,
        ]);
    }

    public function update(Request $request, TPP $tpp)
    {
        $total = 0;
        $formData = [];

        foreach ($this->informasiUmumName() as $column) {
            $formData[$column] = $request->input($column);
        }
        foreach ($this->formPenilaianName() as $column) {
            $total += $request->input($column);
            $formData[$column] = $request->input($column);
        }

        $formData['skor'] = (int) (100 - ($total / 370) * 100);
        $formData['catatan-lain'] = $request->input('catatan-lain');

        $update = $tpp->update($formData);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian TPP gagal diubah');
        }

        return back()->with('success', 'form informasi dan penilaian berhasil diubah');
    }

    public function destroy(TPP $tpp)
    {
        $destroy = $tpp->destroy($tpp['id']);

        if (!$destroy) {
            return redirect(route('tpp-tertentu.show', ['tpp_tertentu' => $tpp['id']]))->with('error', 'form informasi dan penilaian TPP gagal dihapus');
        }

        return redirect(route('inspection'))->with('success', 'form informasi dan penilaian TPP berhasil dihapus');
    }
}
