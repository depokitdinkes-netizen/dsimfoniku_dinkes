<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IklInspectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $userId = 1; // Asumsi user dengan ID 1 ada
        
        // Data sample untuk 1 Restoran saja
        DB::table('restoran')->insert([
            'subjek' => 'Restoran Padang Sederhana',
            'pengelola' => 'Budi Santoso',
            'kontak' => '081234567890',
            'alamat' => 'Jl. Margonda Raya No. 123',
            'kelurahan' => 'Pondok Cina',
            'kecamatan' => 'Beji',
            'koordinat' => '-6.3649, 106.8338',
            'tanggal-penilaian' => $now->subDays(5)->format('Y-m-d'),
            'nama-pemeriksa' => 'Dr. Ahmad Sanusi',
            'instansi-pemeriksa' => 'Dinas Kesehatan Kota Depok',
            'u004' => 5, // Total penjamah pangan
            'u005' => 3, // Penjamah pangan bersertifikat
            'u006' => 'SIUP/001/2024',
            'u009' => 'UMUM',
            'skor' => 85,
            'catatan-lain' => 'Kebersihan dapur perlu ditingkatkan',
            'rencana-tindak-lanjut' => 'Perbaikan sistem ventilasi dalam 2 minggu',
            'user_id' => $userId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        echo "Sample data created successfully!\n";
        echo "Data created: 1 Restoran (tanpa dokumen SLHS)\n";
        echo "Dokumen SLHS bisa ditambahkan melalui edit form.\n";
    }
}
