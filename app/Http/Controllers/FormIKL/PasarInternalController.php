<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\PasarInternal;
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

class PasarInternalController extends Controller {
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
            Form::input('number', 'Jumlah Total Pedagang', 'jumlah_total_pedagang'),
            Form::input('number', 'Jumlah Total Kios', 'jumlah_total_kios'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian')
        ];
    }

    protected function formPenilaian() {
        return [
            Form::h(2, 'A. Bangunan Pasar', 'bangunan-pasar'),

            Form::select('Bangunan pasar terpelihara', 'bp001', 1),
            Form::select('Lingkungan pasar bersih setiap hari', 'bp002', 1),
            Form::select('Jalan dan lorong dalam pasar tidak ada sampah', 'bp003', 1),
            Form::select('Pasar tidak bau, tidak gelap, tidak pengap, memiliki lubang angin/ventilasi dan pencahayaan yang baik (tidak panas dan terang)', 'bp004', 1),
            Form::select('Lantai tidak retak, rata, tidak licin, dan mudah dibersihkan', 'bp005', 1),
            Form::select('Lantai tidak ada genangan air', 'bp006', 1),
            Form::select('Semua bahan dan peralatan yang digunakan diletakkan pada tempatnya dan tidak menghalangi jalan/lorong', 'bp007', 1),
            Form::select('Semua fasilitas pasar terawat baik dan bersih', 'bp008', 1),
            Form::select('Lorong pasar tidak digunakan untuk berjualan', 'bp009', 1),

            Form::h(2, 'B. Bangunan Kios/Los', 'bangunan-kios-los'),

            Form::select('Setiap kios/los bersih dan tidak ada sampah berserakan', 'bkl001', 1),
            Form::select('Tidak ada sampah menumpuk dan membusuk', 'bkl002', 1),
            Form::select('Ada meja tempat berjualan dan kondisi bersih', 'bkl003', 1),

            Form::h(2, 'C. Tempat Pembuangan Sampah', 'tempat-pembuangan-sampah'),

            Form::select('Mempunyai Tempat Penampungan Sampah Sementara (TPS)', 'tps001', 1),
            Form::select('TPS tidak bau, tidak ada sampah berserakan', 'tps002', 1),
            Form::select('Tersedia tempat sampah di setiap kios', 'tps003', 1),
            Form::select('Tersedia tempat sampah di los pasar', 'tps004', 1),
            Form::select('Ada pemisahan sampah basah dan sampah kering', 'tps005', 1),

            Form::h(2, 'D. Saluran Limbah dan Drainase', 'saluran-limbah-drainase'),

            Form::select('Saluran limbah cair/drainase disemen dan ditutup dengan kisi-kisi dari logam', 'sld001', 1),
            Form::select('Aliran air limbah/drainase lancar', 'sld002', 1),
            Form::select('Selokan/saluran air di los basah (ikan, daging, unggas potong, sayur mayur, tempat pemarutan kelapa) tidak ada genangan air', 'sld003', 1),

            Form::h(2, 'E. Toilet', 'toilet'),

            Form::select('Tersedia toilet laki-laki dan perempuan dan tidak antri', 't001', 1),
            Form::select('Toilet bersih, tidak berbau, dan tidak ada jentik nyamuk', 't002', 1),
            Form::select('Mempunyai lubang angin/ventilasi dan cukup cahaya', 't003', 1),
            Form::select('Tersedia air yang cukup', 't004', 1),
            Form::select('Tersedia tempat cuci tangan yang dilengkapi dengan sabun', 't005', 1),
            Form::select('Ada penanggung jawab pemeliharaan dan kebersihan toilet', 't006', 1),

            Form::h(2, 'F. Air Bersih', 'air-bersih'),

            Form::select('Tersedia air bersih dengan jumlah yang cukup dan mengalir dengan lancar', 'ab001', 1),
            Form::select('Kran air terletak di tempat yang strategis dan mudah dijangkau', 'ab002', 1),
            Form::select('Air yang digunakan harus bersih, tidak berwarna, tidak berbau, dan tidak berasa', 'ab003', 1),

            Form::h(2, 'G. Tempat Penjualan Makanan dan Bahan Pangan', 'tempat-penjualan-makanan'),

            Form::select('Los tempat penjualan makanan & bahan pangan tersedia tempat cuci tangan dengan air mengalir yang dilengkapi dengan sabun', 'tpmmm001', 1),
            Form::select('Meja/tempat untuk menjual makanan dan bahan pangan 60 cm di atas lantai', 'tpmmm002', 1),
            Form::select('Tempat pemotongan ayam berada di lokasi khusus di luar pasar', 'tpmmm003', 1),
            Form::select('Tempat penjualan makanan & bahan pangan terbuat dari bahan yang tahan karat, bukan dari kayu', 'tpmmm004', 1),
            Form::select('Alas pemotong (talenan) untuk makanan dan bahan pangan harus selalu dibersihkan', 'tpmmm005', 1),
            Form::select('Tersedia alat pendingin atau menggunakan es batu untuk tempat penyimpanan ikan segar, daging, dan unggas potong yang akan dijual', 'tpmmm006', 1),
            Form::select('Penyajian dagangan dikelompokkan sesuai jenis', 'tpmmm007', 1),
            Form::select('Pernah dilakukan pengambilan contoh makanan untuk pemeriksaan ke laboratorium', 'tpmmm008', 1),
            Form::select('Untuk pedagang makanan siap saji pernah dilakukan usap dubur oleh petugas kesehatan', 'tpmmm009', 1),

            Form::h(2, 'H. Pengendalian Binatang Penularan Penyakit', 'pengendalian-binatang'),

            Form::select('Dilakukan penyemprotan lalat, nyamuk, kecoa, dan tikus setiap bulan', 'pb001', 1),
            Form::select('Tidak ada lalat di tempat penjualan makanan matang (siap saji)', 'pb002', 1),
            Form::select('Tidak ada binatang peliharaan (kucing/anjing) berkeliaran di dalam pasar', 'pb003', 1),

            Form::h(2, 'I. Keamanan Pasar', 'keamanan-pasar'),

            Form::select('Pengelola pasar harus menjaga keamanan pasar', 'kpp001', 1),
            Form::select('Alat pemadam kebakaran tersedia dalam jumlah cukup, diletakkan di tempat yang strategis dan mudah dijangkau', 'kpp002', 1),

            Form::h(2, 'J. Pencahayaan, Suhu, dan Kelembaban', 'pencahayaan-suhu-kelembaban'),

            Form::select('Pencahayaan alam dan buatan cukup terang untuk melakukan kegiatan', 'psk001', 1),
            Form::select('Suhu di setiap kios/los tidak panas', 'psk002', 1),

            Form::h(2, 'K. Tempat Cuci Tangan', 'tempat-cuci-tangan'),

            Form::select('Tersedia tempat cuci tangan dengan air mengalir dengan jumlah yang cukup', 'tctt001', 1),
            Form::select('Dilengkapi sabun, dijaga kebersihannya, dan terletak di lokasi yang mudah terjangkau', 'tctt002', 1),

            Form::h(2, 'L. Tempat Parkir', 'tempat-parkir'),

            Form::select('Tersedia tempat parkir untuk kendaraan roda dua, roda tiga, roda empat, dan tempat bongkar muat barang dagangan', 'tp001', 1),
            Form::select('Jalur masuk dan keluar pasar terpisah dengan jelas', 'tp002', 1),

            Form::h(2, 'M. Pedagang/Karyawan', 'pedagang-karyawan'),

            Form::select('Pedagang dan/atau karyawan menggunakan pakaian kerja dan alat pelindung diri (APD seperti celemek, sepatu boot, sarung tangan, tutup kepala/topi)', 'pkk001', 1),
            Form::select('Ada kelompok atau asosiasi pedagang pasar', 'pkk002', 1),
            Form::select('Ada pelatihan dalam rangka meningkatkan kebersihan, keamanan, dan kesehatan pasar bagi pedagang dan pengelola pasar dalam 3 bulan terakhir', 'pkk003', 1),
            Form::select('Tidak merokok saat berjualan', 'pkk004', 1),
            Form::select('Tidak meludah sembarangan', 'pkk005', 1),
            Form::select('Pedagang daging, ikan, dan unggas potong selalu mencuci tangan dengan air mengalir dan sabun setelah menjamah barang dagangannya', 'pkk006', 1),
            Form::select('Kuku pedagang pendek dan bersih', 'pkk007', 1),

            Form::h(2, 'N. Pengunjung', 'pengunjung'),

            Form::select('Tersedia himbauan/slogan untuk masyarakat pengunjung', 'p001', 1),
            Form::select('Tersedia toilet untuk masyarakat pengunjung', 'p002', 1),
            Form::select('Pengunjung/pembeli berperilaku hidup bersih dan sehat (PHBS) (cuci tangan pakai sabun setelah menjamah ikan, daging, unggas potong, dan makanan matang, tidak buang sampah sembarangan, tidak meludah, dan sebagainya)', 'p003', 1),
        ];
    }

    protected function formPenilaianName() {
        return [
            // A. Bangunan Pasar
            'bp001', 'bp002', 'bp003', 'bp004', 'bp005', 'bp006', 'bp007', 'bp008', 'bp009',
            
            // B. Bangunan Kios/Los
            'bkl001', 'bkl002', 'bkl003',
            
            // C. Tempat Pembuangan Sampah
            'tps001', 'tps002', 'tps003', 'tps004', 'tps005',
            
            // D. Saluran Limbah dan Drainase
            'sld001', 'sld002', 'sld003',
            
            // E. Toilet
            't001', 't002', 't003', 't004', 't005', 't006',
            
            // F. Air Bersih
            'ab001', 'ab002', 'ab003',
            
            // G. Tempat Penjualan Makanan dan Bahan Pangan
            'tpmmm001', 'tpmmm002', 'tpmmm003', 'tpmmm004', 'tpmmm005', 'tpmmm006', 'tpmmm007', 'tpmmm008', 'tpmmm009',
            
            // H. Pengendalian Binatang Penularan Penyakit
            'pb001', 'pb002', 'pb003',
            
            // I. Keamanan Pasar
            'kpp001', 'kpp002',
            
            // J. Pencahayaan, Suhu, dan Kelembaban
            'psk001', 'psk002',
            
            // K. Tempat Cuci Tangan
            'tctt001', 'tctt002',
            
            // L. Tempat Parkir
            'tp001', 'tp002',
            
            // M. Pedagang/Karyawan
            'pkk001', 'pkk002', 'pkk003', 'pkk004', 'pkk005', 'pkk006', 'pkk007',
            
            // N. Pengunjung
            'p001', 'p002', 'p003'
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
                $item = PasarInternal::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Pasar Internal',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Pasar', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)],
                        ['Jumlah Total Pedagang', $item['jumlah_total_pedagang']],
                ['Jumlah Total Kios', $item['jumlah_total_kios']]
                    ],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']
                ])->download('BAIKL_PASAR_INTERNAL_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return PasarInternal::withTrashed()->get()->map(function ($item) {
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
                            'Jumlah Total Pedagang', 'Jumlah Total Kios', 'Nama Pemeriksa', 'Instansi Pemeriksa', 'Tanggal Penilaian',
                            'Catatan Lain', 'Rencana Tindak Lanjut', 'Dibuat', 'Diperbarui', 'Dihapus',
                            
                            // A. Bangunan Pasar
                            'Bangunan pasar terpelihara',
                            'Lingkungan pasar bersih setiap hari',
                            'Jalan dan lorong dalam pasar tidak ada sampah',
                            'Pasar tidak bau, tidak gelap, tidak pengap, memiliki lubang angin/ventilasi dan pencahayaan yang baik (tidak panas dan terang)',
                            'Lantai tidak retak, rata, tidak licin, dan mudah dibersihkan',
                            'Lantai tidak ada genangan air',
                            'Semua bahan dan peralatan yang digunakan diletakkan pada tempatnya dan tidak menghalangi jalan/lorong',
                            'Semua fasilitas pasar terawat baik dan bersih',
                            'Lorong pasar tidak digunakan untuk berjualan',
                            
                            // B. Bangunan Kios/Los
                            'Setiap kios/los bersih dan tidak ada sampah berserakan',
                            'Tidak ada sampah menumpuk dan membusuk',
                            'Ada meja tempat berjualan dan kondisi bersih',
                            
                            // C. Tempat Pembuangan Sampah
                            'Mempunyai Tempat Penampungan Sampah Sementara (TPS)',
                            'TPS tidak bau, tidak ada sampah berserakan',
                            'Tersedia tempat sampah di setiap kios',
                            'Tersedia tempat sampah di los pasar',
                            'Ada pemisahan sampah basah dan sampah kering',
                            
                            // D. Saluran Limbah dan Drainase
                            'Saluran limbah cair/drainase disemen dan ditutup dengan kisi-kisi dari logam',
                            'Aliran air limbah/drainase lancar',
                            'Selokan/saluran air di los basah (ikan, daging, unggas potong, sayur mayur, tempat pemarutan kelapa) tidak ada genangan air',
                            
                            // E. Toilet
                            'Tersedia toilet laki-laki dan perempuan dan tidak antri',
                            'Toilet bersih, tidak berbau, dan tidak ada jentik nyamuk',
                            'Mempunyai lubang angin/ventilasi dan cukup cahaya',
                            'Tersedia air yang cukup',
                            'Tersedia tempat cuci tangan yang dilengkapi dengan sabun',
                            'Ada penanggung jawab pemeliharaan dan kebersihan toilet',
                            
                            // F. Air Bersih
                            'Tersedia air bersih dengan jumlah yang cukup dan mengalir dengan lancar',
                            'Kran air terletak di tempat yang strategis dan mudah dijangkau',
                            'Air yang digunakan harus bersih, tidak berwarna, tidak berbau, dan tidak berasa',
                            
                            // G. Tempat Penjualan Makanan dan Bahan Pangan
                            'Los tempat penjualan makanan & bahan pangan tersedia tempat cuci tangan dengan air mengalir yang dilengkapi dengan sabun',
                            'Meja/tempat untuk menjual makanan dan bahan pangan 60 cm di atas lantai',
                            'Tempat pemotongan ayam berada di lokasi khusus di luar pasar',
                            'Tempat penjualan makanan & bahan pangan terbuat dari bahan yang tahan karat, bukan dari kayu',
                            'Alas pemotong (talenan) untuk makanan dan bahan pangan harus selalu dibersihkan',
                            'Tersedia alat pendingin atau menggunakan es batu untuk tempat penyimpanan ikan segar, daging, dan unggas potong yang akan dijual',
                            'Penyajian dagangan dikelompokkan sesuai jenis',
                            'Pernah dilakukan pengambilan contoh makanan untuk pemeriksaan ke laboratorium',
                            'Untuk pedagang makanan siap saji pernah dilakukan usap dubur oleh petugas kesehatan',
                            
                            // H. Pengendalian Binatang Penularan Penyakit
                            'Dilakukan penyemprotan lalat, nyamuk, kecoa, dan tikus setiap bulan',
                            'Tidak ada lalat di tempat penjualan makanan matang (siap saji)',
                            'Tidak ada binatang peliharaan (kucing/anjing) berkeliaran di dalam pasar',
                            
                            // I. Keamanan Pasar
                            'Pengelola pasar harus menjaga keamanan pasar',
                            'Alat pemadam kebakaran tersedia dalam jumlah cukup, diletakkan di tempat yang strategis dan mudah dijangkau',
                            
                            // J. Pencahayaan, Suhu, dan Kelembaban
                            'Pencahayaan alam dan buatan cukup terang untuk melakukan kegiatan',
                            'Suhu di setiap kios/los tidak panas',
                            
                            // K. Tempat Cuci Tangan
                            'Tersedia tempat cuci tangan dengan air mengalir dengan jumlah yang cukup',
                            'Dilengkapi sabun, dijaga kebersihannya, dan terletak di lokasi yang mudah terjangkau',
                            
                            // L. Tempat Parkir
                            'Tersedia tempat parkir untuk kendaraan roda dua, roda tiga, roda empat, dan tempat bongkar muat barang dagangan',
                            'Jalur masuk dan keluar pasar terpisah dengan jelas',
                            
                            // M. Pedagang/Karyawan
                            'Pedagang dan/atau karyawan menggunakan pakaian kerja dan alat pelindung diri (APD seperti celemek, sepatu boot, sarung tangan, tutup kepala/topi)',
                            'Ada kelompok atau asosiasi pedagang pasar',
                            'Ada pelatihan dalam rangka meningkatkan kebersihan, keamanan, dan kesehatan pasar bagi pedagang dan pengelola pasar dalam 3 bulan terakhir',
                            'Tidak merokok saat berjualan',
                            'Tidak meludah sembarangan',
                            'Pedagang daging, ikan, dan unggas potong selalu mencuci tangan dengan air mengalir dan sabun setelah menjamah barang dagangannya',
                            'Kuku pedagang pendek dan bersih',
                            
                            // N. Pengunjung
                            'Tersedia himbauan/slogan untuk masyarakat pengunjung',
                            'Tersedia toilet untuk masyarakat pengunjung',
                            'Pengunjung/pembeli berperilaku hidup bersih dan sehat (PHBS) (cuci tangan pakai sabun setelah menjamah ikan, daging, unggas potong, dan makanan matang, tidak buang sampah sembarangan, tidak meludah, dan sebagainya)'
                        ];
                    }
                }, 'REPORT_PASAR_INTERNAL_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create() {
        return view('pages.inspection.pasar-internal.create', [
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
            Log::info('PasarInternalController store called', [
                'user_id' => Auth::check() ? Auth::id() : 3,
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
                'jumlah_total_pedagang' => 'required|integer|min:1',
                'jumlah_total_kios' => 'required|integer|min:1',
            ]);

            Log::info('Validation passed for Pasar Internal store');

            $data = $request->all();
            // Set user_id: 3 for guest, actual user_id for logged users
            $data['user_id'] = Auth::check() ? Auth::id() : 3;

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), function($carry, $column) use ($request) {
                // For pasar internal scoring: "Sesuai" = 0 in form but should count as 1 point
                // "Tidak Sesuai" = 1 in form but should count as 0 point
                $value = $request->input($column, 0);
                return $carry + ($value == 0 ? 1 : 0); // 1 point for "Sesuai" (value=0), 0 point for "Tidak Sesuai" (value=1)
            }, 0) / 59 * 100);

            Log::info('Attempting to create Pasar Internal record', ['data_keys' => array_keys($data)]);

            $insert = PasarInternal::create($data);

            if (!$insert) {
                Log::error('Failed to create Pasar Internal record - insert returned false', [
                    'user_id' => Auth::check() ? Auth::id() : 3,
                    'data' => $data
                ]);
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Pasar Internal gagal dibuat, silahkan coba lagi');
            }

            Log::info('Pasar Internal record created successfully', ['id' => $insert->id]);

            return redirect(route('pasar-internal.show', ['pasar_internal' => $insert->id]))->with('success', 'penilaian / inspeksi Pasar Internal berhasil dibuat');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in Pasar Internal store', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali form Anda.');
        } catch (\Exception $e) {
            Log::error('Exception occurred in Pasar Internal store', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data penilaian Pasar Internal. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PasarInternal $pasarInternal)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $pasarInternal,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Pasar Internal',
            'edit_route' => route('pasar-internal.edit', ['pasar_internal' => $pasarInternal['id']]),
            'destroy_route' => route('pasar-internal.destroy', ['pasar_internal' => $pasarInternal['id']]),
            'export_route' => route('pasar-internal.index', ['export' => 'pdf', 'id' => $pasarInternal['id']])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PasarInternal $pasarInternal)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to access Pasar Internal edit form', ['pasar_internal_id' => $pasarInternal->id]);
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return view('pages.inspection.pasar-internal.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'hasil_pengukuran' => $this->hasilPengukuran(),
            'form_data' => $pasarInternal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PasarInternal $pasarInternal)
    {
        try {
            Log::info('PasarInternalController update called', [
                'pasar_internal_id' => $pasarInternal->id,
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
                'jumlah_total_pedagang' => 'required|integer|min:1',
                'jumlah_total_kios' => 'required|integer|min:1',
            ]);
            
            Log::info('Validation passed for Pasar Internal update');

            $data = $request->all();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            foreach ($this->formPenilaianName() as $column) {
                $data[$column] = $request->input($column, '0');
            }

            $data['skor'] = (int) (array_reduce($this->formPenilaianName(), function($carry, $column) use ($request) {
                // For pasar internal scoring: "Sesuai" = 0 in form but should count as 1 point
                // "Tidak Sesuai" = 1 in form but should count as 0 point
                $value = $request->input($column, 0);
                return $carry + ($value == 0 ? 1 : 0); // 1 point for "Sesuai" (value=0), 0 point for "Tidak Sesuai" (value=1)
            }, 0) / 59 * 100);

            if (isset($data['action']) && $data['action'] == 'duplicate') {
                // Add auth user ID only for duplicate action
                $data['user_id'] = Auth::id();

                // For duplicate, preserve the original values if current values are empty
                if (empty($data['kelurahan']) && !empty($pasarInternal->kelurahan)) {
                    $data['kelurahan'] = $pasarInternal->kelurahan;
                }
                if (empty($data['kecamatan']) && !empty($pasarInternal->kecamatan)) {
                    $data['kecamatan'] = $pasarInternal->kecamatan;
                }
                if (empty($data['subjek']) && !empty($pasarInternal->subjek)) {
                    $data['subjek'] = $pasarInternal->subjek;
                }
                if (empty($data['alamat']) && !empty($pasarInternal->alamat)) {
                    $data['alamat'] = $pasarInternal->alamat;
                }

                Log::info('Attempting to duplicate Pasar Internal record', ['original_id' => $pasarInternal->id]);

                $insert = PasarInternal::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Pasar Internal record', [
                        'original_id' => $pasarInternal->id,
                        'user_id' => Auth::check() ? Auth::id() : 3,
                        'data' => $data
                    ]);
                    return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Pasar Internal gagal dibuat, silahkan coba lagi');
                }

                Log::info('Pasar Internal record duplicated successfully', ['new_id' => $insert->id]);
                return redirect(route('pasar-internal.show', ['pasar_internal' => $insert->id]))->with('success', 'penilaian / inspeksi Pasar Internal berhasil dibuat');
            }

            // Remove user_id from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            Log::info('Attempting to update Pasar Internal record', [
                'pasar_internal_id' => $pasarInternal->id,
                'data_keys' => array_keys($data)
            ]);
            
            $update = $pasarInternal->update($data);

            Log::info('Update result', ['success' => $update, 'pasar_internal_id' => $pasarInternal->id]);

            if (!$update) {
                Log::error('Update failed for pasar internal', [
                    'pasar_internal_id' => $pasarInternal->id,
                    'user_id' => Auth::check() ? Auth::id() : 3,
                    'data' => $data
                ]);
                return redirect()->back()->with('error', 'form informasi dan penilaian Pasar Internal gagal diubah');
            }

            Log::info('Update successful, redirecting', ['pasar_internal_id' => $pasarInternal->id]);
            return redirect(route('pasar-internal.show', ['pasar_internal' => $pasarInternal['id']]))->with('success', 'form informasi dan penilaian Pasar Internal berhasil diubah');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in Pasar Internal update', [
                'pasar_internal_id' => $pasarInternal->id,
                'user_id' => Auth::check() ? Auth::id() : 3,
                'errors' => $e->errors(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali form Anda.');
        } catch (\Exception $e) {
            Log::error('Exception occurred in Pasar Internal update', [
                'pasar_internal_id' => $pasarInternal->id,
                'user_id' => Auth::check() ? Auth::id() : 3,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengubah data penilaian Pasar Internal. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('Unauthenticated user attempted to destroy Pasar Internal data', ['pasar_internal_id' => $id]);
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $pasarInternal = PasarInternal::where('id', $id)->withTrashed()->first();

        if ($pasarInternal['deleted_at']) {
            $pasarInternal->update(['deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $pasarInternal->delete();

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Pasar Internal berhasil dihapus');
    }
}
