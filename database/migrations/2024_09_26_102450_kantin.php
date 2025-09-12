<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('kantin', function (Blueprint $table) {
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
            $table->tinyInteger('u009');

            $table->enum('tp001', [0, 3]);
            $table->enum('tp002', [0, 1]);
            $table->enum('tp003', [0, 1]);

            $table->enum('pk001', [0, 1]);
            $table->enum('pk002', [0, 1]);
            $table->enum('pk003', [0, 1]);
            $table->enum('pk004', [0, 1]);
            $table->enum('pk005', [0, 1]);
            $table->enum('pk006', [0, 2]);
            $table->enum('pk007', [0, 1]);
            $table->enum('pk008', [0, 3]);
            $table->enum('pk009', [0, 1]);
            $table->enum('pk010', [0, 1]);
            $table->enum('pk011', [0, 3]);
            $table->enum('pk012', [0, 3]);
            $table->enum('pk013', [0, 1]);
            $table->enum('pk014', [0, 3]);
            $table->enum('pk015', [0, 3]);
            $table->enum('pk016', [0, 2]);
            $table->enum('pk017', [0, 1]);
            $table->enum('pk018', [0, 1]);
            $table->enum('pk019', [0, 3]);
            $table->enum('pk020', [0, 1]);
            $table->enum('pk021', [0, 2]);
            $table->enum('pk022', [0, 1]);
            $table->enum('pk023', [0, 1]);
            $table->enum('pk024', [0, 1]);
            $table->enum('pk025', [0, 1]);
            $table->enum('pk026', [0, 1]);
            $table->enum('pk027', [0, 1]);
            $table->enum('pk028', [0, 1]);
            $table->enum('pk029', [0, 3]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('kantin');
    }
};
