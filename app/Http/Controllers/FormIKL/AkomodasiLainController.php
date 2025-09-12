<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\AkomodasiLain;
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

class AkomodasiLainController extends Controller
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
            Form::input('url', 'Link Upload Dokumen Sertifikat Laik Sehat (SLS) - Opsional', 'dokumen_sls'),
            Form::input('date', 'Tanggal Terbit Dokumen SLS - Opsional', 'sls_issued_date'),
            Form::input('date', 'Tanggal Berakhir Dokumen SLS - Opsional', 'sls_expire_date'),
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
            Form::h(2, "Tempat", "tempat"),

            Form::checkbox("Tempat usaha tidak terletak pada daerah rawan longsor", "t001", 2),
            Form::checkbox("Memiliki ruangan yang berfungsi untuk tempat kerja, kamar tidur, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai", "t002", 2),
            Form::checkbox("Memiliki perlengkapan pendukung untuk usaha akomodasi (furnitur, tempat tidur, meja, dll) yang memadai", "t003", 2),
            Form::checkbox("Memiliki papan nama tempat usaha akomodasi", "t004", 2),
            Form::checkbox("Memiliki sistem pelayanan usaha akomodasi yang terstandar sesuai jenis akomodasi", "t005", 2),
            Form::checkbox("Memiliki pagar pembatas dengan lingkungan", "t006", 2),

            Form::h(2, "Peralatan", "peralatan"),

            Form::checkbox("Peralatan dan perlengkapan yang digunakan untuk pengaturan udara", "p001", 2),
            Form::checkbox("Perlengkapan instalasi penyediaan air", "p002", 2),
            Form::checkbox("Peralatan /Perlengkapan instalasi listrik yang memadai", "p003", 2),
            Form::checkbox("Perlengkapan instalasi pemadam kebakaran yang terstandar", "p004", 2),
            Form::checkbox("Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)", "p005", 2),

            Form::h(2, "Petugas Penjamah makanan dan petugas kebersihan", "petugas-penjamah-makanan-dan-petugas-kebersihan"),

            Form::checkbox("Kondisi sehat dan tidak sedang mengidap penyakit menular", "pk001", 4),
            Form::checkbox("Surat keterangan mengikuti penyuluhan peningkatan kapasitas penjamah makanan", "pk002", 4),
            Form::checkbox("Surat keterangan mengikuti penyuluhan kebersihan usaha akomodasi", "pk003", 4),

            Form::h(2, "Ketersediaan sarana dan bangunan untuk pengunjung", "ketersediaan-sarana-dan-bangunan-untuk-pengunjung"),

            Form::checkbox("bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya", "sb001", 2),
            Form::checkbox("lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air", "sb002", 2),
            Form::checkbox("dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah", "sb003", 2),
            Form::checkbox("atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup", "sb004", 2),
            Form::checkbox("Tersedia Kamar dan perlengkapannya dalam keadaan bersih", "sb005", 2),
            Form::checkbox("Toilet dalam keadaan bersih", "sb006", 2),
            Form::checkbox("Kondisi ruangan lobby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih", "sb007", 2),
            Form::checkbox("Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara disertai dengan ketersediaan SOP pengelolaan sampah", "sb008", 2),
            Form::checkbox("Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota", "sb009", 2),

            Form::h(2, "Ketersediaan air", "ketersediaan-air"),

            Form::checkbox("Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan", "a001", 4),
            Form::checkbox("Pengujian contoh air minum wajib dilakukan oleh pihak penyedia akomodasi di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi.", "a002", 2),
            Form::checkbox("Air tersedia sepanjang waktu dalam kondisi cukup", "a003", 3),

            Form::h(2, "Kondisi udara dan kualitasnya", "kondisi-udara-dan-kualitasnya"),

            Form::checkbox("Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya", "uk001", 2),
            Form::checkbox("Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman", "uk002", 3),
            Form::checkbox("Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan", "uk003", 3),
            Form::checkbox("Pengujian contoh udara wajib dilakukan oleh pihak usaha akomodasi di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi.", "uk004", 2),
            Form::checkbox("Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala", "uk005", 2),

            Form::h(2, "Kondisi Tempat pengelolaan makanan dan minuman dan produknya", "kondisi-tempat-pengelolaan-makanan-dan-minuman-dan-produknya"),

            Form::checkbox("Menyesuaikan kriteria restoran/jasa boga", "k001", 12),

            Form::h(2, "Pengelolaan linen", "pengelolaan-linen"),

            Form::checkbox("Linen yang disediakan dalam keadaan bersih", "pl001", 4),
            Form::checkbox("Tersedia SOP pengelolaan linen dan kegiatannya.", "pl002", 2),

            Form::h(2, "Pengendalian vektor dan binatang pembawa penyakit", "pengendalian-vektor-dan-binatang-pembawa-penyakit"),

            Form::checkbox("Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan", "v001", 4),
            Form::checkbox("Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya", "v002", 2),

            Form::h(2, "Penilaian sendiri/mandiri (Self Assesment)", "penilaian-sendiri-mandiri"),

            Form::checkbox("Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)", "s001", 3)];
    }

    protected function formPenilaianName()
    {
        return [
            't001',
            't002',
            't003',
            't004',
            't005',
            't006',

            'p001',
            'p002',
            'p003',
            'p004',
            'p005',

            'pk001',
            'pk002',
            'pk003',

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

            'pl001',
            'pl002',

            'v001',
            'v002',

            's001'];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = AkomodasiLain::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Akomodasi Lainnya',
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
                    'pemeriksa' => $item['nama-pemeriksa']])->download('BAIKL_AKOMODASI_LAIN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(
                    new class implements FromCollection, WithHeadings
                    {
                        public function collection()
                        {
                            return AkomodasiLain::withTrashed()->get()->map(function ($item) {
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

                                "Tempat usaha tidak terletak pada daerah rawan longsor",
                                "Memiliki ruangan yang berfungsi untuk tempat kerja, kamar tidur, ruang tunggu, dan ruang untuk penyimpanan perlengkapan pendukung yang memadai",
                                "Memiliki perlengkapan pendukung untuk usaha akomodasi (furnitur, tempat tidur, meja, dll) yang memadai",
                                "Memiliki papan nama tempat usaha akomodasi",
                                "Memiliki sistem pelayanan usaha akomodasi yang terstandar sesuai jenis akomodasi",
                                "Memiliki pagar pembatas dengan lingkungan",
                                "Peralatan dan perlengkapan yang digunakan untuk pengaturan udara",
                                "Perlengkapan instalasi penyediaan air",
                                "Peralatan /Perlengkapan instalasi listrik yang memadai",
                                "Perlengkapan instalasi pemadam kebakaran yang terstandar",
                                "Perlengkapan pertolongan pada kecelakaan dan kedaruratan (minimal oksigen set dan tandu)",
                                "Kondisi sehat dan tidak sedang mengidap penyakit menular",
                                "Surat keterangan mengikuti penyuluhan peningkatan kapasitas penjamah makanan",
                                "Surat keterangan mengikuti penyuluhan kebersihan usaha akomodasi",
                                "bangunan kuat, aman, mudah dibersihkan, dan mudah pemeliharaannya",
                                "lantai bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta kemiringan cukup landai untuk memudahkan pembersihan dan tidak terjadi genangan air",
                                "dinding bangunan kedap air, permukaan rata, halus, tidak licin, tidak retak, tidak menyerap debu, dan mudah dibersihkan, serta warna yang terang dan cerah",
                                "atap dan langit-langit bangunan harus kuat, mudah dibersihkan, tidak menyerap debu, permukaan rata, dan mempunyai ketinggian yang memungkinkan adanya pertukaran udara yang cukup",
                                "Tersedia Kamar dan perlengkapannya dalam keadaan bersih",
                                "Toilet dalam keadaan bersih",
                                "Kondisi ruangan lobby, tangga (jika ada), lift (jika ada), dan ruangan pendukung lain dalam keadaan bersih",
                                "Tersedia tempat sampah yang cukup di setiap ruangan dan tempat sampah sementara disertai dengan ketersediaan SOP pengelolaan sampah",
                                "Tersedia tempat pengolahan limbah yang memadai sebelum air limbah tersebut dibuang ke saluran pembuangan kota",
                                "Wajib memenuhi persyaratan kualitas air minum minimal parameter fisik dan E.Coli sesuai yang ditetapkan dalam peraturan Menteri Kesehatan",
                                "Pengujian contoh air minum wajib dilakukan oleh pihak penyedia akomodasi di Laboratorium Pemeriksaan Kualitas Air yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi.",
                                "Air tersedia sepanjang waktu dalam kondisi cukup",
                                "Pencahayaan cukup memadai jika melakukan aktivitas di bawahnya",
                                "Kondisi kualitas udara dalam setiap ruangan bersih dan nyaman",
                                "Wajib memenuhi persyaratan kualitas udara minimal parameter debu PM2.5 sesuai yang ditetapkan dalam peraturan menteri Kesehatan",
                                "Pengujian contoh udara wajib dilakukan oleh pihak usaha akomodasi di Laboratorium Pemeriksaan Kualitas udara yang ditunjuk oleh Pemerintah Kabupaten/kota atau yang terakreditasi.",
                                "Dilakukan perawatan terhadap sarana pengaturan udara seperti AC dan sejenisnya secara berkala",
                                "Menyesuaikan kriteria restoran/jasa boga",
                                "Linen yang disediakan dalam keadaan bersih",
                                "Tersedia SOP pengelolaan linen dan kegiatannya.",
                                "Wajib memenuhi standar dan persyaratan vektor dan binatang pembawa penyakit sesuai peraturan menteri kesehatan",
                                "Tersedia SOP pengendalian vektor dan binatang pembawa penyakit dan kegiatannya",
                                "Setiap pengelola/penanggung jawab wajib melakukan pengawasan terhadap pemenuhan standar baku mutu kesehatan lingkungan dan persyaratan kesehatan secara terus menerus dan dilaporkan satu kali dalam setahun dalam bentuk penilaian sendiri/mandiri (self assesment)",
                                "Link Upload Dokumen Sertifikat Laik Sehat (SLS)",
                                "Tanggal Terbit Dokumen SLS",
                                "Tanggal Berakhir Dokumen SLS"
                            ];
                        }
                    },
                    'REPORT_AKOMODASI_LAIN_' . Carbon::now()->format('Ymd') . '.xlsx'
                );
            default:
                abort(404);
        }
    }

    public function create()
    {
        return view('pages.inspection.akomodasi-lain.create', [
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
                'dokumen_sls' => 'nullable|url',
                'sls_issued_date' => 'nullable|date',
                'sls_expire_date' => 'nullable|date|after_or_equal:sls_issued_date',
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
                'dokumen_sls.url' => 'Format URL dokumen SLS tidak valid.',
                'sls_issued_date.date' => 'Format tanggal terbit SLS tidak valid.',
                'sls_expire_date.date' => 'Format tanggal berakhir SLS tidak valid.',
                'sls_expire_date.after_or_equal' => 'Tanggal berakhir SLS harus setelah atau sama dengan tanggal terbit.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',
                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            Log::info('Akomodasi Lain form submission started', [
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
            
            // Auto-calculate expire date from issued date + 3 years
            if (!empty($data['sls_issued_date'])) {
                $issuedDate = \Carbon\Carbon::parse($data['sls_issued_date']);
                $data['sls_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
            }

            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            $insert = AkomodasiLain::create($data);

            if (!$insert) {
                Log::error('Failed to create Akomodasi Lain record', [
                    'user_id' => Auth::id(),
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Akomodasi Lainnya gagal dibuat, silahkan coba lagi.');
            }

            Log::info('Akomodasi Lain record created successfully', [
                'user_id' => Auth::id(),
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            return redirect(route('akomodasi-lain.show', ['akomodasi_lain' => $insert->id]))
                ->with('success', 'Penilaian/inspeksi Akomodasi Lainnya berhasil dibuat.');

        } catch (ValidationException $e) {
            Log::warning('Akomodasi Lain form validation failed', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Akomodasi Lain form submission', [
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

    public function show(AkomodasiLain $akomodasiLain)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $akomodasiLain,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Akomodasi Lainnya',
            'edit_route' => route('akomodasi-lain.edit', ['akomodasi_lain' => $akomodasiLain['id']]),
            'destroy_route' => route('akomodasi-lain.destroy', ['akomodasi_lain' => $akomodasiLain['id']]),
            'export_route' => route(
                'akomodasi-lain.index',
                [
                    'export' => 'pdf',
                    'id' => $akomodasiLain['id']],
            )]);
    }

    public function edit(AkomodasiLain $akomodasiLain)
    {
        return view('pages.inspection.akomodasi-lain.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $akomodasiLain]);
    }

    public function update(Request $request, AkomodasiLain $akomodasiLain)
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
                'dokumen_sls' => 'nullable|url',
                'sls_issued_date' => 'nullable|date',
                'sls_expire_date' => 'nullable|date|after_or_equal:sls_issued_date',
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
                'dokumen_sls.url' => 'Format URL dokumen SLS tidak valid.',
                'sls_issued_date.date' => 'Format tanggal terbit SLS tidak valid.',
                'sls_expire_date.date' => 'Format tanggal berakhir SLS tidak valid.',
                'sls_expire_date.after_or_equal' => 'Tanggal berakhir SLS harus setelah atau sama dengan tanggal terbit.',
                'kontak.numeric' => 'Kontak pengelola harus berupa angka.',
                'koordinat.required' => 'Titik GPS wajib diisi.',
                'koordinat.max' => 'Titik GPS maksimal 255 karakter.',
                'nama-pemeriksa.max' => 'Nama pemeriksa maksimal 255 karakter.',
                'instansi-pemeriksa.max' => 'Instansi pemeriksa maksimal 255 karakter.',
                'tanggal-penilaian.date' => 'Format tanggal penilaian tidak valid.',
                'catatan-lain.max' => 'Catatan lain maksimal 1000 karakter.',
                'rencana-tindak-lanjut.max' => 'Rencana tindak lanjut maksimal 1000 karakter.',
            ]);

            Log::info('Akomodasi Lain update process started', [
                'user_id' => Auth::id(),
                'record_id' => $akomodasiLain->id,
                'subjek' => $request->input('subjek')
            ]);

            $data = $request->all();
            
            // Handle instansi-lainnya logic
            if ($request->has('instansi-lainnya') && !empty($request->input('instansi-lainnya'))) {
                $data['instansi-pemeriksa'] = $request->input('instansi-lainnya');
            }
            
            // Auto-calculate expire date from issued date + 3 years
            if (!empty($data['sls_issued_date'])) {
                $issuedDate = \Carbon\Carbon::parse($data['sls_issued_date']);
                $data['sls_expire_date'] = $issuedDate->addYears(3)->format('Y-m-d');
            }

            $data['skor'] = (int) ((int) (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column), 0) / 100 * 100));

            if ($data['action'] == 'duplicate') {
                // Add user_id for duplicate action
                $data['user_id'] = Auth::id();
                
                $insert = AkomodasiLain::create($data);

                if (!$insert) {
                    Log::error('Failed to duplicate Akomodasi Lain record', [
                        'user_id' => Auth::id(),
                        'original_record_id' => $akomodasiLain->id,
                        'data' => $validatedData
                    ]);
                    return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Akomodasi Lainnya gagal diduplikasi, silahkan coba lagi.');
                }

                Log::info('Akomodasi Lain record duplicated successfully', [
                    'user_id' => Auth::id(),
                    'original_record_id' => $akomodasiLain->id,
                    'new_record_id' => $insert->id,
                    'subjek' => $insert->subjek
                ]);

                return redirect(route('akomodasi-lain.show', ['akomodasi_lain' => $insert->id]))
                    ->with('success', 'Penilaian/inspeksi Akomodasi Lainnya berhasil diduplikasi.');
            }

            // Check for duplicate
            $duplicate = AkomodasiLain::where('subjek', $data['subjek'])
                ->where('alamat', $data['alamat'])
                ->where('id', '!=', $akomodasiLain->id)
                ->first();

            if ($duplicate) {
                Log::warning('Duplicate Akomodasi Lain record found during update', [
                    'user_id' => Auth::id(),
                    'current_record_id' => $akomodasiLain->id,
                    'duplicate_record_id' => $duplicate->id,
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
            
            $update = $akomodasiLain->update($data);

            if (!$update) {
                Log::error('Failed to update Akomodasi Lain record', [
                    'user_id' => Auth::id(),
                    'record_id' => $akomodasiLain->id,
                    'data' => $validatedData
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Form informasi dan penilaian Akomodasi Lainnya gagal diubah, silahkan coba lagi.');
            }

            // Clear application cache to ensure fresh data is loaded
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Log::info('Akomodasi Lain record updated successfully', [
                'user_id' => Auth::id(),
                'record_id' => $akomodasiLain->id,
                'subjek' => $akomodasiLain->subjek
            ]);

            return redirect(route('akomodasi-lain.show', ['akomodasi_lain' => $akomodasiLain->id]))
                ->with('success', 'Form informasi dan penilaian Akomodasi Lainnya berhasil diubah.');

        } catch (ValidationException $e) {
            Log::warning('Akomodasi Lain update validation failed', [
                'user_id' => Auth::id(),
                'record_id' => $akomodasiLain->id,
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            Log::error('Unexpected error during Akomodasi Lain update', [
                'user_id' => Auth::id(),
                'record_id' => $akomodasiLain->id,
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
        $akomodasiLain = AkomodasiLain::where('id', $id)->withTrashed()->first();

        if ($akomodasiLain['deleted_at']) {
            $akomodasiLain->update([
                'deleted_at' => null]);

            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $akomodasiLain->destroy($akomodasiLain['id']);

        if (!$destroy) {
            return redirect(route('akomodasi-lain.show', ['akomodasi_lain' => $akomodasiLain['id']]))->with('error', 'form informasi dan penilaian Akomodasi Lainnya gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Akomodasi Lainnya berhasil dihapus');
    }
}