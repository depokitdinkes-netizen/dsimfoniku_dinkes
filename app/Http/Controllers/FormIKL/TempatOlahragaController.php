<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\TempatOlahraga;
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

class TempatOlahragaController extends Controller
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
            Form::input('number', 'Luas Bangunan (m²)', 'u005'),
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
            Form::checkbox('Memiliki ruangan yang berfungsi untuk tempat kerja, ruang tempat olahraga, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai', 't002', 2),
            Form::checkbox('Memiliki perlengkapan pendukung untuk usaha tempat olahraga (sesuai jenis usaha) yang memadai', 't003', 2),
            Form::checkbox('Memiliki papan nama tempat usaha tempat olahraga', 't004', 2),
            Form::checkbox('memiliki sistem pelayanan usaha tempat olahraga yang terstandar', 't005', 2),

            Form::h(2, 'Peralatan', 'peralatan'),

            Form::checkbox('Peralatan dan perlengkapan yang digunakan untuk pengaturan udara.', 'p001', 2),
            Form::checkbox('Perlengkapan instalasi penyediaan air', 'p002', 2),
            Form::checkbox('Peralatan /Perlengkapan instalasi listrik yang memadai', 'p003', 2),
            Form::checkbox('Perlengkapan instalasi pemadam kebakaran yang terstandar', 'p004', 2),
            Form::checkbox('Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)', 'p005', 2),

            Form::h(2, 'Petugas Penjamah makanan dan petugas kebersihan', 'petugas-penjamah-makanan-dan-petugas-kebersihan'),

            Form::checkbox('Penjamah makanan memiliki surat keterangan mengikuti penyuluhan atau pelatihan higiene sanitasi makanan', 'pk001', 5),
            Form::checkbox('Petugas kebersihan memiliki surat keterangan mengikuti penyuluhan/sertifikat pelatihan petugas kebersihan (cleaning service)', 'pk002', 5),
            Form::checkbox('Menggunakan pakaian kerja yang bersih dan Rapi', 'pk003', 2),
            Form::checkbox('Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun', 'pk004', 3),

            Form::h(2, 'Ketersediaan sarana dan bangunan untuk pengunjung', 'ketersediaan-sarana-dan-bangunan-untuk-pengunjung'),

            Form::checkbox('bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya', 'sb001', 2),
            Form::checkbox('lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air', 'sb002', 2),
            Form::checkbox('dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah', 'sb003', 2),
            Form::checkbox('atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup', 'sb004', 2),
            Form::checkbox('Tersedia ruang tempat olahraga dan perlengkapannya dalam keadaan bersih', 'sb005', 2),
            Form::checkbox('Toilet dalam keadaan bersih', 'sb006', 2),
            Form::checkbox('Kondisi ruangan loby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih', 'sb007', 2),
            Form::checkbox('Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara disertai dengan ketersediaan SOP pengelolaan sampah', 'sb008', 3),
            Form::checkbox('Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota', 'sb009', 2),

            Form::h(2, 'Ketersediaan air', 'ketersediaan-air'),

            Form::checkbox('Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan', 'a001', 4),
            Form::checkbox('Pengujian contoh air minum wajib dilakukan oleh pihak usaha tempat olahraga di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi', 'a002', 2),
            Form::checkbox('Air tersedia sepanjang waktu dalam kondisi cukup', 'a003', 3),

            Form::h(2, 'Kondisi udara dan kualitasnya', 'kondisi-udara-dan-kualitasnya'),

            Form::checkbox('Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya', 'uk001', 2),
            Form::checkbox('Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman', 'uk002', 5),
            Form::checkbox('Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan', 'uk003', 5),
            Form::checkbox('Pengujian contoh udara wajib dilakukan oleh pihak usaha tempat olahraga di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah kabupaten/kota atau yang terakreditasi', 'uk004', 2),
            Form::checkbox('Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala', 'uk005', 3),

            Form::h(2, 'Kondisi Tempat pengelolaan makanan dan minuman dan produknya', 'kondisi-tempat-pengelolaan-makanan-dan-minuman-dan-produknya'),

            Form::checkbox('a. Menyesuaikan kriteria restoran/rumah makan dan b. Menyesuaikan kriteria kantin/sentra jajanan/foodtruck, dan sejenis lainnya)', 'k001', 12),

            Form::h(2, 'Pengendalian vektor dan binatang pembawa penyakit', 'pengendalian-vektor-dan-binatang-pembawa-penyakit'),

            Form::checkbox('Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan', 'v001', 3),
            Form::checkbox('Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya', 'v002', 2),

            Form::h(2, 'Penilaian sendiri/mandiri (Self Assesment)', 'penilaian-mandiri-self-assesment'),

            Form::checkbox('Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan Tempat Olahraga secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)', 's001', 3)];
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

            'sb001',
            'sb002',
            'sb003',
            'sb004',
            'sb005',
            'sb006',
            'sb007',
            'sb008',
            'sb009',

            'a001',
            'a002',
            'a003',

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
                $item = TempatOlahraga::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Gelanggang Olahraga',
                    'tanggal' => Carbon::parse($item["created_at"])->format('d'),
                    'bulan' => Carbon::parse($item["created_at"])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item["created_at"])->format('Y'),
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
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_GELANGGANG_OLAHRAGA_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
                break;
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return TempatOlahraga::withTrashed()->get()->map(function ($item) {
                            // Convert all null values to '0' for Excel export
                            $array = $item->toArray();
                            foreach ($array as $key => $value) {
                                if (is_null($value) || $value === '' || $value === 0) {
                                    $array[$key] = '0';
                                }
                            }
                            return $array;
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
                            'Memiliki ruangan yang berfungsi untuk tempat kerja, ruang tempat olahraga, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai',
                            'Memiliki perlengkapan pendukung untuk usaha tempat olahraga (sesuai jenis usaha) yang memadai',
                            'Memiliki papan nama tempat usaha tempat olahraga',
                            'memiliki sistem pelayanan usaha tempat olahraga yang terstandar',
                            'Peralatan dan perlengkapan yang digunakan untuk pengaturan udara.',
                            'Perlengkapan instalasi penyediaan air',
                            'Peralatan /Perlengkapan instalasi listrik yang memadai',
                            'Perlengkapan instalasi pemadam kebakaran yang terstandar',
                            'Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)',
                            'Penjamah makanan memiliki surat keterangan mengikuti penyuluhan atau pelatihan higiene sanitasi makanan',
                            'Petugas kebersihan memiliki surat keterangan mengikuti penyuluhan/sertifikat pelatihan petugas kebersihan (cleaning service)',
                            'Menggunakan pakaian kerja yang bersih dan Rapi',
                            'Melakukan pemeriksaan kesehatan secara berkala minimal 1 (satu) kali dalam setahun',
                            'bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya',
                            'lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air',
                            'dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah',
                            'atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup',
                            'Tersedia ruang tempat olahraga dan perlengkapannya dalam keadaan bersih',
                            'Toilet dalam keadaan bersih',
                            'Kondisi ruangan loby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih',
                            'Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara disertai dengan ketersediaan SOP pengelolaan sampah',
                            'Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota',
                            'Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan',
                            'Pengujian contoh air minum wajib dilakukan oleh pihak usaha tempat olahraga di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi',
                            'Air tersedia sepanjang waktu dalam kondisi cukup',
                            'Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya',
                            'Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman',
                            'Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan',
                            'Pengujian contoh udara wajib dilakukan oleh pihak usaha tempat olahraga di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah kabupaten/kota atau yang terakreditasi',
                            'Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala',
                            'a. Menyesuaikan kriteria restoran/rumah makan dan b. Menyesuaikan kriteria kantin/sentra jajanan/foodtruck, dan sejenis lainnya)',
                            'Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan',
                            'Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya',
                            'Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan Tempat Olahraga secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)'];     }
                }, 'REPORT_GELANGGANG_OLAHRAGA_' . Carbon::now()->format('Ymd') . '.xlsx');
                break;
            default:
                abort(404);
        }
    }
    public function create()
    {
        return view('pages.inspection.tempat-olahraga.create', [
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
                'u005.min' => 'Luas bangunan tidak boleh negatif.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',

                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            Log::info('Gelanggang Olahraga form submission started', [
                'user_id' => Auth::id(),
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola')
            ]);

            $data = $request->all();

            // Tambahkan user_id dari user yang sedang login
            $data['user_id'] = Auth::id();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            $insert = TempatOlahraga::create($data);

            if (!$insert) {
                Log::error('Failed to create Gelanggang Olahraga record', [
                    'user_id' => Auth::id(),
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Gelanggang Olahraga gagal dibuat, silahkan coba lagi.');
            }

            Log::info('Gelanggang Olahraga record created successfully', [
                'user_id' => Auth::id(),
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            return redirect(route('tempat-olahraga.show', ['tempat_olahraga' => $insert->id]))
                ->with('success', 'Penilaian/inspeksi Gelanggang Olahraga berhasil dibuat.');

        } catch (ValidationException $e) {
            Log::warning('Gelanggang Olahraga form validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Gelanggang Olahraga form submission', [
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

    public function show(TempatOlahraga $tempatOlahraga)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $tempatOlahraga,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Gelanggang Olahraga',
            'edit_route' => route('tempat-olahraga.edit', ['tempat_olahraga' => $tempatOlahraga['id']]),
            'destroy_route' => route('tempat-olahraga.destroy', ['tempat_olahraga' => $tempatOlahraga['id']]),
            'export_route' => route(
                'tempat-olahraga.index',
                [
                    'export' => 'pdf',
                    'id' => $tempatOlahraga['id']],
            )]);
    }

    public function edit(TempatOlahraga $tempatOlahraga)
    {
        return view('pages.inspection.tempat-olahraga.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $tempatOlahraga]);
    }

    public function update(Request $request, TempatOlahraga $tempatOlahraga)
    {
        try {
            Log::info('Gelanggang Olahraga update process started', [
                'user_id' => Auth::id(),
                'record_id' => $tempatOlahraga->id
            ]);

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
                'u005.min' => 'Luas bangunan tidak boleh negatif.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',

                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            $data = $request->all();

            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }

            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            if ($data['action'] == 'duplicate') {
                // Add user_id for duplicate action
                $data['user_id'] = Auth::id();
                
                // Check for duplicates when creating new record (exclude the original record being duplicated)
                $duplicate = TempatOlahraga::where('subjek', $data['subjek'])
                    ->where('alamat', $data['alamat'])
                    ->where('id', '!=', $tempatOlahraga->id)
                    ->first();

                if ($duplicate) {
                    Log::warning('Duplicate Gelanggang Olahraga found during duplication', [
                        'user_id' => Auth::id(),
                        'original_id' => $tempatOlahraga->id,
                        'duplicate_id' => $duplicate->id,
                        'subjek' => $data['subjek'],
                        'alamat' => $data['alamat']
                    ]);
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Data dengan nama usaha dan alamat yang sama sudah ada.');
                }

                $insert = TempatOlahraga::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Gelanggang Olahraga record', [
                        'user_id' => Auth::id(),
                        'original_id' => $tempatOlahraga->id,
                        'data' => $validatedData
                    ]);
                    return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Gelanggang Olahraga gagal diduplikasi, silahkan coba lagi.');
                }

                Log::info('Gelanggang Olahraga record duplicated successfully', [
                    'user_id' => Auth::id(),
                    'original_id' => $tempatOlahraga->id,
                    'new_id' => $insert->id,
                    'subjek' => $insert->subjek
                ]);

                return redirect(route('tempat-olahraga.show', ['tempat_olahraga' => $insert->id]))
                    ->with('success', 'Penilaian/inspeksi Gelanggang Olahraga berhasil diduplikasi.');
            }

            // Check for duplicates when updating existing record
            $duplicate = TempatOlahraga::where('subjek', $data['subjek'])
                ->where('alamat', $data['alamat'])
                ->where('id', '!=', $tempatOlahraga->id)
                ->first();

            if ($duplicate) {
                Log::warning('Duplicate Gelanggang Olahraga found during update', [
                    'user_id' => Auth::id(),
                    'record_id' => $tempatOlahraga->id,
                    'duplicate_id' => $duplicate->id,
                    'subjek' => $data['subjek'],
                    'alamat' => $data['alamat']
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data dengan nama usaha dan alamat yang sama sudah ada.');
            }

            // Remove user_id and action from update data to preserve original user
            unset($data['user_id']);
            unset($data['action']);
            
            $update = $tempatOlahraga->update($data);

            if (!$update) {
                Log::error('Failed to update Gelanggang Olahraga record', [
                    'user_id' => Auth::id(),
                    'record_id' => $tempatOlahraga->id,
                    'data' => $validatedData
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Form informasi dan penilaian Gelanggang Olahraga gagal diubah. Silahkan coba lagi.');
            }

            Log::info('Gelanggang Olahraga record updated successfully', [
                'user_id' => Auth::id(),
                'record_id' => $tempatOlahraga->id,
                'subjek' => $tempatOlahraga->subjek
            ]);

            // Clear application cache to ensure fresh data is loaded
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            return redirect(route('tempat-olahraga.show', ['tempat_olahraga' => $tempatOlahraga['id']]))
                ->with('success', 'Form informasi dan penilaian Gelanggang Olahraga berhasil diubah.');

        } catch (ValidationException $e) {
            Log::warning('Gelanggang Olahraga update validation failed', [
                'user_id' => Auth::id(),
                'record_id' => $tempatOlahraga->id,
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Gelanggang Olahraga update', [
                'user_id' => Auth::id(),
                'record_id' => $tempatOlahraga->id,
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
        $tempatOlahraga = TempatOlahraga::where('id', $id)->withTrashed()->first();

        if ($tempatOlahraga['deleted_at']) {
            $tempatOlahraga->update([
                'deleted_at' => null]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $tempatOlahraga->destroy($tempatOlahraga['id']);

        if (!$destroy) {
            return redirect(route('tempat-olahraga.show', ['tempat_olahraga' => $tempatOlahraga['id']]))->with('error', 'form informasi dan penilaian Gelanggang Olahraga gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaiannggang Olahraga berhasil dihapus');
    }
}