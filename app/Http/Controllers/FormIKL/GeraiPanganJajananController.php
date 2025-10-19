<?php

namespace App\Http\Controllers\FormIKL;

use App\Http\Controllers\Controller;
use App\Models\FormIKL\GeraiPanganJajanan;
use App\Utils\Export;
use App\Utils\Form;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GeraiPanganJajananController extends Controller
{
    protected function informasiUmum()
    {
        return [
            Form::input('text', 'Nama Gerai Pangan Jajanan', 'subjek'),
            Form::input('text', 'Alamat', 'alamat'),
            Form::input('text', 'Kecamatan', 'kecamatan'),
            Form::input('text', 'Kelurahan', 'kelurahan'),
            Form::input('text', 'Nama Pengelola/Pemilik/Penanggung Jawab', 'pengelola'),
            Form::input('number', 'Jumlah Penjamah Pangan', 'u004'),
            Form::input('number', 'Jumlah yang sudah mengikuti pelatihan dan mendapatkan sertifikat', 'u005'),
            Form::input('number', 'Nomor Induk Berusaha (Opsional)', 'u006'),
            Form::input('text', 'Lokasi Dapur Gerai Pangan', 'u008'),
            Form::input('text', 'Nama Pemeriksa', 'nama-pemeriksa'),
            Form::input('select', 'Instansi Pemeriksa', 'instansi-pemeriksa'),
            Form::input('date', 'Tanggal Penilaian', 'tanggal-penilaian'),
            Form::input('url', 'Link Dokumen SLHS (Opsional)', 'dokumen_slhs'),
            Form::input('date', 'Tanggal Terbit SLHS (Opsional)', 'slhs_issued_date'),
            Form::input('date', 'Tanggal Berakhir SLHS (Opsional)', 'slhs_expire_date'),
            Form::input('number', 'Kontak Pengelola', 'kontak'),
            Form::input('select', 'Apakah masih beroperasi?', 'status-operasi'),
            Form::input('text', 'Titik GPS', 'koordinat'),
        ];
    }

    protected function formPenilaian()
    {
        return [
            Form::h(2, 'Inspeksi Area Lokasi Sekitar Gerai Jajanan'),

            Form::select('Lokasi bebas banjir', 'ij001', 3),
            Form::select('Lokasi bebas dari pencemaran bau/asap/debu/kotoran', 'ij002'),
            Form::select('Lokasi bebas dari sumber vektor dan binatang pembawa penyakit', 'ij003'),
            Form::select('Memiliki tenda/atap pelindung jika beroperasi pada bangunan semi permanen', 'ij004'),

            Form::h(4, 'Jika menggunakan tenda'),
            Form::select('Bahan kuat dan mudah dibersihkan', 'ij005'),
            Form::select('Kedap air', 'ij006'),

            Form::h(2, 'Inspeksi Tempat Penjualan Pangan'),

            Form::h(3, 'A Personel', 'personel'),

            Form::h(4, 'Penjual pangan'),

            Form::select('Sehat dan bebas dari penyakit menular', 'p001', 3),
            Form::select('Berkuku pendek, bersih dan tidak memakai pewarna kuku', 'p002', 3),
            Form::select('Selalu mencuci tangan dengan sabun dan air mengalir secara berkala sebelum menanggani pangan atau menggunakan hand sanitizer secara teratur', 'p003', 3),
            Form::select('Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)', 'p004', 3),
            Form::select('Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih', 'p005', 3),
            Form::select('Melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun', 'p006', 2),
            Form::select('Sudah mendapatkan penyuluhan keamanan pangan siap saji', 'p007', 3),
            Form::select('Personil yang melayani pembayaran tidak menyentuh pangan yang terbuka secara langsung sebelum mencuci tangan', 'p008', 3),
            Form::select('Tidak merokok pada saat menangani pangan', 'p009', 3),
            Form::select('Tidak menggaruk-garuk anggota badan dan langsung menangani pangan tanpa terlebih dahulu mencuci tangan atau menggunakan hand sanitizer', 'p010', 3),

            Form::h(3, 'B Penyimpanan dan Pengemasan Pangan Matang', 'penyimpanan-pengemasan'),

            Form::select('Meja atau rak penyimpanan pangan bersih dan kuat', 'ppm001'),
            Form::select('Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)', 'ppm002', 3),
            Form::select('Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan', 'ppm003', 2),
            Form::select('Pangan matang yang mudah rusak harus sudah dikonsumsi 4 (empat) jam setelah matang', 'ppm004', 3),
            Form::select('Pangan matang panas dijaga pada suhu > 60°C', 'ppm005', 3),
            Form::select('Pangan matang dingin dijaga pada suhu < 5°C', 'ppm006', 3),
            Form::select('Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)', 'ppm007', 3),
            Form::select('Jika menggunakan es batu, maka es batu dibuat dari air matang/sudah dimasak atau berasal dari sumber yang terpercaya', 'ppm008', 3),
            Form::select('Tidak terdapat pangan yang busuk/basi', 'ppm009', 3),
            Form::select('Air untuk minum memenuhi standar kualitas air minum/air yang sudah diolah/dimasak', 'ppm010', 3),

            Form::h(4, 'Tempat yang digunakan untuk menyajikan pangan'),

            Form::select('Piring bersih dan tara pangan/food grade', 'ppm011', 3),
            Form::select('Gelas bersih dan tara pangan/food grade', 'ppm012', 3),
            Form::select('Sendok bersih dan tara pangan/food grade', 'ppm013', 3),
            Form::select('Sedotan bersih dan tara pangan/food grade', 'ppm014', 3),
            Form::select('Tidak ada vektor dan binatang pembawa penyakit pada area penyajian pangan', 'ppm015', 3),
            Form::select('Tersedia tempat sampah (dapat menggunakan tempat sampah khusus atau plastik untuk menampung sampah sementara)', 'ppm016', 2),
            Form::select('Lap/kain majun yang digunakan untuk mengelap permukaan peralatan atau meja bersih dan rutin diganti', 'ppm017', 2),
        ];
    }

    protected function formPenilaianName()
    {
        return [
            'ij001',
            'ij002',
            'ij003',
            'ij004',
            'ij005',
            'ij006',

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

            'ppm001',
            'ppm002',
            'ppm003',
            'ppm004',
            'ppm005',
            'ppm006',
            'ppm007',
            'ppm008',
            'ppm009',
            'ppm010',
            'ppm011',
            'ppm012',
            'ppm013',
            'ppm014',
            'ppm015',
            'ppm016',
            'ppm017',
        ];
    }

    public function index(Request $request)
    {
        switch ($request->export) {
            case 'pdf':
                $item = GeraiPanganJajanan::find($request->id);

                if (!$item) {
                    return abort(404);
                }

                Carbon::setLocale('id');

                return Pdf::loadView('pdf', [
                    'form' => 'Gerai Pangan Jajanan',
                    'tanggal' => Carbon::parse($item['tanggal-penilaian'])->format('d'),
                    'bulan' => Carbon::parse($item['tanggal-penilaian'])->translatedFormat('F'),
                    'tahun' => Carbon::parse($item['tanggal-penilaian'])->format('Y'),
                    'informasi' => [
                        ['Nama Gerai Pangan Jajanan', $item['subjek']],
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
                ])->download('BAIKL_GERAI_PANGAN_JAJANAN_' . str_pad($item['id'], 5, '0', STR_PAD_LEFT) . '.pdf');
            case 'excel':
                return Excel::download(new class implements FromCollection, WithHeadings {
                    public function collection()
                    {
                        return GeraiPanganJajanan::withTrashed()->get()->map(function ($item) {
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
                            'Nomor Induk Berusaha',
                            'Lokasi Dapur Gerai Pangan',

                            'Lokasi bebas banjir',
                            'Lokasi bebas dari pencemaran bau/asap/debu/kotoran',
                            'Lokasi bebas dari sumber vektor dan binatang pembawa penyakit',
                            'Memiliki tenda/atap pelindung jika beroperasi pada bangunan semi permanen',
                            'Bahan kuat dan mudah dibersihkan',
                            'Kedap air',
                            'Sehat dan bebas dari penyakit menular',
                            'Berkuku pendek, bersih dan tidak memakai pewarna kuku',
                            'Selalu mencuci tangan dengan sabun dan air mengalir secara berkala sebelum menanggani pangan atau menggunakan hand sanitizer secara teratur',
                            'Mengambil pangan matang menggunakan sarung tangan atau alat bantu (contoh sendok, penjapit makanan)',
                            'Jika terluka maka luka ditutup dengan perban/sejenisnya dan ditutup penutup tahan air dan kondisi bersih',
                            'Melakukan pemeriksaan kesehatan minimal 1 (satu) kali dalam setahun',
                            'Sudah mendapatkan penyuluhan keamanan pangan siap saji',
                            'Personil yang melayani pembayaran tidak menyentuh pangan yang terbuka secara langsung sebelum mencuci tangan',
                            'Tidak merokok pada saat menangani pangan',
                            'Tidak menggaruk-garuk anggota badan dan langsung menangani pangan tanpa terlebih dahulu mencuci tangan atau menggunakan hand sanitizer',
                            'Meja atau rak penyimpanan pangan bersih dan kuat',
                            'Pengemasan pangan matang harus dalam wadah tertutup dan tara pangan (food grade)',
                            'Wadah penyimpanan pangan matang terpisah untuk setiap jenis pangan',
                            'Pangan matang yang mudah rusak harus sudah dikonsumsi 4 (empat) jam setelah matang',
                            'Pangan matang panas dijaga pada suhu > 60°C',
                            'Pangan matang dingin dijaga pada suhu < 5°C',
                            'Pangan segar yang langsung dikonsumsi seperti buah potong dan salad disimpan dalam suhu yang aman yaitu di bawah 5oC (lemari pendingin) atau di wadah bersuhu dingin/(coolbox)',
                            'Jika menggunakan es batu, maka es batu dibuat dari air matang/sudah dimasak atau berasal dari sumber yang terpercaya',
                            'Tidak terdapat pangan yang busuk/basi',
                            'Air untuk minum memenuhi standar kualitas air minum/air yang sudah diolah/dimasak',
                            'Piring bersih dan tara pangan/food grade',
                            'Gelas bersih dan tara pangan/food grade',
                            'Sendok bersih dan tara pangan/food grade',
                            'Sedotan bersih dan tara pangan/food grade',
                            'Tidak ada vektor dan binatang pembawa penyakit pada area penyajian pangan',
                            'Tersedia tempat sampah (dapat menggunakan tempat sampah khusus atau plastik untuk menampung sampah sementara)',
                            'Lap/kain majun yang digunakan untuk mengelap permukaan peralatan atau meja bersih dan rutin diganti',
                            'Link Upload Dokumen Sertifikat Laik Sehat (SLHS)',
                            'Tanggal Terbit Dokumen SLHS',
                            'Tanggal Berakhir Dokumen SLHS',
                        ];
                    }
                }, 'REPORT_GERAI_PANGAN_JAJANAN_' . Carbon::now()->format('Ymd') . '.xlsx');
            default:
                abort(404);
        }
    }

    public function create()
    {
        return view('pages.inspection.gerai-pangan-jajanan.create', [
            'page_name' => 'inspection',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Check if user is authenticated or guest
            $isGuest = !Auth::check();
            
            if ($isGuest) {
                Log::info('Guest user attempting to store Gerai Pangan Jajanan data');
            }

            // Validasi input komprehensif
            $validatedData = $request->validate([
                'subjek' => 'required|string|max:255',
                'pengelola' => 'required|string|max:255',
                'alamat' => 'required|string|max:500',
                'kecamatan' => 'required|string|max:255',
                'kelurahan' => 'required|string|max:255',
                'dokumen_slhs' => 'nullable|url',
                'slhs_issued_date' => 'nullable|date',
                'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
                'status-operasi' => 'nullable|string',
                'kontak' => 'nullable|numeric',
                'koordinat' => 'required|string|max:255',
                'nama-pemeriksa' => 'nullable|string|max:255',
                'instansi-pemeriksa' => 'nullable|string|max:255',
                'tanggal-penilaian' => 'nullable|date',
                'catatan-lain' => 'nullable|string|max:1000',
                'rencana-tindak-lanjut' => 'nullable|string|max:1000',
                'tujuan-ikl' => 'nullable|string|max:255',
            ], [
                'subjek.required' => 'Nama gerai pangan jajanan wajib diisi.',
                'subjek.max' => 'Nama gerai pangan jajanan maksimal 255 karakter.',
                'pengelola.required' => 'Nama pengelola wajib diisi.',
                'pengelola.max' => 'Nama pengelola maksimal 255 karakter.',
                'alamat.required' => 'Alamat wajib diisi.',
                'alamat.max' => 'Alamat maksimal 500 karakter.',
                'kecamatan.required' => 'Kecamatan wajib diisi.',
                'kelurahan.required' => 'Kelurahan wajib diisi.',
                'koordinat.required' => 'Koordinat wajib diisi.',
                'dokumen_slhs.url' => 'Link dokumen SLHS harus berupa URL yang valid.',
                'slhs_issued_date.date' => 'Format tanggal terbit SLHS tidak valid.',
                'slhs_expire_date.date' => 'Format tanggal berakhir SLHS tidak valid.',
                'slhs_expire_date.after_or_equal' => 'Tanggal berakhir SLHS harus setelah tanggal terbit.',
            ]);

            Log::info('Gerai Pangan Jajanan form submission started', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'subjek' => $request->input('subjek'),
                'pengelola' => $request->input('pengelola'),
                'is_guest' => $isGuest
            ]);

            $data = $request->all();
            
            // Set user_id: 3 untuk guest, Auth::id() untuk user yang login
            $data['user_id'] = $isGuest ? 3 : Auth::id();
            
            // Handle instansi-lainnya logic
            if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
                $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
                unset($data['instansi-lainnya']);
            }

            $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 83) * 100);

            $insert = GeraiPanganJajanan::create($data);

            if (!$insert) {
                Log::error('Failed to create Gerai Pangan Jajanan record', [
                    'user_id' => $isGuest ? 3 : Auth::id(),
                    'is_guest' => $isGuest,
                    'data' => $validatedData
                ]);
                return redirect(route('inspection'))->with('error', 'Penilaian/inspeksi Gerai Pangan Jajanan gagal dibuat, silahkan coba lagi.');
            }

            Log::info('Gerai Pangan Jajanan record created successfully', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'record_id' => $insert->id,
                'subjek' => $insert->subjek
            ]);

            return redirect(route('gerai-pangan-jajanan.show', ['gerai_pangan_jajanan' => $insert->id]))->with('success', 'Penilaian/inspeksi Gerai Pangan Jajanan berhasil dibuat.');

        } catch (ValidationException $e) {
            $isGuest = !Auth::check();
            Log::warning('Gerai Pangan Jajanan form validation failed', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'errors' => $e->errors(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silahkan periksa kembali.');
        } catch (\Exception $e) {
            $isGuest = !Auth::check();
            Log::error('Unexpected error during Gerai Pangan Jajanan form submission', [
                'user_id' => $isGuest ? 3 : Auth::id(),
                'is_guest' => $isGuest,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['_token'])
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silahkan coba lagi atau hubungi administrator.');
        }
    }

    public function show(GeraiPanganJajanan $geraiPanganJajanan)
    {
        return view('pages.inspection.show', [
            'page_name' => 'history',
            'form_data' => $geraiPanganJajanan,
            'general_info' => $this->informasiUmum(),
            'inspection_name' => 'Gerai Pangan Jajanan',
            'edit_route' => route('gerai-pangan-jajanan.edit', ['gerai_pangan_jajanan' => $geraiPanganJajanan['id']]),
            'destroy_route' => route('gerai-pangan-jajanan.destroy', ['gerai_pangan_jajanan' => $geraiPanganJajanan['id']]),
            'export_route' => route(
                'gerai-pangan-jajanan.index',
                [
                    'export' => 'pdf',
                    'id' => $geraiPanganJajanan['id'],
                ],
            ),
        ]);
    }

    public function edit(GeraiPanganJajanan $geraiPanganJajanan)
    {
        return view('pages.inspection.gerai-pangan-jajanan.edit', [
            'page_name' => 'history',
            'informasi_umum' => $this->informasiUmum(),
            'form_penilaian' => $this->formPenilaian(),
            'form_data' => $geraiPanganJajanan,
        ]);
    }

    public function update(Request $request, GeraiPanganJajanan $geraiPanganJajanan)
    {
        // Validasi input
        $request->validate([
            'dokumen_slhs' => 'nullable|url', // Link dokumen SLHS
            'slhs_issued_date' => 'nullable|date',
            'slhs_expire_date' => 'nullable|date|after_or_equal:slhs_issued_date',
        ]);

        $data = $request->all();
        
        // Handle instansi-lainnya logic
        if (isset($data['instansi-pemeriksa']) && $data['instansi-pemeriksa'] === 'Lainnya' && isset($data['instansi-lainnya'])) {
            $data['instansi-pemeriksa'] = $data['instansi-lainnya'];
            unset($data['instansi-lainnya']);
        }
        
        // Handle file upload if present
        if ($request->hasFile('dokumen_slhs')) {
            $file = $request->file('dokumen_slhs');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen-slhs', $fileName, 'public');
            $data['dokumen_slhs'] = $filePath;
        }

        // Ensure all form penilaian fields have values
        foreach ($this->formPenilaianName() as $column) {
            $data[$column] = $request->input($column, '0');
        }

        $data['skor'] = (int) (100 - (array_reduce($this->formPenilaianName(), fn($carry, $column) => $carry + $request->input($column, 0)) / 83) * 100);

        if ($data['action'] == 'duplicate') {
            // Add auth user ID for duplicate action
            $data['user_id'] = Auth::id();

            // For duplicate, preserve the original values if current values are empty
            if (empty($data['kelurahan']) && !empty($geraiPanganJajanan->kelurahan)) {
                $data['kelurahan'] = $geraiPanganJajanan->kelurahan;
            }
            if (empty($data['kecamatan']) && !empty($geraiPanganJajanan->kecamatan)) {
                $data['kecamatan'] = $geraiPanganJajanan->kecamatan;
            }
            if (empty($data['subjek']) && !empty($geraiPanganJajanan->subjek)) {
                $data['subjek'] = $geraiPanganJajanan->subjek;
            }
            if (empty($data['alamat']) && !empty($geraiPanganJajanan->alamat)) {
                $data['alamat'] = $geraiPanganJajanan->alamat;
            }

            $insert = GeraiPanganJajanan::create($data);

            if (!$insert) {
                return redirect(route('inspection'))->with('error', 'penilaian / inspeksi Gerai Pangan Jajanan gagal dibuat, silahkan coba lagi');
            }

            return redirect(route('gerai-pangan-jajanan.show', ['gerai_pangan_jajanan' => $insert->id]))->with('success', 'penilaian / inspeksi Gerai Pangan Jajanan berhasil dibuat');
        }

        $update = $geraiPanganJajanan->update($data);

        if (!$update) {
            return redirect(route('inspection'))->with('error', 'form informasi dan penilaian Gerai Pangan Jajanan gagal diubah');
        }

        // Clear application cache to ensure fresh data is loaded
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect(route('gerai-pangan-jajanan.show', ['gerai_pangan_jajanan' => $geraiPanganJajanan['id']]))->with('success', 'form informasi dan penilaian Gerai Pangan Jajanan berhasil diubah');
    }

    public function destroy(String $id)
    {
        // Restrict guest account from deleting
        if (!Auth::check()) {
            Log::warning('Guest user attempted to delete gerai pangan jajanan data', ['user_id' => 3]);
            return redirect()->route('login')->with('error', 'Anda harus login untuk menghapus data inspeksi');
        }

        $geraiPanganJajanan = GeraiPanganJajanan::where('id', $id)->withTrashed()->first();

        if ($geraiPanganJajanan['deleted_at']) {
            $geraiPanganJajanan->update([
                'deleted_at' => null,
            ]);
            return redirect(route('archived'))->with('success', 'form inspeksi berhasil dipulihkan');
        }

        $destroy = $geraiPanganJajanan->destroy($geraiPanganJajanan['id']);

        if (!$destroy) {
            return redirect(route('gerai-pangan-jajanan.show', ['gerai_pangan_jajanan' => $geraiPanganJajanan['id']]))->with('error', 'form informasi dan penilaian Gerai Pangan Jajanan gagal dihapus');
        }

        return redirect(route('history'))->with('success', 'form informasi dan penilaian Gerai Pangan Jajanan berhasil dihapus');
    }
}