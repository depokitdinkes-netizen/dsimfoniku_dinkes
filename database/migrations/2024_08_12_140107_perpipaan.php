<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('perpipaan', function (Blueprint $table) {
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

            $table->string('u001');
            $table->enum('u006', [1, 0, -1]);
            $table->enum('u007', ['0-15', '15-30', '>30']);
            $table->enum('u008', ['HUJAN_LEBAT', 'HUJAN', 'PANAS']);
            $table->string('u009');
            $table->enum('u010', [0, 1, -1]);
            $table->string('u010a')->nullable();
            $table->enum('u011', [0, 1]);
            $table->enum('u011a', ['BANJIR', 'KEMARAU', 'LISTRIK_PADAM', 'POMPA_RUSAK'])->nullable();

            $table->enum('ins001', [0, 1]);
            $table->enum('ins002', ['PENGENDAPAN', 'PENYARINGAN', 'DISINFEKSI'])->nullable();
            $table->string('ins003')->nullable();

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

            $table->string('int012');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('perpipaan');
    }
};
