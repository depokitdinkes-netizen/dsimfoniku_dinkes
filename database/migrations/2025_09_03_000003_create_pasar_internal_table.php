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
        Schema::create('pasar_internal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informasi Umum
            $table->string('subjek');
            $table->string('pengelola');
            $table->string('alamat');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('koordinat')->nullable();
            $table->integer('jumlah_total_pedagang')->nullable();
            $table->integer('jumlah_total_kios')->nullable();
            $table->string('nama-pemeriksa');
            $table->string('instansi-pemeriksa');
            $table->date('tanggal-penilaian');
            
            // Hasil dan catatan
            $table->tinyInteger('skor')->length(3)->default(0);
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // A. Bangunan Pasar
            $table->enum('bp001', [0, 1])->default(0);
            $table->enum('bp002', [0, 1])->default(0);
            $table->enum('bp003', [0, 1])->default(0);
            $table->enum('bp004', [0, 1])->default(0);
            $table->enum('bp005', [0, 1])->default(0);
            $table->enum('bp006', [0, 1])->default(0);
            $table->enum('bp007', [0, 1])->default(0);
            $table->enum('bp008', [0, 1])->default(0);
            $table->enum('bp009', [0, 1])->default(0);
            
            // B. Bangunan Kios/Los
            $table->enum('bkl001', [0, 1])->default(0);
            $table->enum('bkl002', [0, 1])->default(0);
            $table->enum('bkl003', [0, 1])->default(0);
            
            // C. Tempat Pembuangan Sampah
            $table->enum('tps001', [0, 1])->default(0);
            $table->enum('tps002', [0, 1])->default(0);
            $table->enum('tps003', [0, 1])->default(0);
            $table->enum('tps004', [0, 1])->default(0);
            $table->enum('tps005', [0, 1])->default(0);
            
            // D. Saluran Limbah dan Drainase
            $table->enum('sld001', [0, 1])->default(0);
            $table->enum('sld002', [0, 1])->default(0);
            $table->enum('sld003', [0, 1])->default(0);
            
            // E. Toilet
            $table->enum('t001', [0, 1])->default(0);
            $table->enum('t002', [0, 1])->default(0);
            $table->enum('t003', [0, 1])->default(0);
            $table->enum('t004', [0, 1])->default(0);
            $table->enum('t005', [0, 1])->default(0);
            $table->enum('t006', [0, 1])->default(0);
            
            // F. Air Bersih
            $table->enum('ab001', [0, 1])->default(0);
            $table->enum('ab002', [0, 1])->default(0);
            $table->enum('ab003', [0, 1])->default(0);
            
            // G. Tempat Penjualan Makanan dan Bahan Pangan
            $table->enum('tpmmm001', [0, 1])->default(0);
            $table->enum('tpmmm002', [0, 1])->default(0);
            $table->enum('tpmmm003', [0, 1])->default(0);
            $table->enum('tpmmm004', [0, 1])->default(0);
            $table->enum('tpmmm005', [0, 1])->default(0);
            $table->enum('tpmmm006', [0, 1])->default(0);
            $table->enum('tpmmm007', [0, 1])->default(0);
            $table->enum('tpmmm008', [0, 1])->default(0);
            $table->enum('tpmmm009', [0, 1])->default(0);
            
            // H. Pengendalian Binatang Penularan Penyakit
            $table->enum('pb001', [0, 1])->default(0);
            $table->enum('pb002', [0, 1])->default(0);
            $table->enum('pb003', [0, 1])->default(0);
            
            // I. Keamanan Pasar
            $table->enum('kpp001', [0, 1])->default(0);
            $table->enum('kpp002', [0, 1])->default(0);
            
            // J. Pencahayaan, Suhu, dan Kelembaban
            $table->enum('psk001', [0, 1])->default(0);
            $table->enum('psk002', [0, 1])->default(0);
            
            // K. Tempat Cuci Tangan
            $table->enum('tctt001', [0, 1])->default(0);
            $table->enum('tctt002', [0, 1])->default(0);
            
            // L. Tempat Parkir
            $table->enum('tp001', [0, 1])->default(0);
            $table->enum('tp002', [0, 1])->default(0);
            
            // M. Pedagang/Karyawan
            $table->enum('pkk001', [0, 1])->default(0);
            $table->enum('pkk002', [0, 1])->default(0);
            $table->enum('pkk003', [0, 1])->default(0);
            $table->enum('pkk004', [0, 1])->default(0);
            $table->enum('pkk005', [0, 1])->default(0);
            $table->enum('pkk006', [0, 1])->default(0);
            $table->enum('pkk007', [0, 1])->default(0);
            
            // N. Pengunjung
            $table->enum('p001', [0, 1])->default(0);
            $table->enum('p002', [0, 1])->default(0);
            $table->enum('p003', [0, 1])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasar_internal');
    }
};
