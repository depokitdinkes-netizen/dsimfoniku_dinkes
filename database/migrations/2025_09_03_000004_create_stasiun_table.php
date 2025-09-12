<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stasiun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('subjek'); // Nama Stasiun
            $table->string('pengelola'); // Pengelola/Kepala Stasiun
            $table->string('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kontak'); // Nomor kontak (hanya angka)
            $table->enum('status-operasi', [0, 1]);
            $table->string('koordinat');
            $table->string('nama-pemeriksa');
            $table->string('instansi-pemeriksa');
            $table->date('tanggal-penilaian');
            $table->tinyInteger('skor')->length(3);
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Form Penilaian Air
            $table->enum('a001', [0, 1]); // Tersedia air minum yang cukup untuk semua keperluan
            $table->enum('a002', [0, 1]); // Tersedia air siap minum untuk umum selain kran di tempat kerja
            $table->enum('a003', [0, 1]); // Memiliki akses terhadap sarana cuci tangan pakai sabun dan air mengalir yang berfungsi di area umum/lobby

            // Form Penilaian Udara
            $table->enum('ud001', [0, 1]); // Tidak ada sumber pencemar udara di sekitar stasiun/terminal
            $table->text('ud002')->nullable(); // Jika ada sumber pencemar, sebutkan (bisa lebih dari satu)
            $table->enum('ud003', [0, 1]); // Jarak stasiun/terminal dari sumber pencemar udara lebih dari 150 meter
            $table->enum('ud004', [0, 1]); // Tidak terdapat aktivitas membakar sampah di stasiun/terminal
            $table->enum('ud005', [0, 1]); // Semua atau sebagian ruangan ada ventilasi yang terbuka secara permanen dengan ukuran lebih besar atau sama dengan 10 persen dari luas lantai
            $table->enum('ud006', [0, 1]); // Terdapat alat bantu sirkulasi udara yang berfungsi baik dan bersih
            $table->text('ud007')->nullable(); // Jika ada alat bantu sirkulasi udara, sebutkan
            $table->enum('ud008', [0, 1]); // Tersedia ruang terbuka hijau / pohon rindang / banyak tanaman
            $table->enum('ud009', [0, 1]); // Menerapkan Kawasan Tanpa Rokok dan tidak ditemukan ada yang merokok/vaping/menggunakan rokok elektrik di lingkungan stasiun/terminal
            $table->enum('ud010', [0, 1]); // Tidak terdapat bahan kimia yang menjadi sumber pencemar udara dalam ruangan
            $table->text('ud011')->nullable(); // Jika ada bahan kimia pencemar, sebutkan
            $table->enum('ud012', [0, 1]); // Terdapat aktivitas membersihkan area lingkungan terminal/stasiun
            $table->text('ud013')->nullable(); // Jika ada aktivitas membersihkan, sebutkan frekuensi kegiatan

            // Form Penilaian Pangan
            $table->enum('p001', [0, 1]); // Memiliki fasilitas penjualan makanan/minuman
            $table->enum('p002', [0, 1]); // Sudah pernah melakukan IKL pada fasilitas penjualan

            // Form Penilaian Sarana Dan Bangunan
            $table->enum('sb001', [0, 1]); // Tersedia Tempat Penampungan Sementara (TPS) sampah yang tidak mencemari lingkungan
            $table->enum('sb002', [0, 1]); // Semua sampah setiap hari diangkut
            $table->enum('sb003', [0, 1]); // Tersedia pencahayaan alami/buatan yang diterapkan pada ruangan baik di dalam bangunan maupun di luar bangunan gedung untuk bisa melakukan seluruh aktivitas
            $table->enum('sb004', [0, 1]); // Seluruh sarana dan prasarana bangunan bersih dan mudah dibersihkan
            $table->enum('sb005', [0, 1]); // Tersedia area titik kumpul untuk evakuasi
            $table->enum('sb006', [0, 1]); // Adanya fasilitas ruang ibadah yang memenuhi syarat kesehatan
            $table->enum('sb007', [0, 1]); // Memiliki akses terhadap toilet dan peturasan tersedia dengan jumlah yang cukup dan berfungsi
            $table->enum('sb008', [0, 1]); // Semua toilet dan peturasan bersih dan memenuhi syarat
            $table->enum('sb009', [0, 1]); // Toilet laki-laki terpisah dengan perempuan
            $table->enum('sb010', [0, 1]); // Tersedia toilet bagi penyandang disabilitas
            $table->enum('sb011', [0, 1]); // Memiliki ruang laktasi berfungsi dengan baik

            // Form Penilaian Vektor Dan Binatang Pembawa Penyakit
            $table->enum('vb001', [0, 1]); // Lingkungan stasiun/terminal harus bebas jentik nyamuk
            $table->enum('vb002', [0, 1]); // Lingkungan stasiun/terminal harus bebas vektor (nyamuk, lalat, kecoa) dan binatang pembawa penyakit (tikus)

            // Pengelolaan Limbah dan Sampah
            $table->enum('pl001', [0, 1]); // Tersedianya instalasi pengolahan air limbah yang memenuhi syarat
            $table->enum('pl002', [0, 1]); // Sistem saluran limbah cair yang tertutup
            $table->enum('pl003', [0, 1]); // Tidak terdapat genangan air limbah
            $table->enum('pl004', [0, 1]); // Tidak terdapat sampah berserakan di tempat umum

            // Hasil Pengukuran
            $table->string('hpp001')->nullable(); // Pencahayaan di Ruang Tunggu
            $table->string('hpp002')->nullable(); // Pencahayaan di Area Peron
            $table->string('hpp003')->nullable(); // Pencahayaan di Ruang Tiket/Loket
            $table->string('hpp004')->nullable(); // Kelembaban
            $table->string('hpp005')->nullable(); // Kebisingan
            $table->string('hpp006')->nullable(); // PM 2,5
            $table->string('hpp007')->nullable(); // PM10
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stasiun');
    }
};
