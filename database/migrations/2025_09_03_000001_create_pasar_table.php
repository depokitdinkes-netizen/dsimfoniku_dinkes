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
        Schema::create('pasar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informasi Umum
            $table->string('subjek');
            $table->string('pengelola');
            $table->string('alamat');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('kontak')->nullable();
            $table->enum('status-operasi', [0, 1])->default(1);
            $table->string('koordinat')->nullable();
            $table->string('nama-pemeriksa');
            $table->string('instansi-pemeriksa');
            $table->date('tanggal-penilaian');
            
            // Hasil dan catatan
            $table->tinyInteger('skor')->length(3)->default(0);
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // A. Lokasi
            $table->enum('l001', [0, 2])->default(0);
            $table->enum('l002', [0, 2])->default(0);
            $table->enum('l003', [0, 2])->default(0);
            $table->enum('l004', [0, 2])->default(0);
            $table->enum('l005', [0, 2])->default(0);
            
            // B. Bangunan Pasar - Umum
            $table->enum('u001', [0, 2])->default(0);
            
            // Penataan Ruang Dagang
            $table->enum('prd001', [0, 2])->default(0);
            $table->enum('prd002', [0, 2])->default(0);
            $table->enum('prd003', [0, 2])->default(0);
            $table->enum('prd004', [0, 3])->default(0);
            
            // Ruang Kantor Pengelola
            $table->enum('rkp001', [0, 2])->default(0);
            $table->enum('rkp002', [0, 2])->default(0);
            $table->enum('rkp003', [0, 2])->default(0);
            $table->enum('rkp004', [0, 2])->default(0);
            
            // Tempat Penjualan Bahan Pangan Basah
            $table->enum('tpb001', [0, 2])->default(0);
            $table->enum('tpb002', [0, 2])->default(0);
            $table->enum('tpb003', [0, 2])->default(0);
            $table->enum('tpb004', [0, 3])->default(0);
            $table->enum('tpb005', [0, 2])->default(0);
            $table->enum('tpb006', [0, 3])->default(0);
            $table->enum('tpb007', [0, 2])->default(0);
            $table->enum('tpb008', [0, 2])->default(0);
            $table->enum('tpb009', [0, 3])->default(0);
            
            // Tempat Penjualan Bahan Pangan Kering
            $table->enum('tpk001', [0, 2])->default(0);
            $table->enum('tpk002', [0, 2])->default(0);
            $table->enum('tpk003', [0, 2])->default(0);
            $table->enum('tpk004', [0, 3])->default(0);
            $table->enum('tpk005', [0, 3])->default(0);
            
            // Tempat Penjualan Makanan Matang/Siap Saji
            $table->enum('tpm001', [0, 3])->default(0);
            
            // Area Parkir
            $table->enum('ap001', [0, 2])->default(0);
            $table->enum('ap002', [0, 2])->default(0);
            $table->enum('ap003', [0, 2])->default(0);
            $table->enum('ap004', [0, 2])->default(0);
            $table->enum('ap005', [0, 2])->default(0);
            $table->enum('ap006', [0, 2])->default(0);
            $table->enum('ap007', [0, 1])->default(0);
            $table->enum('ap008', [0, 2])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasar');
    }
};
