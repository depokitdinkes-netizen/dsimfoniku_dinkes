<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('subjek');
            $table->enum('jenis_sekolah', ['SD', 'SMP', 'SMA', 'SMK', 'TK', 'PAUD']);
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

            $table->integer('u004');
            $table->tinyInteger('u005');
            $table->bigInteger('u006')->nullable();

            $table->enum('a001', [0, 1]);
            $table->enum('a002', [0, 1]);
            $table->enum('a003', [0, 1]);
            $table->enum('a004', [0, 1]);
            $table->enum('a005', [0, 1]);
            $table->enum('a006', [0, 1]);
            $table->enum('a007', [0, 1]);
            $table->enum('a008', [0, 1]);
            $table->enum('a009', [0, 1]);

            $table->enum('ud001', [0, 1]);
            $table->enum('ud002', [0, 1]);
            $table->enum('ud003', [0, 1]);
            $table->enum('ud004', [0, 1]);
            $table->enum('ud005', [0, 1]);
            $table->enum('ud006', [0, 1]);
            $table->enum('ud007', [0, 1]);
            $table->enum('ud008', [0, 1]);
            $table->enum('ud009', [0, 1]);
            $table->enum('ud010', [0, 1]);
            $table->enum('ud011', [0, 1]);
            $table->enum('ud012', [0, 1]);
            $table->enum('ud013', [0, 1]);

            $table->enum('p001', [0, 1]);
            $table->enum('p002', [0, 1]);

            $table->enum('sb001', [0, 1]);
            $table->enum('sb002', [0, 1]);
            $table->enum('sb003', [0, 1]);
            $table->enum('sb004', [0, 1]);
            $table->enum('sb005', [0, 1]);
            $table->enum('sb006', [0, 1]);
            $table->enum('sb007', [0, 1]);
            $table->enum('sb008', [0, 1]);
            $table->enum('sb009', [0, 1]);
            $table->enum('sb010', [0, 1]);
            $table->enum('sb011', [0, 1]);
            $table->enum('sb012', [0, 1]);
            $table->enum('sb013', [0, 1]);
            $table->enum('sb014', [0, 1]);
            $table->enum('sb015', [0, 1]);
            $table->enum('sb016', [0, 1]);
            $table->enum('sb017', [0, 1]);
            $table->enum('sb018', [0, 1]);
            $table->enum('sb019', [0, 1]);
            $table->enum('sb020', [0, 1]);
            $table->enum('sb021', [0, 1]);
            $table->enum('sb022', [0, 1]);
            $table->enum('sb023', [0, 1]);
            $table->enum('sb024', [0, 1]);
            $table->enum('sb025', [0, 1]);
            $table->enum('sb026', [0, 1]);
            $table->enum('sb027', [0, 1]);
            $table->enum('sb028', [0, 1]);

            $table->enum('vb001', [0, 1]);
            $table->enum('vb002', [0, 1]);
            $table->enum('vb003', [0, 1]);
            $table->enum('vb004', [0, 1]);
            $table->enum('vb005', [0, 1]);
            $table->enum('vb006', [0, 1]);

            $table->text('hpp001');
            $table->text('hpp002');
            $table->text('hpp003');
            $table->text('hpp004');
            $table->text('hpp005');
            $table->text('hpp006');
            $table->text('hpp007');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('sekolah');
    }
};
