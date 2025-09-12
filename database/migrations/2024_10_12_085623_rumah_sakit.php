<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('rumah_sakit', function (Blueprint $table) {
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
            $table->integer('skor');
            $table->text('pelaporan-elektronik');
            $table->text('pengamanan-radiasi');
            $table->text('penyehatan-air-hemodiolisa');
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->enum('kelas', ['A', 'B', 'C']);
            $table->integer('jumlah-tempat-tidur');

            $table->enum('1001', [4 * 100, 4 * 50, 4 * 0]);
            $table->enum('1002a', [0, 400]);
            $table->enum('1002b', [0, 400]);
            $table->enum('1002c', [0, 100]);
            $table->enum('1003', [3 * 100, 3 * 50, 3 * 0]);
            $table->enum('1004', [3 * 100, 3 * 50, 3 * 0]);

            $table->enum('2001a', [0, 100]);
            $table->enum('2001b', [0, 100]);
            $table->enum('2002', [2 * 100, 2 * 50]);
            $table->enum('2003a', [0, 20]);
            $table->enum('2003b', [0, 20]);
            $table->enum('2003c', [0, 20]);
            $table->enum('2003d', [0, 20]);
            $table->enum('2003e', [0, 20]);
            $table->enum('2003f', [0, 20]);
            $table->enum('2003g', [0, 20]);
            $table->enum('2003h', [0, 20]);
            $table->enum('2003i', [0, 10]);
            $table->enum('2003j', [0, 10]);
            $table->enum('2003k', [0, 20]);
            $table->enum('2004a', [0, 30]);
            $table->enum('2004b', [0, 20]);
            $table->enum('2004c', [0, 10]);
            $table->enum('2004d', [0, 10]);
            $table->enum('2004e', [0, 10]);
            $table->enum('2004f', [0, 10]);
            $table->enum('2004g', [0, 10]);
            $table->enum('2004h', [0, 10]);
            $table->enum('2004i', [0, 10]);
            $table->enum('2004j', [0, 10]);
            $table->enum('2004k', [0, 10]);
            $table->enum('2004l', [0, 10]);
            $table->enum('2004m', [0, 20]);
            $table->enum('2004n', [0, 10]);
            $table->enum('2004o', [0, 10]);
            $table->enum('2004p', [0, 10]);
            $table->enum('2005a', [0, 20]);
            $table->enum('2005b', [0, 20]);
            $table->enum('2005c', [0, 20]);
            $table->enum('2005d', [0, 20]);
            $table->enum('2005e', [0, 20]);
            $table->enum('2005f', [0, 20]);
            $table->enum('2005g', [0, 20]);
            $table->enum('2005h', [0, 30]);
            $table->enum('2005i', [0, 30]);

            $table->enum('3001', [5 * 100, 5 * 0]);
            $table->enum('3002', [0, 500]);

            $table->enum('4001', [2 * 100, 2 * 50]);
            $table->enum('4002', [0, 200]);
            $table->enum('4003a', [0, 50]);
            $table->enum('4003b', [0, 50]);
            $table->enum('4003c', [0, 50]);
            $table->enum('4003d', [0, 50]);
            $table->enum('4004a', [0, 40]);
            $table->enum('4004b', [0, 40]);
            $table->enum('4004c', [0, 30]);
            $table->enum('4004d', [0, 30]);
            $table->enum('4004e', [0, 30]);
            $table->enum('4004f', [0, 30]);
            $table->enum('4005', [1 * 100, 1 * 50]);
            $table->enum('4006a', [0, 20]);
            $table->enum('4006b', [0, 20]);
            $table->enum('4006c', [0, 20]);
            $table->enum('4006d', [0, 20]);
            $table->enum('4006e', [0, 20]);

            $table->enum('5001a', [0, 50]);
            $table->enum('5001b', [0, 50]);
            $table->enum('5001c', [0, 50]);
            $table->enum('5001d', [0, 50]);
            $table->enum('5001e', [0, 50]);
            $table->enum('5001f', [0, 50]);
            $table->enum('5001g', [0, 50]);
            $table->enum('5001h', [0, 50]);
            $table->enum('5001i', [0, 50]);
            $table->enum('5001j', [0, 50]);
            $table->enum('5002', [5 * 100, 5 * 0]);

            $table->enum('6001a', [0, 200]);
            $table->enum('6001b', [0, 150]);
            $table->enum('6001c', [0, 150]);
            $table->enum('6002a', [0, 100]);
            $table->enum('6002b', [0, 100]);
            $table->enum('6002c', [0, 100]);
            $table->enum('6002d', [0, 200]);
            $table->enum('6003a', [0, 200]);
            $table->enum('6003b', [0, 200]);
            $table->enum('6004a', [0, 40]);
            $table->enum('6004b', [0, 40]);
            $table->enum('6004c', [0, 40]);
            $table->enum('6004d', [0, 40]);
            $table->enum('6004e', [0, 40]);

            $table->enum('7a', [0, 400]);
            $table->enum('7b', [0, 300]);
            $table->enum('7c', [0, 300]);

            $table->enum('8001a', [0, 140]);
            $table->enum('8001b', [0, 140]);
            $table->enum('8001c', [0, 140]);
            $table->enum('8001d', [0, 140]);
            $table->enum('8001e', [0, 140]);
            $table->enum('8002a', [0, 150]);
            $table->enum('8002b', [0, 150]);

            $table->enum('9001a', [0, 100]);
            $table->enum('9001b', [0, 60]);
            $table->enum('9001c', [0, 80]);
            $table->enum('9001d', [0, 80]);
            $table->enum('9001e', [0, 40]);
            $table->enum('9001f', [0, 40]);
            $table->enum('9002', [3 * 100, 3 * 30, 3 * 0]);
            $table->enum('9003', [3 * 100, 3 * 25]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('rumah_sakit');
    }
};
