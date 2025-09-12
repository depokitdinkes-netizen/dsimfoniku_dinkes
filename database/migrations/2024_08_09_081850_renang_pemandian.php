<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('renang_pemandian', function (Blueprint $table) {
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

            $table->date('u004');
            $table->integer('u005');

            $table->enum('t001', [0, 2]);
            $table->enum('t002', [0, 2]);
            $table->enum('t003', [0, 2]);
            $table->enum('t004', [0, 1]);
            $table->enum('t005', [0, 1]);

            $table->enum('p001', [0, 2]);
            $table->enum('p002', [0, 2]);
            $table->enum('p003', [0, 2]);
            $table->enum('p004', [0, 2]);
            $table->enum('p005', [0, 2]);

            $table->enum('pk001', [0, 2]);
            $table->enum('pk002', [0, 3]);
            $table->enum('pk003', [0, 2]);
            $table->enum('pk004', [0, 2]);
            $table->enum('pk005', [0, 3]);

            $table->enum('sb001', [0, 2]);
            $table->enum('sb002', [0, 2]);
            $table->enum('sb003', [0, 2]);
            $table->enum('sb004', [0, 2]);
            $table->enum('sb005', [0, 1]);
            $table->enum('sb006', [0, 2]);
            $table->enum('sb007', [0, 2]);
            $table->enum('sb008', [0, 2]);
            $table->enum('sb009', [0, 2]);
            $table->enum('sb010', [0, 2]);
            $table->enum('sb011', [0, 2]);
            $table->enum('sb012', [0, 2]);

            $table->enum('a001', [0, 2]);
            $table->enum('a002', [0, 2]);
            $table->enum('a003', [0, 2]);
            $table->enum('a004', [0, 4]);
            $table->enum('a005', [0, 2]);
            $table->enum('a006', [0, 3]);
            $table->enum('a007', [0, 3]);

            $table->enum('uk001', [0, 2]);
            $table->enum('uk002', [0, 2]);
            $table->enum('uk003', [0, 2]);
            $table->enum('uk004', [0, 2]);
            $table->enum('uk005', [0, 2]);

            $table->enum('k001', [0, 12]);

            $table->enum('v001', [0, 2]);
            $table->enum('v002', [0, 2]);

            $table->enum('s001', [0, 3]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('renang_pemandian');
    }
};
