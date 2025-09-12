<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\GeraiKantin;
use App\Models\FormIKL\Kantin;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GeraiKantinController extends Controller
{
    protected $kantinController;

    public function __construct(KantinController $kantinController)
    {
        $this->kantinController = $kantinController;
    }

    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Gerai', 'subjek'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Penyiapan Pangan untuk masing-masing TPP'),

            Form::h(3, 'Umum', 'umum'),

            Form::select('Tersedia tempat pencucian peralatan dan bahan pangan', 'pp001', 2),
            Form::select('Pencucian menggunakan air mengalir', 'pp002', 2),
            Form::select('Pencucian tidak dilakukan di area sumber kontaminasi (kamar mandi, jamban, kamar mandi umum)', 'pp003', 3),
            Form::select('Tersedia tempat cuci tangan dengan air mengalir', 'pp004', 3),
            Form::select('Tersedia tempat cuci tangan dengan sabun cuci tangan', 'pp005', 3),
            Form::select('Tersedia tempat sampah yang tertutup', 'pp006', 2),
            Form::select('Tersedia tempat penyimpanan pangan yang bersih terlindung dari bahan kimia, serta vektor dan binatang pembawa penyakit', 'pp007', 2),
            Form::select('Tersedia tempat penyimpanan peralatan yang bersih terhindar dari vektor dan binatang pembawa penyakit', 'pp008', 2),
            Form::select('Tempat penyimpanan bukan merupakan jalur akses ke kamar mandi atau jamban', 'pp009', 2),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'pp010', 3),
            Form::select('Tidak ada bahan kimia non pangan yang disimpan bersebelahan dengan bahan pangan atau pangan matang', 'pp011', 3),
            Form::select('Lantai rata', 'pp012'),
            Form::select('Lantai mudah dibersihkan', 'pp013'),
            Form::select('Memiliki ventilasi udara ', 'pp014'),
            Form::select('Dengan bahan kuat dan tahan lama', 'pp015'),
            Form::select('Ventilasi udara jika terbuka memiliki kasa anti serangga yang mudah dilepas dan dibersihkan', 'pp016'),
            Form::select('Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih', 'pp017'),

            Form::h(2, 'Pemilihan dan Penyimpanan Bahan Pangan'),

            Form::select('Bahan pangan mutu baik', 'ppbp001'),
            Form::select('Bahan pangan utuh dan tidak rusak', 'ppbp002'),
            Form::select('Bahan baku pangan dalam kemasan memiliki label', 'ppbp003', 2),
            Form::select('Bahan baku pangan dalam kemasan terdaftar atau ada izin edar', 'ppbp004', 2),
            Form::select('Bahan baku pangan dalam kemasan tidak kadaluwarsa', 'ppbp005', 2),
            Form::select('Bahan baku pangan dalam kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)', 'ppbp006', 2),

            Form::select('Jika terdapat kulkas untuk menyimpan pangan, maka bersih', 'ppbp007', 2),
            Form::select('Jika terdapat kulkas untuk menyimpan pangan, maka tersusun rapi sesuai jenis pangan (matang di atas dan mentah di bagian bawah)', 'ppbp008', 2),
            Form::select('Jika terdapat kulkas untuk menyimpan pangan, maka tidak terlalu padat', 'ppbp009', 2),

            Form::select('Bahan pangan disimpan terpisah dan dikelompokkan menurut jenisnya dalam wadah yang bersih, dan tara pangan (food grade)', 'ppbp010', 2),
            Form::select('Bahan pangan disimpan pada suhu yang tepat sesuai jenisnya', 'ppbp011', 2),
            Form::select('Penyimpanan bahan pangan menerapkan prinsip First In First Out (FIFO) dan First Expired First Out (FEFO)', 'ppbp012', 2),
            Form::select('Bahan pangan tertutup untuk mencegah akses bebas vektor dan binatang pembawa penyakit', 'ppbp013', 2),

            Form::h(3, 'C Persiapan dan Pengolahan/Pemasakan Pangan', 'persiapan-dan-pengolahan-pangan'),

            Form::select('Pencahayaan cukup terang', 'pppp001', 2),
            Form::select('Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak', 'pppp002', 2),
            Form::select('Melakukan thawing/pelunakan pangan dengan benar', 'pppp003', 2),
            Form::select('Pangan dimasak dengan suhu yang sesuai dan matang sempurna', 'pppp004', 3),
            Form::select('Personel yang bekerja pada area ini sehat dan bebas dari penyakit menular', 'pppp005', 3),
            Form::select('Personel menggunakan APD: Celemek', 'pppp006', 2),
            Form::select('Personel menggunakan APD: Masker', 'pppp007', 3),
            Form::select('Personel menggunakan APD: Hairnet/penutup rambut', 'pppp008', 3),
            Form::select('Personel berkuku pendek, bersih dan tidak memakai pewarna kuku', 'pppp009', 3),
            Form::select('Personel selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan', 'pppp010', 3),
            Form::select('Personel tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros, dan lain-lain) ketika mengolah pangan', 'pppp011', 3),
            Form::select('Pada saat mengolah pangan: Tidak merokok', 'pppp012', 3),
            Form::select('Pada saat mengolah pangan: Tidak bersin atau batuk di atas pangan langsung', 'pppp013', 3),
            Form::select('Pada saat mengolah pangan: Tidak meludah sembarangan', 'pppp014', 3),
            Form::select('Pada saat mengolah pangan: Tidak mengunyah makanan/permen', 'pppp015', 3),
            Form::select('Pada saat mengolah pangan: Tidak menangani pangan langsung setelah menggaruk-garuk anggota badan tanpa mencuci tangan atau menggunakan hand sanitizer terlebih dahulu', 'pppp016', 3),
            Form::select('Personel mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'pppp017', 3),
            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'pppp018', 3),
            Form::select('Personel melakukan pemeriksaan kesehatan minimal 1 kali dalam setahun', 'pppp019', 2),
            Form::select('Penjamah pangan sudah mendapatkan penyuluhan keamanan pangan siap saji', 'pppp020', 3),

            Form::h(3, 'Peralatan (termasuk meja tempat pengolahan)', 'peralatan'),

            Form::select('Peralatan untuk pengolahan pangan berbahan kuat', 'pppl001', 2),
            Form::select('Peralatan untuk pengolahan pangan tidak berkarat', 'pppl002', 3),
            Form::select('Peralatan untuk pengolahan pangan tara pangan (food grade)', 'pppl003', 3),
            Form::select('Peralatan untuk pengolahan pangan bersih sebelum digunakan', 'pppl004', 3),
            Form::select('Peralatan untuk pengolahan pangan Setelah digunakan kondisi bersih dan kering', 'pppl005', 3),
            Form::select('Peralatan untuk pengolahan pangan Berbeda untuk pangan matang dan pangan mentah', 'pppl006', 3),
            Form::select('Peralatan untuk pengolahan pangan sekali pakai tidak dipakai ulang dan food grade', 'pppl007', 3),
            Form::select('Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang', 'pppl008', 3),
            Form::select('Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)', 'pppl009', 3),

            Form::h(3, 'Penyajian Pangan Matang', 'penyajian-pangan-matang'),

            Form::select('Pangan matang yang mudah rusak harus sudah dikonsumsi 4 jam setelah matang', 'ppm001', 3),
            Form::select('Pangan matang panas dijaga pada suhu >60°C', 'ppm002', 3),
            Form::select('Pangan matang dingin dijaga pada suhu < 5°C', 'ppm003', 3),
            Form::select('Jika terdapat menu pangan segar yang langsung dikonsumsi seperti buah potong dan salad, maka pangan tersebut disimpan dalam suhu yang aman yaitu di bawah 5 oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)', 'ppm004', 3),
            Form::select('Es batu sebagai pangan dibuat dari air matang/sudah dimasak atau dari sumber terpercaya', 'ppm005', 3),
            Form::select('Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi', 'ppm006', 3),
            Form::select('Air untuk minum sesuai dengan standar kualitas air minum/air sudah diolah/dimasak', 'ppm007', 3),
            Form::select('Pangan yang tidak dikemas harus disajikan dengan penutup (tudung saji) atau di dalam lemari display yang tertutup', 'ppm008', 3),
            Form::select('Tempat memajang pangan matang tidak terjadi kontak dengan vektor dan binatang pembawa penyakit.', 'ppm009', 3),

            Form::h(3, 'Pengemasan Pangan Matang', 'pengemasan-pangan-matang'),

            Form::select('Pengemasan dilakukan secara higiene (personil cuci tangan dan menggunakan sarung tangan dengan kondisi baik)', 'pmpm001', 3),
            Form::select('Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)', 'pmpm002', 3),
        ];
    }

    protected function formPenilaianName()
    {
        return [
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
            'pp011',
            'pp012',
            'pp013',
            'pp014',
            'pp015',
            'pp016',
            'pp017',

            'ppbp001',
            'ppbp002',
            'ppbp003',
            'ppbp004',
            'ppbp005',
            'ppbp006',
            'ppbp007',
            'ppbp008',
            'ppbp009',
            'ppbp010',
            'ppbp011',
            'ppbp012',
            'ppbp013',

            'pppp001',
            'pppp002',
            'pppp003',
            'pppp004',
            'pppp005',
            'pppp006',
            'pppp007',
            'pppp008',
            'pppp009',
            'pppp010',
            'pppp011',
            'pppp012',
            'pppp013',
            'pppp014',
            'pppp015',
            'pppp016',
            'pppp017',
            'pppp018',
            'pppp019',
            'pppp020',

            'pppl001',
            'pppl002',
            'pppl003',
            'pppl004',
            'pppl005',
            'pppl006',
            'pppl007',
            'pppl008',
            'pppl009',

            'ppm001',
            'ppm002',
            'ppm003',
            'ppm004',
            'ppm005',
            'ppm006',
            'ppm007',
            'ppm008',
            'ppm009',

            'pmpm001',
            'pmpm002',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = GeraiKantin::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                $kantin = Kantin::find($item['id-kantin']);

                return Pdf::loadView('pdf', [
                    'form' => 'Gerai Kantin',
                    'tanggal' => Carbon::parse($kantin['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($kantin['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($kantin['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Gerai Kantin', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $kantin['alamat']],
                        ['Kelurahan', $kantin['kelurahan']],
                        ['Kecamatan', $kantin['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $kantin['nama-pemeriksa'],
                ])->download('BAIKL_GERAI_KANTIN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            default:
                abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Kantin $kantin)
    {
        return view('pages.inspection.gerai-kantin.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'informasi_umum_kantin' => array_slice($this->kantinController->informasiUmum(), 0, 6),
            'kantin' => $kantin,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 170) * 100);

        $insert = GeraiKantin::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Gerai Kantin gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('kantin.show', ['kantin' => $data['id-kantin']]))->with('success', 'penilaian / inspeksi Gerai Kantin berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(GeraiKantin $geraiKantin)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeraiKantin $geraiKantin)
    {
        $kantin = Kantin::where('id', $geraiKantin['id-kantin'])->first();

        return view('pages.inspection.gerai-kantin.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $geraiKantin,
            'informasi_umum_kantin' => array_slice($this->kantinController->informasiUmum(), 0, 6),
            'kantin' => $kantin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeraiKantin $geraiKantin)
    {
        
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        if ($request->hasFile('dokumen_slhs')) {
            $file = $request->file('dokumen_slhs');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 170) * 100);

        if ($data['action'] == 'duplicate') {
            $insert = GeraiKantin::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Gerai Kantin gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('kantin.show', ['kantin' => $data['id-kantin']]))->with('success', 'penilaian / inspeksi Gerai Kantin berhasil dibuat');
        }

        // Remove user_id and action from update data to preserve original user
        unset($data['user_id']);
        unset($data['action']);
        
        $update = $geraiKantin->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Gerai Kantin gagal diubah, silahkan coba lagi');
        }

        return redirect(route('kantin.show', ['kantin' => $data['id-kantin']]))->with('success', 'form informasi dan penilaian Gerai Kantin berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $geraiKantin = GeraiKantin::where('id', $id)->withTrashed()->first();

        if ($geraiKantin['deleted_at']) {
            $geraiKantin->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $geraiKantin->delete();

        return redirect(route('kantin.show', ['kantin' => $geraiKantin['id-kantin']]))->with('success', 'form informasi dan penilaian Gerai Kantin berhasil dihapus');
    }
}