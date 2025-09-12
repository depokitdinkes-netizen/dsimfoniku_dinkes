<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\Puskesmas;
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

class PuskesmasController extends Controller
{

    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Puskesmas', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Pimpinan/PJ', 'pengelola'),
            Form::input('number', 'Jumlah Karyawan', 'u004'),
            Form::input('number', 'Nomor ID Puskesmas', 'u005'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('select', 'Status Operasi', 'status-operasi'),
            Form::input('number', 'Kontak Yang Dapat Dihubungi', 'kontak'),
            Form::input('text', 'Titik GPS', 'koordinat')];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Air'),

            Form::checkbox('Tersedia air untuk keperluan HS untuk pasien rawat jalan (Kadar tertinggi yang diperkenankan 15-20 L/org/hr)', 'a001'),
            Form::checkbox('Tersedia air untuk keperluan HS untuk pasien rawat jalan (Kadar Tertinggi Yang diperkenankan 40-60 L/org/hr)', 'a002'),
            Form::checkbox('Tersedia air dengann kualitas air minum untuk keperluan di ruang bersalin (Kadar tertinggi yang diperkenankan 100 L/org/hr)', 'a003'),

            Form::h(3, 'Parameter fisik wajib air untuk keperluan HS', 'parameter-fisik-wajib'),

            Form::checkbox('Kekeruhan (Kadar tertinggi yang diperkenankan 25 NTU)', 'a004'),
            Form::checkbox('Warna (Kadar tertinggi yang diperkenankan 50 TCU)', 'a005'),
            Form::checkbox('Zat Padat Terlarut (Kadar tertinggi yang diperkenankan 1000 mg/L)', 'a006'),
            Form::checkbox('Suhu ( Kadar tertinggi yang diperkenankan suhu udara + 3 oC)', 'a007'),
            Form::checkbox('Rasa (Kadar tertinggi yang diperkenankan Tidak Berasa)', 'a008'),
            Form::checkbox('Bau (Kadar tertinggi yang diperkenankan Tidak Berbau)', 'a009'),

            Form::h(3, 'Parameter air wajib air untuk keperluan HS', 'parameter-air-wajib'),

            Form::checkbox('Total coliform (Kadar tertinggi yang diperkenankan 50 CFU/100 ml sample)', 'a010'),
            Form::checkbox('E.Coli (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)', 'a011'),

            Form::h(3, 'Parameter Kimia Wajib Air Untuk Keperluan HS', 'parameter-kimia-wajib'),

            Form::checkbox('PH (Kadar tertinggi yang diperkenankan 6,5-8,5 mg/l)', 'a012'),
            Form::checkbox('Besi (Kadar tertinggi yang diperkenankan 1 mg/l)', 'a013'),
            Form::checkbox('Flourida (Kadar tertinggi yang diperkenankan 1,5 mg/l)', 'a014'),
            Form::checkbox('Kesadahan (Kadar tertinggi yang diperkenankan 500 mg/l)', 'a015'),
            Form::checkbox('Mangan (Kadar tertinggi yang diperkenankan 0,5 mg/l)', 'a016'),
            Form::checkbox('Nitrat Sebagai N (Kadar tertinggi yang diperkenankan 10 mg/l)', 'a017'),
            Form::checkbox('Nitrit Sebagai N (Kadar tertinggi yang diperkenankan 1 mg/l)', 'a018'),
            Form::checkbox('Sianida (Kadar tertinggi yang diperkenankan 0,1 mg/l)', 'a019'),
            Form::checkbox('Detergen (Kadar tertinggi yang diperkenankan 0,05 mg/l)', 'a020'),
            Form::checkbox('Pestisida (Kadar tertinggi yang diperkenankan 0,1 mg/l)', 'a021'),

            Form::h(3, 'Parameter Fisik Wajib Air Minum', 'parameter-fisik-wajib-air-minum'),

            Form::checkbox('Tidak Bau', 'a022'),
            Form::checkbox('Warna (Kadar tertinggi yang diperkenankan 15 TCU)', 'a023'),
            Form::checkbox('Zat padat terlarut (TDS) (Kadar tertinggi yang diperkenankan 500 mg/l)', 'a024'),
            Form::checkbox('Kekeruhan (Kadar tertinggi yang diperkenankan 5 NTU)', 'a025'),
            Form::checkbox('Rasa', 'a026'),
            Form::checkbox('Suhu (Kadar tertinggi yang diperkenankan  suhu udara + 3 oC)', 'a027'),

            Form::h(3, 'Parameter biologi wajib air minum', 'parameter-biologi-wajib-air-minum'),

            Form::checkbox('E.Coli (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)', 'a028'),
            Form::checkbox('Total Coliform (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)', 'a029'),

            Form::h(3, 'Parameter kimia wajib air minum', 'parameter-kimia-wajib-air-minum'),

            Form::checkbox('Arsen (Kadar tertinggi yang diperkenankan 0,01 mg/l)', 'a030'),
            Form::checkbox('Flourida (Kadar tertinggi yang diperkenankan 1,5 mg/l)', 'a031'),
            Form::checkbox('Total coliform (Kadar tertinggi yang diperkenankan 0,05 mg/l)', 'a032'),
            Form::checkbox('Kadmium (Kadar tertinggi yang diperkenankan 0,003 mg/l)', 'a033'),
            Form::checkbox('Nitrit (Kadar tertinggi yang diperkenankan 3 mg/l)', 'a034'),
            Form::checkbox('Nitrat (Kadar tertinggi yang diperkenankan 60 mg/l)', 'a035'),
            Form::checkbox('Sianida (Kadar tertinggi yang diperkenankan 0,07 mg/l)', 'a036'),
            Form::checkbox('Selenium (Kadar tertinggi yang diperkenankan 0,01 mg/l)', 'a037'),
            Form::checkbox('Aluminium (Kadar tertinggi yang diperkenankan 0,2 mg/l)', 'a038'),
            Form::checkbox('Besi (Kadar tertinggi yang diperkenankan 0,3 mg/l)', 'a039'),
            Form::checkbox('Kesadahan (Kadar tertinggi yang diperkenankan 500 mg/l)', 'a040'),
            Form::checkbox('Klorida (Kadar tertinggi yang diperkenankan 250 mg/l)', 'a041'),
            Form::checkbox('Mangan (Kadar tertinggi yang diperkenankan 0,4 mg/l)', 'a042'),
            Form::checkbox('PH (Kadar tertinggi yang diperkenankan 6,5-8,5)', 'a043'),
            Form::checkbox('Seng (Kadar tertinggi yang diperkenankan 3 mg/l)', 'a044'),
            Form::checkbox('Sulfat (Kadar tertinggi yang diperkenankan 250 mg/l)', 'a045'),
            Form::checkbox('Tembaga (Kadar tertinggi yang diperkenankan 2 mg/l)', 'a046'),
            Form::checkbox('Amonia (Kadar tertinggi yang diperkenankan 1,5 mg/l)', 'a047'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-air'),

            Form::checkbox('Air untuk keperluan HS tersedia sepanjang waktu', 'a048'),
            Form::checkbox('Air minum tersedia sepanjang waktu', 'a049'),

            Form::h(2, 'Udara'),

            Form::h(3, 'Standar baku mutu kualitas fisik', 'standar-baku-mutu-kualitas-fisik'),

            Form::checkbox('Pencahayaan ruangan pemeriksaan umum (Kadar tertinggi yang diperkenankan 200)', 'ud001'),
            Form::checkbox('Pencahayaan ruangan pemeriksaan gigi dan mulut (Kadar tertinggi yang diperkenankan 200)', 'ud002'),
            Form::checkbox('Pencahayaan ruangan farmasi (Kadar tertinggi yang diperkenankan 200)', 'ud003'),
            Form::checkbox('Pencahayaan ruangan laboratorium (Kadar tertinggi yang diperkenankan 300)', 'ud004'),
            Form::checkbox('Pencahayaan ruangan pemeriksaan tindakan (Kadar tertinggi yang diperkenankan 300)', 'ud005'),
            Form::checkbox('Pencahayaan ruangan gawat darurat (Kadar tertinggi yang diperkenankan 300)', 'ud006'),
            Form::checkbox('Kelembaban (Kadar tertinggi yang diperkenankan 40-70)', 'ud007'),
            Form::checkbox('Laju ventilasi udara (Kadar tertinggi yang diperkenankan 0,15 - 0,50)', 'ud008'),
            Form::checkbox('Kebisingan di dalam bangunan puskemas (Kadar tertinggi yang diperkenankan ≤ 45)', 'ud009'),
            Form::checkbox('Kebisingan di luar bangunan puskemas (Kadar tertinggi yang diperkenankan ≤ 55)', 'ud010'),
            Form::checkbox('Particulate matter (PM) 2,5 (Kadar tertinggi yang diperkenankan ≤ 35)', 'ud011'),
            Form::checkbox('Particulate matter (PM) 10 (Kadar tertinggi yang diperkenankan ≤ 70)', 'ud012'),

            Form::h(3, 'Standar baku mutu kualitas biologi', 'standar-baku-mutu-kualitas-biologi'),

            Form::checkbox('Angka jamur total (Kadar tertinggi yang diperkenankan 1000 CFU/m³)', 'ud013'),
            Form::checkbox('Angka kuman total (Kadar tertinggi yang diperkenankan 500 CFU/m³)', 'ud014'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-udara'),

            Form::checkbox('Puskesmas bebas dari asap rokok', 'ud016'),
            Form::checkbox('Lingkungan puskesmas tidak banyak debu', 'ud017'),
            Form::checkbox('Pencahayaan ruang pemeriksaan umum, ruang tindakan, dan ruang gawat darurat dapat melakukan kegiatan dengan cahaya yang terang tanpa bantuan penerangan pada siang hari', 'ud018'),
            Form::checkbox('Udara di dalam puskesmas tidak terasa pengap / terasa segar / terasa nyaman', 'ud019'),
            Form::checkbox('Udara di dalam puskesmas tidak terasa bau', 'ud020'),

            Form::h(2, 'Pangan'),

            Form::checkbox('Standar baku mutu dan persyaratan kesehatan lingkungan menggunakan peraturan HSP yang berlaku sesuai dengan jenis TPM yang ada di wilayah PKM', 'p001'),

            Form::h(2, 'Sarana Dan Bangunan'),
            Form::h(3, 'Standar baku mutu', 'standar-baku-mutu-sarana-dan-bangunan'),

            Form::checkbox('Lebar koridor (Kadar tertinggi yang diperkenankan 2,4 meter)', 'sb001'),
            Form::checkbox('Tinggi Langit - langit (Kadar tertinggi yang diperkenankan  ≥ 2,8 meter)', 'sb002'),
            Form::checkbox('Lebar bukaan pintu utama dan ruang gawat darurat (Kadar tertinggi yang diperkenankan  ≥ 120 centi meter)', 'sb003'),
            Form::checkbox('Lebar bukaan pintu yang bukan akses brangkar (Kadar tertinggi yang diperkenankan  ≥ 90 centi meter)', 'sb004'),
            Form::checkbox('Lebar daun pintu khusus untuk KM/WC di ruang perawatan dan pintu KM/WC untuk penyandang disabilitas (Kadar tertinggi yang diperkenankan  ≥ 90 centi meter)', 'sb005'),
            Form::checkbox('Jumlah sarana WC/urinoir untuk penyandang disabilitas (Kadar tertinggi yang diperkenankan 1)', 'sb006'),
            Form::checkbox('Jumlah sarana KM dan WC Sesuai', 'sb007'),
            Form::checkbox('Jumlah tempat sampah per ruangan (Kadar tertinggi yang diperkenankan 2)', 'sb008'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-sarana-dan-bangunan'),

            Form::checkbox('Tersedia lahan parkir', 'sb009'),
            Form::checkbox('Puskesmas berpagar', 'sb010'),
            Form::checkbox('Atap kuat, tidak bocor, tahan lama dan tidak menjadi tempat perindukan vektor', 'sb011'),
            Form::checkbox('Langit-langit harus kuat, berwarna terang dan mudah dibersihkan, tanpa profil dan terlihat tanpa sambungan', 'sb012'),
            Form::checkbox('Dinding harus keras rata, tidak berpori, tidak menyebabkan silau, kedap air dan mudah dibersihkan dan tidak ada sambungan', 'sb013'),
            Form::checkbox('Dinding laboratorium harus tahan terhadap bahan kimia, mudah dibersihkan dan tidak berpori', 'sb014'),
            Form::checkbox('Tempat sampah pembuatan dari bahan yang kuat, cukup ringan, tahan karat, kedap air dan mudah dibersihkan', 'sb015'),
            Form::checkbox('Lubang ventilasi dilengkapi kawat kasa nyamuk', 'sb016'),
            Form::checkbox('Pintu ruangan terbuka keluar', 'sb017'),
            Form::checkbox('Lantai kuat, kedap air, rata, tidak licin, warna terang, mudah dibersihkan dan dengan sambungan seminimal mungkin', 'sb018'),
            Form::checkbox('Dinding WC/KM kedap air, dilapisi keramik setinggi 150 cm', 'sb019'),
            Form::checkbox('Pintu khusus KM/WC di ruang perawatan dan pintu KM/WC penyandang disabilitas terbuka keluar', 'sb020'),
            Form::checkbox('Material pintu KM.WC harus kedap air', 'sb021'),
            Form::checkbox('Lantai harus kuat, kedap air, rata, tidak licin, dan air buangan tidak boleh tergenang', 'sb022'),
            Form::checkbox('Pintu KM.WC harus mudah dibuka dan ditutup', 'sb023'),
            Form::checkbox('KM/WC untuk penyandang disabilitas, dilengkapi dengan pegangan rambat (handrail) yang memiliki posisi dan ketingian disesuaikan dengan pennguna kursi roda dan penyandang disabilitas lainnya', 'sb024'),
            Form::checkbox('Tersedia alat sanitasi di KM/WC (sikat, desinfektan, dll)', 'sb025'),
            Form::checkbox('Pada setiap lubang penyaluran air limbah di ruangan harus dilengkapi dengan saringan (KM/WC)', 'sb026'),
            Form::checkbox('Tersedia septic tank dengan resapan san secara rutin dilakukan penyedotan', 'sb027'),

            Form::h(3, 'Limbah Cair', 'limbah-cair'),

            Form::checkbox('Tersedia IPAL dengan kapasitas memadai yang dilengkapi dengan alat pengukur debit', 'sb028'),
            Form::checkbox('Pada outlet IPAL dilakukan pemeriksaan kualitas air limbah setiap 3 bulan sekali dengan hasil memenuhi baku mutu', 'sb029'),
            Form::checkbox('Tersedia tempat khusus bahan kimia (lab, farmasi, dll)', 'sb030'),
            Form::checkbox('Tersedia APD bagi petugas limbah cair (masker, sarung tangan dan sepatu)', 'sb031'),
            Form::checkbox('Saluran air limbah harus terpisah dengan saluran air hujan', 'sb032'),
            Form::checkbox('Saluran pembuangan air limbah tertutup, kedap air dengan kemiringan', 'sb033'),
            Form::checkbox('Tersedia bak kontrol/ lubang pemeriksaan pada jarak minimal 5 meter atau setiap ada perubahan aliran atau mendapatkan tambahan aliran dari pipa lain', 'sb034'),

            Form::h(3, 'Limbah Medis Padat', 'limbah-medis-padat'),

            Form::checkbox('Terpilah limbah medis dengan non medis', 'sb035'),
            Form::checkbox('Tersedia wadah limbah medis sesuai jenisnya dan memenuhi syarat', 'sb036'),
            Form::checkbox('Tersedia TPS limbah B3 berijin, dengan ventilasi dan penerangan memadai. Dilengkapi dengan alat pemadam kebakaran', 'sb037'),
            Form::checkbox('Tersedia log book limbah B3', 'sb038'),
            Form::checkbox('Tersedia timbangan limbah B3', 'sb039'),
            Form::checkbox('Tersedia APD bagi petugas (sarung tangan, masker)', 'sb040'),
            Form::checkbox('Pengolahan limbah secara mandiri atau bekerjasama (MOU) dengan pihak III berijin', 'sb041'),

            Form::h(2, 'Vektor Dan Binatang Pembawa Penyakit'),
            Form::h(3, 'Standar baku mutu', 'standar-baku-mutu-vektor-dan-binatang'),

            Form::checkbox('Angka bebas jentik (Kadar tertinggi yang diperkenankan 100%)', 'vb001'),
            Form::checkbox('Angka rata - rata populasi lalat (Kadar tertinggi yang diperkenankan 2 ekor)', 'vb002'),
            Form::checkbox('Angka rata - rata populasi kecoa (Kadar tertinggi yang diperkenankan 2 ekor)', 'vb003'),

            Form::h(3, 'Persyaratan Kesehatan', 'persyaratan-kesehatan-vektor-dan-binatang'),

            Form::checkbox('Semua ruangan di Puskesmas harus bebas dari tanda-tanda keberadaan kecoa (bau, kencing, keberadaan telur/ookinet)', 'vb004'),
            Form::checkbox('Semua ruangan di Puskesmas harus bebas dari tanda-tanda keberadaan tikus (bau, kencing, bekas gigitan, kotoran)', 'vb005'),
            Form::checkbox('Tidak ditemukan lalat di puskesmas', 'vb006'),
            Form::checkbox('Di lingkungan puskesmas harus bebas kucing dan anjing', 'vb007')];
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
            'a010',
            'a011',
            'a012',
            'a013',
            'a014',
            'a015',
            'a016',
            'a017',
            'a018',
            'a019',
            'a020',
            'a021',
            'a022',
            'a023',
            'a024',
            'a025',
            'a026',
            'a027',
            'a028',
            'a029',
            'a030',
            'a031',
            'a032',
            'a033',
            'a034',
            'a035',
            'a036',
            'a037',
            'a038',
            'a039',
            'a040',
            'a041',
            'a042',
            'a043',
            'a044',
            'a045',
            'a046',
            'a047',
            'a048',
            'a049',

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
            'ud014',
            'ud015',
            'ud016',
            'ud017',
            'ud018',
            'ud019',
            'ud020',

            'p001',

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
            'sb029',
            'sb030',
            'sb031',
            'sb032',
            'sb033',
            'sb034',
            'sb035',
            'sb036',
            'sb037',
            'sb038',
            'sb039',
            'sb040',
            'sb041',

            'vb001',
            'vb002',
            'vb003',
            'vb004',
            'vb005',
            'vb006',
            'vb007'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Puskesmas::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Puskesmas',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Puskesmas', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)]],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_PUSKESMAS_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return Puskesmas::withTrashed()->get()->map(function ($item) {
                            return collect($item->toArray())->map(function ($value) {
                                return ($value === null || $value === '' || $value === 0) ? '0' : $value;
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

                            'Jumlah Karyawan',
                            'Nomor ID Puskesmas',

                            'Tersedia air untuk keperluan HS untuk pasien rawat jalan (Kadar tertinggi yang diperkenankan 15-20 L/org/hr)',
                            'Tersedia air untuk keperluan HS untuk pasien rawat jalan (Kadar Tertinggi Yang diperkenankan 40-60 L/org/hr)',
                            'Tersedia air dengann kualitas air minum untuk keperluan di ruang bersalin (Kadar tertinggi yang diperkenankan 100 L/org/hr)',
                            'Kekeruhan (Kadar tertinggi yang diperkenankan 25 NTU)',
                            'Warna (Kadar tertinggi yang diperkenankan 50 TCU)',
                            'Zat Padat Terlarut (Kadar tertinggi yang diperkenankan 1000 mg/L)',
                            'Suhu ( Kadar tertinggi yang diperkenankan suhu udara + 3 oC)',
                            'Rasa (Kadar tertinggi yang diperkenankan Tidak Berasa)',
                            'Bau (Kadar tertinggi yang diperkenankan Tidak Berbau)',
                            'Total coliform (Kadar tertinggi yang diperkenankan 50 CFU/100 ml sample)',
                            'E.Coli (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)',
                            'PH (Kadar tertinggi yang diperkenankan 6,5-8,5 mg/l)',
                            'Besi (Kadar tertinggi yang diperkenankan 1 mg/l)',
                            'Flourida (Kadar tertinggi yang diperkenankan 1,5 mg/l)',
                            'Kesadahan (Kadar tertinggi yang diperkenankan 500 mg/l)',
                            'Mangan (Kadar tertinggi yang diperkenankan 0,5 mg/l)',
                            'Nitrat Sebagai N (Kadar tertinggi yang diperkenankan 10 mg/l)',
                            'Nitrit Sebagai N (Kadar tertinggi yang diperkenankan 1 mg/l)',
                            'Sianida (Kadar tertinggi yang diperkenankan 0,1 mg/l)',
                            'Detergen (Kadar tertinggi yang diperkenankan 0,05 mg/l)',
                            'Pestisida (Kadar tertinggi yang diperkenankan 0,1 mg/l)',
                            'Tidak Bau',
                            'Warna (Kadar tertinggi yang diperkenankan 15 TCU)',
                            'Zat padat terlarut (TDS) (Kadar tertinggi yang diperkenankan 500 mg/l)',
                            'Kekeruhan (Kadar tertinggi yang diperkenankan 5 NTU)',
                            'Rasa',
                            'Suhu (Kadar tertinggi yang diperkenankan  suhu udara + 3 oC)',
                            'E.Coli (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)',
                            'Total Coliform (Kadar tertinggi yang diperkenankan 0 CFU/100 ml sample)',
                            'Arsen (Kadar tertinggi yang diperkenankan 0,01 mg/l)',
                            'Flourida (Kadar tertinggi yang diperkenankan 1,5 mg/l)',
                            'Total coliform (Kadar tertinggi yang diperkenankan 0,05 mg/l)',
                            'Kadmium (Kadar tertinggi yang diperkenankan 0,003 mg/l)',
                            'Nitrit (Kadar tertinggi yang diperkenankan 3 mg/l)',
                            'Nitrat (Kadar tertinggi yang diperkenankan 60 mg/l)',
                            'Sianida (Kadar tertinggi yang diperkenankan 0,07 mg/l)',
                            'Selenium (Kadar tertinggi yang diperkenankan 0,01 mg/l)',
                            'Aluminium (Kadar tertinggi yang diperkenankan 0,2 mg/l)',
                            'Besi (Kadar tertinggi yang diperkenankan 0,3 mg/l)',
                            'Kesadahan (Kadar tertinggi yang diperkenankan 500 mg/l)',
                            'Klorida (Kadar tertinggi yang diperkenankan 250 mg/l)',
                            'Mangan (Kadar tertinggi yang diperkenankan 0,4 mg/l)',
                            'PH (Kadar tertinggi yang diperkenankan 6,5-8,5)',
                            'Seng (Kadar tertinggi yang diperkenankan 3 mg/l)',
                            'Sulfat (Kadar tertinggi yang diperkenankan 250 mg/l)',
                            'Tembaga (Kadar tertinggi yang diperkenankan 2 mg/l)',
                            'Amonia (Kadar tertinggi yang diperkenankan 1,5 mg/l)',
                            'Air untuk keperluan HS tersedia sepanjang waktu',
                            'Air minum tersedia sepanjang waktu',
                            'Pencahayaan ruangan pemeriksaan umum (Kadar tertinggi yang diperkenankan 200)',
                            'Pencahayaan ruangan pemeriksaan gigi dan mulut (Kadar tertinggi yang diperkenankan 200)',
                            'Pencahayaan ruangan farmasi (Kadar tertinggi yang diperkenankan 200)',
                            'Pencahayaan ruangan laboratorium (Kadar tertinggi yang diperkenankan 300)',
                            'Pencahayaan ruangan pemeriksaan tindakan (Kadar tertinggi yang diperkenankan 300)',
                            'Pencahayaan ruangan gawat darurat (Kadar tertinggi yang diperkenankan 300)',
                            'Kelembaban (Kadar tertinggi yang diperkenankan 40-70)',
                            'Laju ventilasi udara (Kadar tertinggi yang diperkenankan 0,15 - 0,50)',
                            'Kebisingan di dalam bangunan puskemas (Kadar tertinggi yang diperkenankan ≤ 45)',
                            'Kebisingan di luar bangunan puskemas (Kadar tertinggi yang diperkenankan ≤ 55)',
                            'Particulate matter (PM) 2,5 (Kadar tertinggi yang diperkenankan ≤ 35)',
                            'Particulate matter (PM) 10 (Kadar tertinggi yang diperkenankan ≤ 70)',
                            'Angka jamur total (Kadar tertinggi yang diperkenankan 1000 CFU/m³)',
                            'Angka kuman total (Kadar tertinggi yang diperkenankan 500 CFU/m³)',
                            'Puskesmas bebas dari asap rokok',
                            'Lingkungan puskesmas tidak banyak debu',
                            'Pencahayaan ruang pemeriksaan umum, ruang tindakan, dan ruang gawat darurat dapat melakukan kegiatan dengan cahaya yang terang tanpa bantuan penerangan pada siang hari',
                            'Udara di dalam puskesmas tidak terasa pengap / terasa segar / terasa nyaman',
                            'Udara di dalam puskesmas tidak terasa bau',
                            'Standar baku mutu dan persyaratan kesehatan lingkungan menggunakan peraturan HSP yang berlaku sesuai dengan jenis TPM yang ada di wilayah PKM',
                            'Lebar koridor (Kadar tertinggi yang diperkenankan 2,4 meter)',
                            'Tinggi Langit - langit (Kadar tertinggi yang diperkenankan  ≥ 2,8 meter)',
                            'Lebar bukaan pintu utama dan ruang gawat darurat (Kadar tertinggi yang diperkenankan  ≥ 120 centi meter)',
                            'Lebar bukaan pintu yang bukan akses brangkar (Kadar tertinggi yang diperkenankan  ≥ 90 centi meter)',
                            'Lebar daun pintu khusus untuk KM/WC di ruang perawatan dan pintu KM/WC untuk penyandang disabilitas (Kadar tertinggi yang diperkenankan  ≥ 90 centi meter)',
                            'Jumlah sarana WC/urinoir untuk penyandang disabilitas (Kadar tertinggi yang diperkenankan 1)',
                            'Jumlah sarana KM dan WC Sesuai',
                            'Jumlah tempat sampah per ruangan (Kadar tertinggi yang diperkenankan 2)',
                            'Tersedia lahan parkir',
                            'Puskesmas berpagar',
                            'Atap kuat, tidak bocor, tahan lama dan tidak menjadi tempat perindukan vektor',
                            'Langit-langit harus kuat, berwarna terang dan mudah dibersihkan, tanpa profil dan terlihat tanpa sambungan',
                            'Dinding harus keras rata, tidak berpori, tidak menyebabkan silau, kedap air dan mudah dibersihkan dan tidak ada sambungan',
                            'Dinding laboratorium harus tahan terhadap bahan kimia, mudah dibersihkan dan tidak berpori',
                            'Tempat sampah pembuatan dari bahan yang kuat, cukup ringan, tahan karat, kedap air dan mudah dibersihkan',
                            'Lubang ventilasi dilengkapi kawat kasa nyamuk',
                            'Pintu ruangan terbuka keluar',
                            'Lantai kuat, kedap air, rata, tidak licin, warna terang, mudah dibersihkan dan dengan sambungan seminimal mungkin',
                            'Dinding WC/KM kedap air, dilapisi keramik setinggi 150 cm',
                            'Pintu khusus KM/WC di ruang perawatan dan pintu KM/WC penyandang disabilitas terbuka keluar',
                            'Material pintu KM.WC harus kedap air',
                            'Lantai harus kuat, kedap air, rata, tidak licin, dan air buangan tidak boleh tergenang',
                            'Pintu KM.WC harus mudah dibuka dan ditutup',
                            'KM/WC untuk penyandang disabilitas, dilengkapi dengan pegangan rambat (handrail) yang memiliki posisi dan ketingian disesuaikan dengan pennguna kursi roda dan penyandang disabilitas lainnya',
                            'Tersedia alat sanitasi di KM/WC (sikat, desinfektan, dll)',
                            'Pada setiap lubang penyaluran air limbah di ruangan harus dilengkapi dengan saringan (KM/WC)',
                            'Tersedia septic tank dengan resapan san secara rutin dilakukan penyedotan',
                            'Tersedia IPAL dengan kapasitas memadai yang dilengkapi dengan alat pengukur debit',
                            'Pada outlet IPAL dilakukan pemeriksaan kualitas air limbah setiap 3 bulan sekali dengan hasil memenuhi baku mutu',
                            'Tersedia tempat khusus bahan kimia (lab, farmasi, dll)',
                            'Tersedia APD bagi petugas limbah cair (masker, sarung tangan dan sepatu)',
                            'Saluran air limbah harus terpisah dengan saluran air hujan',
                            'Saluran pembuangan air limbah tertutup, kedap air dengan kemiringan',
                            'Tersedia bak kontrol/ lubang pemeriksaan pada jarak minimal 5 meter atau setiap ada perubahan aliran atau mendapatkan tambahan aliran dari pipa lain',
                            'Terpilah limbah medis dengan non medis',
                            'Tersedia wadah limbah medis sesuai jenisnya dan memenuhi syarat',
                            'Tersedia TPS limbah B3 berijin, dengan ventilasi dan penerangan memadai. Dilengkapi dengan alat pemadam kebakaran',
                            'Tersedia log book limbah B3',
                            'Tersedia timbangan limbah B3',
                            'Tersedia APD bagi petugas (sarung tangan, masker)',
                            'Pengolahan limbah secara mandiri atau bekerjasama (MOU) dengan pihak III berijin',
                            'Angka bebas jentik (Kadar tertinggi yang diperkenankan 100%)',
                            'Angka rata - rata populasi lalat (Kadar tertinggi yang diperkenankan 2 ekor)',
                            'Angka rata - rata populasi kecoa (Kadar tertinggi yang diperkenankan 2 ekor)',
                            'Semua ruangan di Puskesmas harus bebas dari tanda-tanda keberadaan kecoa (bau, kencing, keberadaan telur/ookinet)',
                            'Semua ruangan di Puskesmas harus bebas dari tanda-tanda keberadaan tikus (bau, kencing, bekas gigitan, kotoran)',
                            'Tidak ditemukan lalat di puskesmas',
                            'Di lingkungan puskesmas harus bebas kucing dan anjing'];
                        }
                    }, 'REPORT_PUSKESMAS_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspection.puskesmas.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian()]);
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
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'u004' => 'required|numeric|min:0',
                'u005' => 'required|numeric|min:0',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'status-operasi' => 'required|boolean',
                'kontak' => 'required|string|max:255',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
            ], [
                'subjek.required' => 'Nama Puskesmas wajib diisi',
                'alamat.required' => 'Alamat wajib diisi',
                'kecamatan.required' => 'Kecamatan wajib diisi',
                'kelurahan.required' => 'Kelurahan wajib diisi',
                'pengelola.required' => 'Pimpinan/PJ wajib diisi',
                'u004.required' => 'Jumlah Karyawan wajib diisi',
                'u005.required' => 'Nomor ID Puskesmas wajib diisi',
                'nama-pemeriksa.required' => 'Nama Pemeriksa wajib diisi',
                'instansi-pemeriksa.required' => 'Instansi Pemeriksa wajib diisi',
                'tanggal-penilaian.required' => 'Tanggal Penilaian wajib diisi',
                'status-operasi.required' => 'Status Operasi wajib diisi',
                'kontak.required' => 'Kontak Yang Dapat Dihubungi wajib diisi',
                'koordinat.required' => 'Titik GPS wajib diisi',
                'koordinat.regex' => 'Format koordinat tidak valid. Gunakan format: latitude,longitude',
            ]);

            $data = $request->all();

            // Handle instansi-lainnya logic
            if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
                $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
                unset($data['instansi-lainnya']);
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 117 * 100);

            $insert = Puskesmas::create($data);

            if (!$insert) {
                Log::error('Failed to create Puskesmas record', [
                    'data' => $data,
                    'user_id' => Auth::id()
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian / inspeksi Puskesmas gagal dibuat, silahkan coba lagi');
            }

            Log::info('Puskesmas record created successfully', [
                'id' => $insert->id,
                'user_id' => Auth::id()
            ]);

            return redirect(route('puskesmas.show', ['puskesmas' => $insert->id]))->with('success', 'Penilaian / inspeksi Puskesmas berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed in Puskesmas store method', [
                'errors' => $e->errors(),
                'input' => $request->all(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Error in Puskesmas store method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Puskesmas $puskesmas)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $puskesmas,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Puskesmas',
            'edit_route' => route('puskesmas.edit', ['puskesmas' => $puskesmas['id']]),
            'destroy_route' => route('puskesmas.destroy', ['puskesmas' => $puskesmas['id']]),
            'export_route' => route(
                'puskesmas.index',
                [
                    'export' => 'pdf',
                    'id' => $puskesmas['id']],
            )]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puskesmas $puskesmas)
    {
        return view('pages.inspection.puskesmas.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $puskesmas]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puskesmas $puskesmas)
    {
        try {
            // Validasi input dengan custom error messages
            $request->validate([
                'subjek' => 'required|string|max:255',
                'alamat' => 'required|string',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'u004' => 'required|numeric|min:0',
                'u005' => 'required|numeric|min:0',
                'nama-pemeriksa' => 'required|string|max:255',
                'instansi-pemeriksa' => 'required|string|max:255',
                'tanggal-penilaian' => 'required|date',
                'status-operasi' => 'required|boolean',
                'kontak' => 'required|string|max:255',
                'koordinat' => 'required|string|regex:/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/',
            ], [
                'subjek.required' => 'Nama Puskesmas wajib diisi',
                'alamat.required' => 'Alamat wajib diisi',
                'kecamatan.required' => 'Kecamatan wajib diisi',
                'kelurahan.required' => 'Kelurahan wajib diisi',
                'pengelola.required' => 'Pimpinan/PJ wajib diisi',
                'u004.required' => 'Jumlah Karyawan wajib diisi',
                'u005.required' => 'Nomor ID Puskesmas wajib diisi',
                'nama-pemeriksa.required' => 'Nama Pemeriksa wajib diisi',
                'instansi-pemeriksa.required' => 'Instansi Pemeriksa wajib diisi',
                'tanggal-penilaian.required' => 'Tanggal Penilaian wajib diisi',
                'status-operasi.required' => 'Status Operasi wajib diisi',
                'kontak.required' => 'Kontak Yang Dapat Dihubungi wajib diisi',
                'koordinat.required' => 'Titik GPS wajib diisi',
                'koordinat.regex' => 'Format koordinat tidak valid. Gunakan format: latitude,longitude',
            ]);

            $data = $request->all();

            // Handle instansi-lainnya logic
            if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
                $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
                unset($data['instansi-lainnya']);
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 117 * 100);

            if ($data['action'] == 'duplicate') {
                // Add auth user ID for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if (empty($data['kelurahan']) && !empty($puskesmas->kelurahan)) {
                    $data['kelurahan'] = $puskesmas->kelurahan;
                }
                if (empty($data['kecamatan']) && !empty($puskesmas->kecamatan)) {
                    $data['kecamatan'] = $puskesmas->kecamatan;
                }
                if (empty($data['subjek']) && !empty($puskesmas->subjek)) {
                    $data['subjek'] = $puskesmas->subjek;
                }
                if (empty($data['alamat']) && !empty($puskesmas->alamat)) {
                    $data['alamat'] = $puskesmas->alamat;
                }
                if (empty($data['pengelola']) && !empty($puskesmas->pengelola)) {
                    $data['pengelola'] = $puskesmas->pengelola;
                }
                if (empty($data['kontak']) && !empty($puskesmas->kontak)) {
                    $data['kontak'] = $puskesmas->kontak;
                }

                $insert = Puskesmas::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Puskesmas record', [
                        'original_id' => $puskesmas->id,
                        'data' => $data,
                        'user_id' => Auth::id()
                    ]);
                    return redirect(route('inspection'))->with('error', 'Penilaian / inspeksi Puskesmas gagal dibuat, silahkan coba lagi');
                }

                Log::info('Puskesmas record duplicated successfully', [
                    'original_id' => $puskesmas->id,
                    'new_id' => $insert->id,
                    'user_id' => Auth::id()
                ]);

                return redirect(route('puskesmas.show', ['puskesmas' => $insert->id]))->with('success', 'Penilaian / inspeksi Puskesmas berhasil dibuat');
            }

            $update = $puskesmas->update($data);

            if (!$update) {
                Log::error('Failed to update Puskesmas record', [
                    'id' => $puskesmas->id,
                    'data' => $data,
                    'user_id' => Auth::id()
                ]);
                return redirect(route('inspection'))->with('error', 'Form informasi dan penilaian Puskesmas gagal diubah');
            }

            Log::info('Puskesmas record updated successfully', [
                'id' => $puskesmas->id,
                'user_id' => Auth::id()
            ]);

            // Clear application cache to ensure fresh data is loaded
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            return redirect(route('puskesmas.show', ['puskesmas' => $puskesmas['id']]))->with('success', 'Form informasi dan penilaian Puskesmas berhasil diubah');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed in Puskesmas update method', [
                'errors' => $e->errors(),
                'input' => $request->all(),
                'puskesmas_id' => $puskesmas->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Error in Puskesmas update method', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
                'puskesmas_id' => $puskesmas->id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $puskesmas = Puskesmas::where('id', $id)->withTrashed()->first();

        if ($puskesmas['deleted_at']) {
            $puskesmas->update([
                'deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $puskesmas->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Puskesmas berhasil dihapus');
    }
}