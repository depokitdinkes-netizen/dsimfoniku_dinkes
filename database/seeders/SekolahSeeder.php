<?php

namespace Database\Seeders;

use App\Models\FormIKL\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data sekolah
        Sekolah::create([
            'subjek' => 'SDN Depok Baru 01',
            'jenis_sekolah' => 'SD',
            'pengelola' => 'Dra. Siti Aminah, M.Pd',
            'alamat' => 'Jl. Margonda Raya No. 123',
            'kelurahan' => 'Depok',
            'kecamatan' => 'Pancoran Mas',
            'kontak' => '02177889900',
            'status-operasi' => '1',
            'koordinat' => '-6.3751, 106.8310',
            'nama-pemeriksa' => 'Dr. Ahmad Sanitarian',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-10-01',
            'skor' => 85,
            'catatan-lain' => 'Kondisi sekolah baik secara keseluruhan',
            'rencana-tindak-lanjut' => 'Perbaikan saluran air',
            'u004' => 450,
            'u005' => 25,
            'u006' => 20101234,
            'a001' => '1', 'a002' => '1', 'a003' => '1', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '1', 'ud005' => '1', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '1',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '300 lux', 'hpp002' => '250 lux', 'hpp003' => '280 lux', 'hpp004' => '50%', 'hpp005' => '40 dB', 'hpp006' => '30 µg/m³', 'hpp007' => '45 µg/m³'
        ]);

        Sekolah::create([
            'subjek' => 'SMPN 15 Depok',
            'jenis_sekolah' => 'SMP',
            'pengelola' => 'Drs. Bambang Sutrisno, M.Pd',
            'alamat' => 'Jl. Raya Sawangan No. 45',
            'kelurahan' => 'Sawangan',
            'kecamatan' => 'Sawangan',
            'kontak' => '02177889901',
            'status-operasi' => '1',
            'koordinat' => '-6.4051, 106.7810',
            'nama-pemeriksa' => 'Dr. Sari Kesehatan',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-09-15',
            'skor' => 78,
            'catatan-lain' => 'Perlu perbaikan ventilasi',
            'rencana-tindak-lanjut' => 'Penambahan ventilasi udara',
            'u004' => 320,
            'u005' => 18,
            'u006' => 20101235,
            'a001' => '1', 'a002' => '1', 'a003' => '0', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '0', 'ud005' => '0', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '0',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '280 lux', 'hpp002' => '240 lux', 'hpp003' => '260 lux', 'hpp004' => '65%', 'hpp005' => '42 dB', 'hpp006' => '35 µg/m³', 'hpp007' => '50 µg/m³'
        ]);

        Sekolah::create([
            'subjek' => 'SMA Bina Bangsa',
            'jenis_sekolah' => 'SMA',
            'pengelola' => 'Dra. Maria Cristina, S.Pd',
            'alamat' => 'Jl. UI No. 78',
            'kelurahan' => 'Kukusan',
            'kecamatan' => 'Beji',
            'kontak' => '02177889902',
            'status-operasi' => '1',
            'koordinat' => '-6.3651, 106.8210',
            'nama-pemeriksa' => 'Dr. Ahmad Sanitarian',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-08-20',
            'skor' => 92,
            'catatan-lain' => 'Kondisi sangat baik',
            'rencana-tindak-lanjut' => 'Maintenance rutin',
            'u004' => 280,
            'u005' => 22,
            'u006' => 20101236,
            'a001' => '1', 'a002' => '1', 'a003' => '1', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '1', 'ud005' => '1', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '1',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '320 lux', 'hpp002' => '290 lux', 'hpp003' => '310 lux', 'hpp004' => '45%', 'hpp005' => '38 dB', 'hpp006' => '25 µg/m³', 'hpp007' => '35 µg/m³'
        ]);

        // Islamic Schools (Madrasah)
        Sekolah::create([
            'subjek' => 'MI Al-Hidayah',
            'jenis_sekolah' => 'MI',
            'pengelola' => 'Ustadz Ahmad Fauzi, S.Pd.I',
            'alamat' => 'Jl. Masjid Raya No. 15',
            'kelurahan' => 'Pancoran Mas',
            'kecamatan' => 'Pancoran Mas',
            'kontak' => '02177889903',
            'status-operasi' => '1',
            'koordinat' => '-6.3851, 106.8410',
            'nama-pemeriksa' => 'Dr. Sari Kesehatan',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-09-10',
            'skor' => 88,
            'catatan-lain' => 'Kondisi madrasah baik, perlu peningkatan fasilitas cuci tangan',
            'rencana-tindak-lanjut' => 'Penambahan wastafel di setiap kelas',
            'u004' => 380,
            'u005' => 20,
            'u006' => 20101237,
            'a001' => '1', 'a002' => '1', 'a003' => '1', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '1', 'ud005' => '0', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '1',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '290 lux', 'hpp002' => '260 lux', 'hpp003' => '280 lux', 'hpp004' => '50%', 'hpp005' => '39 dB', 'hpp006' => '28 µg/m³', 'hpp007' => '40 µg/m³'
        ]);

        Sekolah::create([
            'subjek' => 'MTs Nurul Iman',
            'jenis_sekolah' => 'MTs',
            'pengelola' => 'Ustadzah Fatimah Zahra, S.Ag',
            'alamat' => 'Jl. Pesantren No. 25',
            'kelurahan' => 'Sukmajaya',
            'kecamatan' => 'Sukmajaya',
            'kontak' => '02177889904',
            'status-operasi' => '1',
            'koordinat' => '-6.3951, 106.8510',
            'nama-pemeriksa' => 'Dr. Ahmad Sanitarian',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-09-05',
            'skor' => 82,
            'catatan-lain' => 'Kondisi madrasah cukup baik, perlu perbaikan ventilasi ruang kelas',
            'rencana-tindak-lanjut' => 'Penambahan jendela dan ventilasi udara',
            'u004' => 295,
            'u005' => 16,
            'u006' => 20101238,
            'a001' => '1', 'a002' => '1', 'a003' => '0', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '0', 'ud005' => '1', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '0',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '275 lux', 'hpp002' => '245 lux', 'hpp003' => '265 lux', 'hpp004' => '60%', 'hpp005' => '43 dB', 'hpp006' => '32 µg/m³', 'hpp007' => '48 µg/m³'
        ]);

        Sekolah::create([
            'subjek' => 'MA Darul Falah',
            'jenis_sekolah' => 'MA',
            'pengelola' => 'Ustadz Muhammad Ridwan, M.Pd.I',
            'alamat' => 'Jl. Islamic Center No. 10',
            'kelurahan' => 'Limo',
            'kecamatan' => 'Limo',
            'kontak' => '02177889905',
            'status-operasi' => '1',
            'koordinat' => '-6.3751, 106.7910',
            'nama-pemeriksa' => 'Dr. Sari Kesehatan',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'tanggal-penilaian' => '2024-08-25',
            'skor' => 90,
            'catatan-lain' => 'Kondisi madrasah sangat baik, fasilitas memadai',
            'rencana-tindak-lanjut' => 'Maintenance berkala fasilitas',
            'u004' => 240,
            'u005' => 19,
            'u006' => 20101239,
            'a001' => '1', 'a002' => '1', 'a003' => '1', 'a004' => '1', 'a005' => '1', 'a006' => '1', 'a007' => '1', 'a008' => '1', 'a009' => '1',
            'ud001' => '1', 'ud002' => '1', 'ud003' => '1', 'ud004' => '1', 'ud005' => '1', 'ud006' => '1', 'ud007' => '1', 'ud008' => '1', 'ud009' => '1', 'ud010' => '1', 'ud011' => '1', 'ud012' => '1', 'ud013' => '1',
            'p001' => '1', 'p002' => '1',
            'sb001' => '1', 'sb002' => '1', 'sb003' => '1', 'sb004' => '1', 'sb005' => '1', 'sb006' => '1', 'sb007' => '1', 'sb008' => '1', 'sb009' => '1', 'sb010' => '1', 'sb011' => '1', 'sb012' => '1', 'sb013' => '1', 'sb014' => '1', 'sb015' => '1', 'sb016' => '1', 'sb017' => '1', 'sb018' => '1', 'sb019' => '1', 'sb020' => '1', 'sb021' => '1', 'sb022' => '1', 'sb023' => '1', 'sb024' => '1', 'sb025' => '1', 'sb026' => '1', 'sb027' => '1', 'sb028' => '1',
            'vb001' => '1', 'vb002' => '1', 'vb003' => '1', 'vb004' => '1', 'vb005' => '1', 'vb006' => '1',
            'hpp001' => '315 lux', 'hpp002' => '285 lux', 'hpp003' => '305 lux', 'hpp004' => '48%', 'hpp005' => '37 dB', 'hpp006' => '26 µg/m³', 'hpp007' => '38 µg/m³'
        ]);
    }
}
