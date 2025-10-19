<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\RenangPemandian;
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

class RenangPemandianController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Usaha', 'subjek'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('date', 'Tanggal/Bulan/Tahun mulai beroperasi', 'u004'),
            Form::input('number', 'Luas bangunan (m²)', 'u005'),
            Form::input('select', 'Status Operasi', 'status-operasi'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('text', 'Titik GPS', 'koordinat'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian')];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Tempat', 'tempat'),

            Form::checkbox('Tempat usaha tidak terletak pada daerah rawan longsor', 't001', 2),
            Form::checkbox('Memiliki ruangan yang berfungsi untuk tempat kerja, ruang Arena Renang / Pemandian Alam, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai', 't002', 2),
            Form::checkbox('Memiliki perlengkapan pendukung untuk usaha Arena Renang / Pemandian Alam (sesuai jenis usaha) yang memadai', 't003', 2),
            Form::checkbox('Memiliki papan nama tempat usaha Arena Renang / Pemandian Alam', 't004'),
            Form::checkbox('memiliki sistem pelayanan usaha Arena Renang / Pemandian Alam yang terstandar', 't005'),

            Form::h(2, 'Peralatan', 'peralatan'),

            Form::checkbox('Peralatan dan perlengkapan yang digunakan untuk pengaturan udara (jika arena tertutup)', 'p001', 2),
            Form::checkbox('Perlengkapan instalasi penyediaan air', 'p002', 2),
            Form::checkbox('Peralatan/Perlengkapan instalasi listrik yang memadai', 'p003', 2),
            Form::checkbox('Perlengkapan instalasi pemadam kebakaran yang terstandar', 'p004', 2),
            Form::checkbox('Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)', 'p005', 2),

            Form::h(2, 'Petugas Penjamah makanan dan petugas kebersihan', 'petugas-penjamah-makanan-dan-petugas-kebersihan'),

            Form::checkbox('Penjamah makanan memiliki surat keterangan mengikuti penyuluhan atau pelatihan higiene sanitasi makanan', 'pk001', 2),
            Form::checkbox('Petugas kebersihan memiliki surat keterangan mengikuti penyuluhan/sertifikat pelatihan petugas kebersihan (cleaning service)', 'pk002', 3),
            Form::checkbox('Menggunakan pakaian kerja yang bersih dan rapi', 'pk003', 2),
            Form::checkbox('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun', 'pk004', 2),
            Form::checkbox('Petugas pemandu/guide renang memiliki surat keterangan mengikuti pelatihan pemandu renang termasuk pertolongan pertama kedaruratan dalam air', 'pk005', 3),

            Form::h(2, 'Ketersediaan sarana dan bangunan untuk pengunjung', 'ketersediaan-sarana-dan-bangunan-untuk-pengunjung'),

            Form::checkbox('bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya', 'sb001', 2),
            Form::checkbox('lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air', 'sb002', 2),
            Form::checkbox('dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah', 'sb003', 2),
            Form::checkbox('Toilet dalam keadaan bersih', 'sb004', 2),
            Form::checkbox('atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup', 'sb005'),
            Form::checkbox('Tersedia ruang Arena Renang / Pemandian Alam dan perlengkapannya dalam keadaan bersih', 'sb006', 2),
            Form::checkbox('Kondisi ruangan lobby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih', 'sb007', 2),
            Form::checkbox('Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara', 'sb008', 2),
            Form::checkbox('Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota', 'sb009', 2),
            Form::checkbox('Standar sarana dan bangunan mengikuti standar ketentuan perundang-undangan terkait arena renang', 'sb010', 2),
            Form::checkbox('Kondisi tempat wahana olah raga (seperti kolam renang, tempat permandian alam) dalam keadaan bersih', 'sb011', 2),
            Form::checkbox('Dilakukan pembersihan secara rutin terhadap wahana olah raga (seperti kolam renang, tempat permandian alam) dan sekitarnya', 'sb012', 2),

            Form::h(2, 'Ketersediaan air', 'ketersediaan-air'),

            Form::checkbox('Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan', 'a001', 2),
            Form::checkbox('Pengujian contoh air minum wajib dilakukan oleh pihak usaha gelanggang/arena renang di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi sekurang-kurangnya 6 (enam) bulan sekali.', 'a002', 2),
            Form::checkbox('Air tersedia sepanjang waktu dalam kondisi cukup', 'a003', 2),
            Form::checkbox('Wajib memenuhi peryaratan kualitas air kolam renang/air permandian alam minimal parameter fisik e.coli (khusus permandian alam) dan sisa chlor, atau residu bahan desinfektan lainnya yang disampaikan kepada pengunjung setiap hari.', 'a004', 4),
            Form::checkbox('Pengujian contoh air kolam renang/tempat permandian alam wajib dilakukan oleh pihak Arena Renang / Pemandian Alam setiap hari', 'a005', 2),
            Form::checkbox('Dilakukan penggantian air kolam renang secara berkala sesuai kondisi kualitas air.', 'a006', 3),
            Form::checkbox('Dilakukan pembersihan air dan atau melakukan desinfeksi air dengan bahan desinfektan air yang sesuai takaran secara rutin setiap hari', 'a007', 3),

            Form::h(2, 'Kondisi udara dan kualitasnya', 'kondisi-udara-dan-kualitasnya'),

            Form::checkbox('Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya', 'uk001', 2),
            Form::checkbox('Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman', 'uk002', 2),
            Form::checkbox('Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan', 'uk003', 2),
            Form::checkbox('Pengujian contoh udara wajib dilakukan oleh pihak usaha tempat gelanggang renang/tempat permandian alam di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi', 'uk004', 2),
            Form::checkbox('Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala', 'uk005', 2),

            Form::h(2, 'Kondisi Tempat pengelolaan makanan dan minuman dan produknya', 'kondisi-tempat-pengelolaan-makanan-dan-minuman-dan-produknya'),

            Form::checkbox('1. Menyesuaikan kriteria restoran/rumah makan dan 2. Menyesuaikan kriteria kantin/sentra jajanan/foodtruck, dan sejenis lainnya)', 'k001', 12),

            Form::h(2, 'Pengendalian vektor dan binatang pembawa penyakit', 'pengendalian-vektor-dan-binatang-pembawa-penyakit'),

            Form::checkbox('Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan', 'v001', 2),
            Form::checkbox('Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya', 'v002', 2),

            Form::h(2, 'Penilaian sendiri/mandiri (Self Assesment)', 'penilaian-mandiri-self-assesment'),

            Form::checkbox('Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan Gelanggang Renang dan Tempat Pemandian Alam secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)', 's001', 3)];
    }

    protected function formPenilaianName()
    {
        return [
            't001',
            't002',
            't003',
            't004',
            't005',

            'p001',
            'p002',
            'p003',
            'p004',
            'p005',

            'pk001',
            'pk002',
            'pk003',
            'pk004',
            'pk005',

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

            'a001',
            'a002',
            'a003',
            'a004',
            'a005',
            'a006',
            'a007',

            'uk001',
            'uk002',
            'uk003',
            'uk004',
            'uk005',

            'k001',

            'v001',
            'v002',

            's001'];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = RenangPemandian::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Kolam Renang Pemandian',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Usaha', $item['subjek']],
                        ['Nama Pengelola/Pemilik/Penanggung Jawab', $item['pengelola']],
                        ['Kontak Pengelola', $item['kontak']],
                        ['Alamat', $item['alamat']],
                        ['Kelurahan', $item['kelurahan']],
                        ['Kecamatan', $item['kecamatan']],
                        ['Skor', Export::score($item['skor'], Export::TUJUH_PULUH)]],
                    'catatan' => $item['catatan-lain'],
                    'rencana' => $item['rencana-tindak-lanjut'],
                    'pengelola' => $item['pengelola'],
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_KOLAM_RENANG_PEMANDIAN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings
                {
                    public function collection()
                    {
                        return RenangPemandian::withTrashed()->get()->map(function ($item) {
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

                            'Tanggal/Bulan/Tahun mulai beroperasi',
                            'Luas bangunan (m²)',

                            'Tempat usaha tidak terletak pada daerah rawan longsor',
                            'Memiliki ruangan yang berfungsi untuk tempat kerja, ruang Arena Renang / Pemandian Alam, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai',
                            'Memiliki perlengkapan pendukung untuk usaha Arena Renang / Pemandian Alam (sesuai jenis usaha) yang memadai',
                            'Memiliki papan nama tempat usaha Arena Renang / Pemandian Alam',
                            'memiliki sistem pelayanan usaha Arena Renang / Pemandian Alam yang terstandar',
                            'Peralatan dan perlengkapan yang digunakan untuk pengaturan udara (jika arena tertutup)',
                            'Perlengkapan instalasi penyediaan air',
                            'Peralatan/Perlengkapan instalasi listrik yang memadai',
                            'Perlengkapan instalasi pemadam kebakaran yang terstandar',
                            'Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)',
                            'Penjamah makanan memiliki surat keterangan mengikuti penyuluhan atau pelatihan higiene sanitasi makanan',
                            'Petugas kebersihan memiliki surat keterangan mengikuti penyuluhan/sertifikat pelatihan petugas kebersihan (cleaning service)',
                            'Menggunakan pakaian kerja yang bersih dan rapi',
                            'Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun',
                            'Petugas pemandu/guide renang memiliki surat keterangan mengikuti pelatihan pemandu renang termasuk pertolongan pertama kedaruratan dalam air',
                            'bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya',
                            'lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air',
                            'dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah',
                            'Toilet dalam keadaan bersih',
                            'atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup',
                            'Tersedia ruang Arena Renang / Pemandian Alam dan perlengkapannya dalam keadaan bersih',
                            'Kondisi ruangan lobby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih',
                            'Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara',
                            'Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota',
                            'Standar sarana dan bangunan mengikuti standar ketentuan perundang-undangan terkait arena renang',
                            'Kondisi tempat wahana olah raga (seperti kolam renang, tempat permandian alam) dalam keadaan bersih',
                            'Dilakukan pembersihan secara rutin terhadap wahana olah raga (seperti kolam renang, tempat permandian alam) dan sekitarnya',
                            'Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan',
                            'Pengujian contoh air minum wajib dilakukan oleh pihak usaha gelanggang/arena renang di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi sekurang-kurangnya 6 (enam) bulan sekali.',
                            'Air tersedia sepanjang waktu dalam kondisi cukup',
                            'Wajib memenuhi peryaratan kualitas air kolam renang/air permandian alam minimal parameter fisik e.coli (khusus permandian alam) dan sisa chlor, atau residu bahan desinfektan lainnya yang disampaikan kepada pengunjung setiap hari.',
                            'Pengujian contoh air kolam renang/tempat permandian alam wajib dilakukan oleh pihak Arena Renang / Pemandian Alam setiap hari',
                            'Dilakukan penggantian air kolam renang secara berkala sesuai kondisi kualitas air.',
                            'Dilakukan pembersihan air dan atau melakukan desinfeksi air dengan bahan desinfektan air yang sesuai takaran secara rutin setiap hari',
                            'Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya',
                            'Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman',
                            'Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan',
                            'Pengujian contoh udara wajib dilakukan oleh pihak usaha tempat gelanggang renang/tempat permandian alam di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi',
                            'Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala',
                            '1. Menyesuaikan kriteria restoran/rumah makan dan 2. Menyesuaikan kriteria kantin/sentra jajanan/foodtruck, dan sejenis lainnya)',
                            'Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan',
                            'Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya',
                            'Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan Gelanggang Renang dan Tempat Pemandian Alam secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)']; }
                    }, 'REPORT_KOLAM_RENANG_PEMANDIAN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create()
    {
        return view('pages.inspection.renang-pemandian.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian()]);
    }

    public function store(Request $request)
    {
        try {
            // Validasi input komprehensif
            $validatedData = $request->validate([
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'u004' => 'nullable|date',
                'u005' => 'nullable|numeric|min:0',
                'status-operasi' => 'nullable|string',
                'kontak' => 'nullable|numeric',
                'koordinat' => 'required|string|max:255',
                'nama-pemeriksa' => 'nullable|string|max:255',
                'instansi-pemeriksa' => 'nullable|string|max:255',
                'tanggal-penilaian' => 'nullable|date',
                'catatan-lain' => 'nullable|string|max:1000',
                'rencana-tindak-lanjut' => 'nullable|string|max:1000',
            ], [
                'subjek.required' => 'Nama usaha wajib diisi.',
                'subjek.max' => 'Nama usaha maksimal 255 karakter.',
                'pengelola.required' => 'Nama pengelola wajib diisi.',
                'pengelola.max' => 'Nama pengelola maksimal 255 karakter.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.max' => 'Alamat maksimal 500 karakter.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kecamatan.max' => 'Kecamatan maksimal 255 karakter.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'kelurahan.max' => 'Kelurahan maksimal 255 karakter.',
                'u004.date' => 'Format tanggal mulai beroperasi tidak valid.',
                'u005.numeric' => 'Luas bangunan harus berupa angka.',
                'u005.min' => 'Luas bangunan tidak boleh kurang dari 0.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',
                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            Log::info('Arena Renang/Pemandian Alam form submission started', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola')
            ]);

            $data = $request->all();
            
            // Set user_id: 3 for guest, actual user_id for logged users
            $data['user_id'] = Auth::check() ? Auth::id() : 3;
            
            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            $insert = RenangPemandian::create($data);

            if (!$insert) {
                Log::error('Failed to create Arena Renang/Pemandian Alam record', [
                    'user_id' => Auth::check() ? Auth::id() : 3,
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Arena Renang/Pemandian Alam gagal dibuat, silahkan coba lagi.');
            }

            Log::info('Arena Renang/Pemandian Alam record created successfully', [
                'user_id' => Auth::check() ? Auth::id() : 3,
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            return redirect(route('renang-pemandian.show', ['renang_pemandian' => $insert->id]))
                ->with('success', 'Penilaian/inspeksi Arena Renang/Pemandian Alam berhasil dibuat.');

        } catch (ValidationException $e) {
            Log::warning('Arena Renang/Pemandian Alam form validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Arena Renang/Pemandian Alam form submission', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    public function show(RenangPemandian $renangPemandian)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $renangPemandian,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Arena Renang / Pemandian Alam',
            'edit_route' => route('renang-pemandian.edit', ['renang_pemandian' => $renangPemandian['id']]),
            'destroy_route' => route('renang-pemandian.destroy', ['renang_pemandian' => $renangPemandian['id']]),
            'export_route' => route(
                'renang-pemandian.index',
                [
                    'export' => 'pdf',
                    'id' => $renangPemandian['id']],
            )]);
    }

    public function edit(RenangPemandian $renangPemandian)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        return view('pages.inspection.renang-pemandian.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $renangPemandian]);
    }

    public function update(Request $request, RenangPemandian $renangPemandian)
    {
        try {
            // Validasi input komprehensif
            $validatedData = $request->validate([
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'u004' => 'nullable|date',
                'u005' => 'nullable|numeric|min:0',
                'status-operasi' => 'nullable|string',
                'kontak' => 'nullable|numeric',
                'koordinat' => 'required|string|max:255',
                'nama-pemeriksa' => 'nullable|string|max:255',
                'instansi-pemeriksa' => 'nullable|string|max:255',
                'tanggal-penilaian' => 'nullable|date',
                'catatan-lain' => 'nullable|string|max:1000',
                'rencana-tindak-lanjut' => 'nullable|string|max:1000',
            ], [
                'subjek.required' => 'Nama usaha wajib diisi.',
                'subjek.max' => 'Nama usaha maksimal 255 karakter.',
                'pengelola.required' => 'Nama pengelola wajib diisi.',
                'pengelola.max' => 'Nama pengelola maksimal 255 karakter.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.max' => 'Alamat maksimal 500 karakter.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kecamatan.max' => 'Kecamatan maksimal 255 karakter.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'kelurahan.max' => 'Kelurahan maksimal 255 karakter.',
                'u004.date' => 'Format tanggal mulai beroperasi tidak valid.',
                'u005.numeric' => 'Luas bangunan harus berupa angka.',
                'u005.min' => 'Luas bangunan tidak boleh kurang dari 0.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',
                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            Log::info('Arena Renang/Pemandian Alam form update started', [
                'user_id' => Auth::id(),
                'record_id' => $renangPemandian->id,
                'subjek' => $request->input('subjek')
            ]);

            $data = $request->all();
            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            if ($data['action'] == 'duplicate') {
                // Add user_id for duplicate action
                $data['user_id'] = Auth::id();
                
                // Get original data for fallback
                $original = $renangPemandian;
                
                // Create fallback data with original values for required fields
                $fallbackData = array_merge($data, [
                    'kelurahan' => !empty($data['kelurahan']) ? $data['kelurahan'] : ($original ? $original->kelurahan : ''),
                    'kecamatan' => !empty($data['kecamatan']) ? $data['kecamatan'] : ($original ? $original->kecamatan : ''),
                    'kode_kabkot' => !empty($data['kode_kabkot']) ? $data['kode_kabkot'] : ($original ? $original->kode_kabkot : ''),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                // Remove id to create new record
                unset($fallbackData['id']);
                
                $insert = RenangPemandian::create($fallbackData);

                if (!$insert) {
                    Log::error('Failed to duplicate Arena Renang/Pemandian Alam record', [
                        'user_id' => Auth::id(),
                        'original_id' => $renangPemandian->id,
                        'data' => $validatedData
                    ]);
                    return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Arena Renang/Pemandian Alam gagal diduplikasi, silahkan coba lagi.');
                }

                Log::info('Arena Renang/Pemandian Alam record duplicated successfully', [
                    'user_id' => Auth::id(),
                    'original_id' => $renangPemandian->id,
                    'new_id' => $insert->id,
                    'subjek' => $insert->subjek
                ]);

                return redirect(route('renang-pemandian.show', ['renang_pemandian' => $insert->id]))
                    ->with('success', 'Penilaian/inspeksi Arena Renang/Pemandian Alam berhasil diduplikasi.');
            }
            // Remove user_id and action from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            $update = $renangPemandian->update($data);

            if (!$update) {
                Log::error('Failed to update Arena Renang/Pemandian Alam record', [
                    'user_id' => Auth::id(),
                    'record_id' => $renangPemandian->id,
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Form informasi dan penilaian Arena Renang/Pemandian Alam gagal diubah.');
            }

            Log::info('Arena Renang/Pemandian Alam record updated successfully', [
                'user_id' => Auth::id(),
                'record_id' => $renangPemandian->id,
                'subjek' => $renangPemandian->subjek
            ]);

            // Clear application cache to ensure fresh data is loaded
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            return redirect(route('renang-pemandian.show', ['renang_pemandian' => $renangPemandian['id']]))
                ->with('success', 'Form informasi dan penilaian Arena Renang/Pemandian Alam berhasil diubah.');

        } catch (ValidationException $e) {
            Log::warning('Arena Renang/Pemandian Alam form update validation failed', [
                'user_id' => Auth::id(),
                'record_id' => $renangPemandian->id,
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Arena Renang/Pemandian Alam form update', [
                'user_id' => Auth::id(),
                'record_id' => $renangPemandian->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    public function destroy(String $id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk mengakses halaman ini.');
        }

        $renangPemandian = RenangPemandian::where('id', $id)->withTrashed()->first();

        if ($renangPemandian['deleted_at']) {
            $renangPemandian->update([
                'deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $renangPemandian->destroy($renangPemandian['id']);

        if (!$destroy) {
            return redirect(route('renang-pemandian.show', ['renang_pemandian' => $renangPemandian['id']]))->with('error', 'form informasi dan penilaian Arena Renang / Pemandian Alam gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Arena Renang / Pemandian Alam berhasil dihapus');
    }
}