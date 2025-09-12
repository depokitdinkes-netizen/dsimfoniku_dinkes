<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('puskesmas', function (Blueprint $table) {
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
            $table->bigInteger('u005')->nullable();

            $table->enum('a001', [0, 1]);
            $table->enum('a002', [0, 1]);
            $table->enum('a003', [0, 1]);
            $table->enum('a004', [0, 1]);
            $table->enum('a005', [0, 1]);
            $table->enum('a006', [0, 1]);
            $table->enum('a007', [0, 1]);
            $table->enum('a008', [0, 1]);
            $table->enum('a009', [0, 1]);
            $table->enum('a010', [0, 1]);
            $table->enum('a011', [0, 1]);
            $table->enum('a012', [0, 1]);
            $table->enum('a013', [0, 1]);
            $table->enum('a014', [0, 1]);
            $table->enum('a015', [0, 1]);
            $table->enum('a016', [0, 1]);
            $table->enum('a017', [0, 1]);
            $table->enum('a018', [0, 1]);
            $table->enum('a019', [0, 1]);
            $table->enum('a020', [0, 1]);
            $table->enum('a021', [0, 1]);
            $table->enum('a022', [0, 1]);
            $table->enum('a023', [0, 1]);
            $table->enum('a024', [0, 1]);
            $table->enum('a025', [0, 1]);
            $table->enum('a026', [0, 1]);
            $table->enum('a027', [0, 1]);
            $table->enum('a028', [0, 1]);
            $table->enum('a029', [0, 1]);
            $table->enum('a030', [0, 1]);
            $table->enum('a031', [0, 1]);
            $table->enum('a032', [0, 1]);
            $table->enum('a033', [0, 1]);
            $table->enum('a034', [0, 1]);
            $table->enum('a035', [0, 1]);
            $table->enum('a036', [0, 1]);
            $table->enum('a037', [0, 1]);
            $table->enum('a038', [0, 1]);
            $table->enum('a039', [0, 1]);
            $table->enum('a040', [0, 1]);
            $table->enum('a041', [0, 1]);
            $table->enum('a042', [0, 1]);
            $table->enum('a043', [0, 1]);
            $table->enum('a044', [0, 1]);
            $table->enum('a045', [0, 1]);
            $table->enum('a046', [0, 1]);
            $table->enum('a047', [0, 1]);
            $table->enum('a048', [0, 1]);
            $table->enum('a049', [0, 1]);

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
            $table->enum('ud014', [0, 1]);
            $table->enum('ud016', [0, 1]);
            $table->enum('ud017', [0, 1]);
            $table->enum('ud018', [0, 1]);
            $table->enum('ud019', [0, 1]);
            $table->enum('ud020', [0, 1]);

            $table->enum('p001', [0, 1]);

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
            $table->enum('sb029', [0, 1]);
            $table->enum('sb030', [0, 1]);
            $table->enum('sb031', [0, 1]);
            $table->enum('sb032', [0, 1]);
            $table->enum('sb033', [0, 1]);
            $table->enum('sb034', [0, 1]);
            $table->enum('sb035', [0, 1]);
            $table->enum('sb036', [0, 1]);
            $table->enum('sb037', [0, 1]);
            $table->enum('sb038', [0, 1]);
            $table->enum('sb039', [0, 1]);
            $table->enum('sb040', [0, 1]);
            $table->enum('sb041', [0, 1]);

            $table->enum('vb001', [0, 1]);
            $table->enum('vb002', [0, 1]);
            $table->enum('vb003', [0, 1]);
            $table->enum('vb004', [0, 1]);
            $table->enum('vb005', [0, 1]);
            $table->enum('vb006', [0, 1]);
            $table->enum('vb007', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('puskesmas');
    }
};
