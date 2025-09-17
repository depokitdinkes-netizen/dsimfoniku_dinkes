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
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class KantinController extends Controller
{
    public function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Sentra Pangan/Kantin', 'subjek'),
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
            Form::input('number', 'Jumlah Gerai', 'u009'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Luar TPP'),

            Form::select('Lokasi bebas banjir', 'tp001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'tp002'),
            Form::select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'tp003'),

            Form::h(2, 'Inspeksi Area Pelayanan Konsumen / Pengelolaan Gedung/Sentra/Kantin'),

            Form::select('Area tempat makan bersih', 'pk001'),
            Form::select('Jika terdapat dinding, maka dinding ruang makan bersih', 'pk002'),
            Form::select('Ventilasi udara baik', 'pk003'),
            Form::select('Memiliki tempat sampah', 'pk004'),
            Form::select('Yang Tertutup rapat', 'pk005'),
            Form::select('Tidak ada tumpukan sampah. Frekuensi pembuangan teratur', 'pk006', 2),
            Form::select('Dengan Jumlah Cukup', 'pk007'),

            Form::select('Tempat/area makan atau meja makan konsumen bersih dan mudah dibersihkan', 'pk008', 3),
            Form::select('Tempat/area makan atau meja makan konsumen utuh/rata', 'pk009'),
            Form::select('Tempat/area makan atau meja makan konsumen kedap air', 'pk010'),

            Form::select('Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini', 'pk011', 3),

            Form::select('Wastafel pengunjung', 'pk012', 3),

            Form::select('Dengan jumlah cukup', 'pk013'),
            Form::select('Dengan tersedia air mengalir', 'pk014', 3),
            Form::select('Dengan tersedia sabun cuci tangan', 'pk015', 3),
            Form::select('Dengan tersedia pengering tangan (lap tangan yang bersih/tisu/mesin pengering tangan)', 'pk016', 2),

            Form::select('Toilet pengunjung dan pekerja jumlah cukup', 'pk017'),
            Form::select('Toilet pengunjung dan pekerja terpisah bagi laki-laki dan perempuan', 'pk018'),
            Form::select('Toilet pengunjung dan pekerja dilengkapi sabun cuci tangan atau wastafel untuk cuci tangan setelah dari toilet', 'pk019', 3),

            Form::select('Tersedia media pesan-pesan kesehatan', 'pk020'),
            Form::select('Tersedia kasir', 'pk021', 2),
            Form::select('Tersedia tanda peringatan/bahaya (ALARM bahaya)', 'pk022'),
            Form::select('Terpasang rambu-rambu jalur evakuasi', 'pk023'),
            Form::select('Tersedia APAR yang Tidak kedaluawarsa', 'pk024'),
            Form::select('Tersedia APAR yang Mudah terlihat dan terjangkau', 'pk025'),
            Form::select('Tersedia APAR yang Terdapat petunjuk pemakaian', 'pk026'),
            Form::select('Tersedia alat P3K dan obat yang tidak kadaluwarsa, jelas dan ada keterangan instruksi yang mudah dimengerti', 'pk027'),
            Form::select('Penerapan kawasan tanpa rokok (KTR) diberlakukan di dalam sentra pangan jajanan/kantin dan sekitarnya', 'pk028'),
            Form::select('Tersedia sumber air yang aman untuk pencucian pangan dan peralatan', 'pk029', 3),
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'tp001',
            'tp002',
            'tp003',

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
            'pk020',
            'pk021',
            'pk022',
            'pk023',
            'pk024',
            'pk025',
            'pk026',
            'pk027',
            'pk028',
            'pk029',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = Kantin::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Kantin',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Kantin', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::DELAPAN_PULUH)],

                        ['Nomor Izin Usaha', $item['u006'] ?? '-'],
                        ['Penjamah Pangan (Bersertifikat/Total)', $item['u005'] . '/' . $item['u004']],
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa'],
                    'gerai' => GeraiKantin::where('id-kantin', $item['id'])->get(),
                ])->download('BAIKL_KANTIN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements WithMultipleSheets
                {
                    public function sheets(): array
                    {
                        return [
                            'Kantin' => new class implements FromCollection, WithHeadings, WithTitle {
                                public function collection()
                                {
                                    return Kantin::withTrashed()->get()->map(function ($item) {
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
                                        'Penjamah Pangan Bersertifikat',
                                        'Nomor Izin Usaha',
                                        'Jumlah Gerai',

                                        'Lokasi bebas banjir',
                                        'Lokasi bebas dari pencemaran bau/asap/debu/kotoran',
                                        'Lokasi bebas dari sumber vektor dan binatang pembawa penyakit',
                                        'Area tempat makan bersih',
                                        'Jika terdapat dinding, maka dinding ruang makan bersih',
                                        'Ventilasi udara baik',
                                        'Memiliki tempat sampah',
                                        'Yang Tertutup rapat',
                                        'Tidak ada tumpukan sampah. Frekuensi pembuangan teratur',
                                        'Dengan Jumlah Cukup',
                                        'Tempat/area makan atau meja makan konsumen bersih dan mudah dibersihkan',
                                        'Tempat/area makan atau meja makan konsumen utuh/rata',
                                        'Tempat/area makan atau meja makan konsumen kedap air',
                                        'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                                        'Wastafel pengunjung',
                                        'Dengan jumlah cukup',
                                        'Dengan tersedia air mengalir',
                                        'Dengan tersedia sabun cuci tangan',
                                        'Dengan tersedia pengering tangan (lap tangan yang bersih/tisu/mesin pengering tangan)',
                                        'Toilet pengunjung dan pekerja jumlah cukup',
                                        'Toilet pengunjung dan pekerja terpisah bagi laki-laki dan perempuan',
                                        'Toilet pengunjung dan pekerja dilengkapi sabun cuci tangan atau wastafel untuk cuci tangan setelah dari toilet',
                                        'Tersedia media pesan-pesan kesehatan',
                                        'Tersedia kasir',
                                        'Tersedia tanda peringatan/bahaya (ALARM bahaya)',
                                        'Terpasang rambu-rambu jalur evakuasi',
                                        'Tersedia APAR yang Tidak kedaluawarsa',
                                        'Tersedia APAR yang Mudah terlihat dan terjangkau',
                                        'Tersedia APAR yang Terdapat petunjuk pemakaian',
                                        'Tersedia alat P3K dan obat yang tidak kadaluwarsa, jelas dan ada keterangan instruksi yang mudah dimengerti',
                                        'Penerapan kawasan tanpa rokok (KTR) diberlakukan di dalam sentra pangan jajanan/kantin dan sekitarnya',
                                        'Tersedia sumber air yang aman untuk pencucian pangan dan peralatan',
                                        'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                                        'Tanggal Terbit Dokumen SLHS',
                                        'Tanggal Berakhir Dokumen SLHS',
                                    ];
                                }

                                public function title(): string
                                {
                                    return 'Kantin';
                                }
                            },
                            'Gerai' => new class implements FromCollection, WithHeadings, WithTitle {
                                public function collection()
                                {
                                    return GeraiKantin::withTrashed()->get()->map(function ($item) {
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
                                        'Id Kantin',
                                        'Id',
                                        'Nama Subjek',
                                        'Nama Pengelola',
                                        'Kontak',
                                        'Status Operasi',
                                        'Skor',
                                        'Hasil IKL',
                                        'Rencana Tindak Lanjut',
                                        'Dibuat',
                                        'Diperbarui',
                                        'Dihapus',

                                        'Tersedia tempat pencucian peralatan dan bahan pangan',
                                        'Pencucian menggunakan air mengalir',
                                        'Pencucian tidak dilakukan di area sumber kontaminasi (kamar mandi, jamban, kamar mandi umum)',
                                        'Tersedia tempat cuci tangan dengan air mengalir',
                                        'Tersedia tempat cuci tangan dengan sabun cuci tangan',
                                        'Tersedia tempat sampah yang tertutup',
                                        'Tersedia tempat penyimpanan pangan yang bersih terlindung dari bahan kimia, serta vektor dan binatang pembawa penyakit',
                                        'Tersedia tempat penyimpanan peralatan yang bersih terhindar dari vektor dan binatang pembawa penyakit',
                                        'Tempat penyimpanan bukan merupakan jalur akses ke kamar mandi atau jamban',
                                        'Tidak ada vektor dan binatang pembawa penyakit atau hewan peliharaan berkeliaran di area ini',
                                        'Tidak ada bahan kimia non pangan yang disimpan bersebelahan dengan bahan pangan atau pangan matang',
                                        'Lantai rata',
                                        'Lantai mudah dibersihkan',
                                        'Memiliki ventilasi udara ',
                                        'Dengan bahan kuat dan tahan lama',
                                        'Ventilasi udara jika terbuka memiliki kasa anti serangga yang mudah dilepas dan dibersihkan',
                                        'Jika menggunakan exhaust atau air conditioner maka kondisi terawat, berfungsi dan bersih',
                                        'Bahan pangan mutu baik',
                                        'Bahan pangan utuh dan tidak rusak',
                                        'Bahan baku pangan dalam kemasan memiliki label',
                                        'Bahan baku pangan dalam kemasan terdaftar atau ada izin edar',
                                        'Bahan baku pangan dalam kemasan tidak kadaluwarsa',
                                        'Bahan baku pangan dalam kemasan tidak rusak (menggelembung, bocor, penyok atau berkarat)',
                                        'Jika terdapat kulkas untuk menyimpan pangan, maka bersih',
                                        'Jika terdapat kulkas untuk menyimpan pangan, maka tersusun rapi sesuai jenis pangan (matang di atas dan mentah di bagian bawah)',
                                        'Jika terdapat kulkas untuk menyimpan pangan, maka tidak terlalu padat',
                                        'Bahan pangan disimpan terpisah dan dikelompokkan menurut jenisnya dalam wadah yang bersih, dan tara pangan (food grade)',
                                        'Bahan pangan disimpan pada suhu yang tepat sesuai jenisnya',
                                        'Penyimpanan bahan pangan menerapkan prinsip First In First Out (FIFO) dan First Expired First Out (FEFO)',
                                        'Bahan pangan tertutup untuk mencegah akses bebas vektor dan binatang pembawa penyakit',
                                        'Pencahayaan cukup terang',
                                        'Bahan pangan yang akan digunakan dibersihkan dan dicuci dengan air mengalir sebelum dimasak',
                                        'Melakukan thawing/pelunakan pangan dengan benar',
                                        'Pangan dimasak dengan suhu yang sesuai dan matang sempurna',
                                        'Personel yang bekerja pada area ini sehat dan bebas dari penyakit menular',
                                        'Personel menggunakan APD: Celemek',
                                        'Personel menggunakan APD: Masker',
                                        'Personel menggunakan APD: Hairnet/penutup rambut',
                                        'Personel berkuku pendek, bersih dan tidak memakai pewarna kuku',
                                        'Personel selalu mencuci tangan dengan sabun dan air mengalir sebelum dan secara berkala saat mengolah pangan',
                                        'Personel tidak menggunakan perhiasan dan aksesoris lain (cincin, gelang, bros, dan lain-lain) ketika mengolah pangan',
                                        'Pada saat mengolah pangan: Tidak merokok',
                                        'Pada saat mengolah pangan: Tidak bersin atau batuk di atas pangan langsung',
                                        'Pada saat mengolah pangan: Tidak meludah sembarangan',
                                        'Pada saat mengolah pangan: Tidak mengunyah makanan/permen',
                                        'Pada saat mengolah pangan: Tidak menangani pangan langsung setelah menggaruk-garuk anggota badan tanpa mencuci tangan atau menggunakan hand sanitizer terlebih dahulu',
                                        'Personel mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                                        'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                                        'Personel melakukan pemeriksaan kesehatan minimal 1 kali dalam setahun',
                                        'Penjamah pangan sudah mendapatkan penyuluhan keamanan pangan siap saji',
                                        'Peralatan untuk pengolahan pangan berbahan kuat',
                                        'Peralatan untuk pengolahan pangan tidak berkarat',
                                        'Peralatan untuk pengolahan pangan tara pangan (food grade)',
                                        'Peralatan untuk pengolahan pangan bersih sebelum digunakan',
                                        'Peralatan untuk pengolahan pangan Setelah digunakan kondisi bersih dan kering',
                                        'Peralatan untuk pengolahan pangan Berbeda untuk pangan matang dan pangan mentah',
                                        'Peralatan untuk pengolahan pangan sekali pakai tidak dipakai ulang dan food grade',
                                        'Alat pengering peralatan seperti lap/kain majun selalu dalam kondisi bersih dan diganti secara rutin untuk menghindari kontaminasi silang',
                                        'Peralatan pembersih tidak menyebabkan kontaminasi silang (tidak boleh menggunakan sapu ijuk atau kemoceng)',
                                        'Pangan matang yang mudah rusak harus sudah dikonsumsi 4 jam setelah matang',
                                        'Pangan matang panas dijaga pada suhu >60°C',
                                        'Pangan matang dingin dijaga pada suhu < 5°C',
                                        'Jika terdapat menu pangan segar yang langsung dikonsumsi seperti buah potong dan salad, maka pangan tersebut disimpan dalam suhu yang aman yaitu di bawah 5 oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)',
                                        'Es batu sebagai pangan dibuat dari air matang/sudah dimasak atau dari sumber terpercaya',
                                        'Pangan matang sisa yang sudah melampaui batas waktu konsumsi dan suhu penyimpanan tidak boleh dikonsumsi',
                                        'Air untuk minum sesuai dengan standar kualitas air minum/air sudah diolah/dimasak',
                                        'Pangan yang tidak dikemas harus disajikan dengan penutup (tudung saji) atau di dalam lemari display yang tertutup',
                                        'Tempat memajang pangan matang tidak terjadi kontak dengan vektor dan binatang pembawa penyakit.',
                                        'Pengemasan dilakukan secara higiene (personil cuci tangan dan menggunakan sarung tangan dengan kondisi baik)',
                                        'Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)',
                                    ];
                                }

                                public function title(): string
                                {
                                    return 'Gerai';
                                }
                            }
                        ];
                    }
                    }, 'REPORT_KANTIN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inspection.kantin.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to submit Kantin form', [
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
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        // Tambahkan user_id dari user yang sedang login
        $data['user_id'] = Auth::id();
        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 51) * 100);

        $insert = Kantin::create($data);

        if (!$insert) {
            return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Sentra Kantin gagal dibuat, silahkan coba lagi');
        }

        return redirect(route('kantin.show', ['kantin' => $insert->id]))->with('success', 'penilaian / inspeksi Sentra Kantin berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kantin $kantin)
    {
        $geraiKantin = GeraiKantin::where('id-kantin', $kantin['id'])->get();
        return view('pages.inspection.show', [
            'page_name' => 'inspection',
            'form_data' => $kantin,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Sentra Kantin',
            'gerai_kantin' => $geraiKantin,
            'edit_route' => route('kantin.edit', ['kantin' => $kantin['id']]),
            'destroy_route' => route('kantin.destroy', ['kantin' => $kantin['id']]),
            'export_route' => route(
                'kantin.index',
                [
                    'export' => 'pdf',
                    'id' => $kantin['id'],
                ],
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kantin $kantin)
    {
        $geraiKantin = GeraiKantin::where('id-kantin', $kantin['id'])->get();
        return view('pages.inspection.kantin.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $kantin,
            'gerai_kantin' => $geraiKantin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kantin $kantin)
    {
        // Cek autentikasi user
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to update Kantin form', [
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
        
        // Handle custom instansi pemeriksa input
        if (!empty($request->input('instansi-lainnya'))) {
            $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
        }
        
        $file = $request->file('dokumen_slhs');
        if ($file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 51) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($kantin->kelurahan)) {
                $data['kelurahan'] = $kantin->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($kantin->kecamatan)) {
                $data['kecamatan'] = $kantin->kecamatan;
            }
            if (empty($data['subjek']) && !empty($kantin->subjek)) {
                $data['subjek'] = $kantin->subjek;
            }
            if (empty($data['alamat']) && !empty($kantin->alamat)) {
                $data['alamat'] = $kantin->alamat;
            }

            $insert = Kantin::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Sentra Kantin gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('kantin.show', ['kantin' => $insert->id]))->with('success', 'penilaian / inspeksi Sentra Kantin berhasil dibuat');
        }

        // Remove user_id and action from update data to preserve original user
        unset($data['user_id']);
        unset($data['action']);
        
        $update = $kantin->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Sentra Kantin gagal diubah, silahkan coba lagi');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('kantin.show', ['kantin' => $kantin['id']]))->with('success', 'form informasi dan penilaian Sentra Kantin berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $kantin = Kantin::where('id', $id)->withTrashed()->first();

        if ($kantin['deleted_at']) {
            $kantin->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $kantin->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Kantin berhasil dihapus');
    }
}