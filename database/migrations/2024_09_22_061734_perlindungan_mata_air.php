<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('perlindungan_mata_air', function (Blueprint $table) {
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

            $table->string('u002');
            $table->string('u003');

            $table->enum('kfa001', [0, 1]);
            $table->enum('kfa002', [0, 1]);
            $table->enum('kfa003', [0, 1]);
            $table->enum('kfa004', [0, 1]);

            $table->enum('int001', [0, 1]);
            $table->enum('int002', [0, 1]);
            $table->enum('int003', [0, 1]);
            $table->enum('int004', [0, 1]);
            $table->enum('int005', [0, 1]);
            $table->enum('int006', [0, 1]);
            $table->enum('int007', [0, 1]);
            $table->enum('int008', [0, 1]);
            $table->enum('int009', [0, 1]);
            $table->enum('int010', [0, 1]);
            $table->enum('int011', [0, 1]);

            $table->enum('ada-bangunan-penangkap', [0, 1]);

            $table->enum('int012', [0, 1]);
            $table->enum('int013', [0, 1]);
            $table->enum('int014', [0, 1]);
            $table->enum('int015', [0, 1]);
            $table->enum('int016', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('perlindungan_mata_air');
    }
};
