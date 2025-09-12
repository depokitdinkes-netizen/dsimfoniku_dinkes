<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('rumah_makan', function (Blueprint $table) {
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
            $table->enum('u009', ['A1', 'A2']);

            $table->enum('tpp001', [0, 3]);
            $table->enum('tpp002', [0, 1]);
            $table->enum('tpp003', [0, 1]);
            $table->enum('tpp004', [0, 2])->default(0);

            $table->enum('pk001', [0, 1]);
            $table->enum('pk002', [0, 1]);
            $table->enum('pk003', [0, 1]);
            $table->enum('pk004', [0, 1]);
            $table->enum('pk005', [0, 1]);
            $table->enum('pk006', [0, 2]);
            $table->enum('pk007', [0, 3]);
            $table->enum('pk008', [0, 1]);
            $table->enum('pk009', [0, 1]);
            $table->enum('pk010', [0, 3]);
            $table->enum('pk011', [0, 2]);
            $table->enum('pk012', [0, 3]);
            $table->enum('pk013', [0, 3]);
            $table->enum('pk014', [0, 3]);
            $table->enum('pk015', [0, 3]);
            $table->enum('pk016', [0, 3]);
            $table->enum('pk017', [0, 3]);
            $table->enum('pk018', [0, 3]);
            $table->enum('pk019', [0, 3]);

            $table->enum('um001', [0, 2]);
            $table->enum('um002', [0, 2]);
            $table->enum('um003', [0, 2]);
            $table->enum('um004', [0, 3]);
            $table->enum('um005', [0, 3]);
            $table->enum('um006', [0, 3]);
            $table->enum('um007', [0, 3]);
            $table->enum('um008', [0, 2]);
            $table->enum('um009', [0, 2]);
            $table->enum('um010', [0, 2]);
            $table->enum('um011', [0, 2])->default(0);;
            $table->enum('um012', [0, 3]);
            $table->enum('um013', [0, 3]);
            $table->enum('um014', [0, 1]);
            $table->enum('um015', [0, 1]);
            $table->enum('um016', [0, 1])->default(0);;
            $table->enum('um017', [0, 1])->default(0);;
            $table->enum('um018', [0, 1])->default(0);;
            $table->enum('um019', [0, 1])->default(0);;

            $table->enum('bpp001', [0, 1]);
            $table->enum('bpp002', [0, 1]);
            $table->enum('bpp003', [0, 2]);
            $table->enum('bpp004', [0, 2]);
            $table->enum('bpp005', [0, 2]);
            $table->enum('bpp006', [0, 2]);
            $table->enum('bpp007', [0, 2])->default(0);;
            $table->enum('bpp008', [0, 2])->default(0);;
            $table->enum('bpp009', [0, 2])->default(0);;
            $table->enum('bpp010', [0, 2]);
            $table->enum('bpp011', [0, 2]);
            $table->enum('bpp012', [0, 2]);
            $table->enum('bpp013', [0, 2]);

            $table->enum('cpp001', [0, 2]);
            $table->enum('cpp002', [0, 2]);
            $table->enum('cpp003', [0, 2]);
            $table->enum('cpp004', [0, 3]);
            $table->enum('cpp005', [0, 3]);
            $table->enum('cpp006', [0, 2]);
            $table->enum('cpp007', [0, 3]);
            $table->enum('cpp008', [0, 3]);
            $table->enum('cpp009', [0, 3]);
            $table->enum('cpp010', [0, 3]);
            $table->enum('cpp011', [0, 3]);
            $table->enum('cpp012', [0, 3]);
            $table->enum('cpp013', [0, 3]);
            $table->enum('cpp014', [0, 3]);
            $table->enum('cpp015', [0, 3]);
            $table->enum('cpp016', [0, 3]);
            $table->enum('cpp017', [0, 3]);
            $table->enum('cpp018', [0, 3]);
            $table->enum('cpp019', [0, 2]);
            $table->enum('cpp020', [0, 3]);

            $table->enum('pl001', [0, 2]);
            $table->enum('pl002', [0, 3]);
            $table->enum('pl003', [0, 3]);
            $table->enum('pl004', [0, 3]);
            $table->enum('pl005', [0, 2]);
            $table->enum('pl006', [0, 3]);
            $table->enum('pl007', [0, 3]);
            $table->enum('pl008', [0, 2]);
            $table->enum('pl009', [0, 2]);

            $table->enum('pm001', [0, 3]);
            $table->enum('pm002', [0, 3]);
            $table->enum('pm003', [0, 3]);
            $table->enum('pm004', [0, 3]);
            $table->enum('pm005', [0, 3]);
            $table->enum('pm006', [0, 3]);
            $table->enum('pm007', [0, 3]);
            $table->enum('pm008', [0, 3]);
            $table->enum('pm009', [0, 3]);
            $table->enum('pm010', [0, 3]);
            $table->enum('pm011', [0, 3]);
            $table->enum('pm012', [0, 3]);
            $table->enum('pm013', [0, 3]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('rumah_makan');
    }
};
