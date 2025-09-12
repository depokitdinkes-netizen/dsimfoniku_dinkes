<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('gerai_pangan_jajanan', function (Blueprint $table) {
            $table->id();
            $table->string('subjek');
            $table->string('pengelola');
            $table->string('alamat');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kontak');
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

            $table->tinyInteger('u004');
            $table->tinyInteger('u005');
            $table->bigInteger('u006')->nullable();
            $table->string('u008');

            $table->enum('ij001', [0, 3]);
            $table->enum('ij002', [0, 1]);
            $table->enum('ij003', [0, 1]);
            $table->enum('ij004', [0, 1]);
            $table->enum('ij005', [0, 1]);
            $table->enum('ij006', [0, 1]);

            $table->enum('p001', [0, 3]);
            $table->enum('p002', [0, 3]);
            $table->enum('p003', [0, 3]);
            $table->enum('p004', [0, 3]);
            $table->enum('p005', [0, 3]);
            $table->enum('p006', [0, 2]);
            $table->enum('p007', [0, 3]);
            $table->enum('p008', [0, 3]);
            $table->enum('p009', [0, 3]);
            $table->enum('p010', [0, 3]);

            $table->enum('ppm001', [0, 1]);
            $table->enum('ppm002', [0, 3]);
            $table->enum('ppm003', [0, 2]);
            $table->enum('ppm004', [0, 3]);
            $table->enum('ppm005', [0, 3]);
            $table->enum('ppm006', [0, 3]);
            $table->enum('ppm007', [0, 3]);
            $table->enum('ppm008', [0, 3]);
            $table->enum('ppm009', [0, 3]);
            $table->enum('ppm010', [0, 3]);
            $table->enum('ppm011', [0, 3]);
            $table->enum('ppm012', [0, 3]);
            $table->enum('ppm013', [0, 3]);
            $table->enum('ppm014', [0, 3]);
            $table->enum('ppm015', [0, 3]);
            $table->enum('ppm016', [0, 2]);
            $table->enum('ppm017', [0, 2]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('gerai_pangan_jajanan');
    }
};
