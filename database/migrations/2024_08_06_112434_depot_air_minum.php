<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('depot_air_minum', function (Blueprint $table) {
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
            $table->enum('tujuan-ikl', ['Program', 'Perizinan SLHS']);
            $table->date('tanggal-penilaian');
            $table->tinyInteger('skor')->length(3);
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->tinyInteger('u004');
            $table->tinyInteger('u005');
            $table->date('u006');
            $table->string('u007');
            $table->string('u008');

            $table->enum('l001', [0, 3]);
            $table->enum('l002', [0, 1]);
            $table->enum('l003', [0, 1]);

            $table->enum('db001', [0, 1]);
            $table->enum('db002', [0, 1]);
            $table->enum('db003', [0, 1]);
            $table->enum('db004', [0, 1]);
            $table->enum('db005', [0, 1]);
            $table->enum('db006', [0, 1]);
            $table->enum('db007', [0, 1]);
            $table->enum('db008', [0, 1]);
            $table->enum('db009', [0, 1]);
            $table->enum('db010', [0, 2]);
            $table->enum('db011', [0, 2]);
            $table->enum('db012', [0, 1]);
            $table->enum('db013', [0, 1]);
            $table->enum('db014', [0, 1]);

            $table->enum('df001', [0, 1]);
            $table->enum('df002', [0, 1]);
            $table->enum('df003', [0, 1]);
            $table->enum('df004', [0, 1]);
            $table->enum('df005', [0, 1]);
            $table->enum('df006', [0, 1]);
            $table->enum('df007', [0, 1]);
            $table->enum('df008', [0, 1]);
            $table->enum('df009', [0, 1]);
            $table->enum('df010', [0, 1]);
            $table->enum('df011', [0, 1]);
            $table->enum('df012', [0, 1]);
            $table->enum('df013', [0, 1]);
            $table->enum('df014', [0, 1]);
            $table->enum('df015', [0, 1]);
            $table->enum('df016', [0, 1]);
            $table->enum('df017', [0, 3]);
            $table->enum('df018', [0, 3]);
            $table->enum('df019', [0, 2]);
            $table->enum('df020', [0, 1]);
            $table->enum('df021', [0, 2]);
            $table->enum('df022', [0, 2]);
            $table->enum('df023', [0, 2]);
            $table->enum('df024', [0, 2]);
            $table->enum('df025', [0, 1]);
            $table->enum('df026', [0, 2]);
            $table->enum('df027', [0, 1]);
            $table->enum('df028', [0, 1]);
            $table->enum('df029', [0, 1]);
            $table->enum('df030', [0, 3]);
            $table->enum('df031', [0, 1]);
            $table->enum('df032', [0, 3]);
            $table->enum('df033', [0, 3]);
            $table->enum('df034', [0, 1]);
            $table->enum('df035', [0, 2]);
            $table->enum('df036', [0, 2]);
            $table->enum('df037', [0, 2]);

            $table->enum('pp001', [0, 3]);
            $table->enum('pp002', [0, 2]);
            $table->enum('pp003', [0, 3]);
            $table->enum('pp004', [0, 3]);
            $table->enum('pp005', [0, 3]);
            $table->enum('pp006', [0, 3]);
            $table->enum('pp007', [0, 3]);
            $table->enum('pp008', [0, 3]);
            $table->enum('pp009', [0, 1]);
            $table->enum('pp010', [0, 3]);

            $table->enum('p001', [0, 2]);
            $table->enum('p002', [0, 3]);
            $table->enum('p003', [0, 3]);
            $table->enum('p004', [0, 3]);
            $table->enum('p005', [0, 2]);
            $table->enum('p006', [0, 3]);
            $table->enum('p007', [0, 3]);
            $table->enum('p008', [0, 3]);
            $table->enum('p009', [0, 3]);
            $table->enum('p010', [0, 3]);
            $table->enum('p011', [0, 3]);
            $table->enum('p012', [0, 3]);
            $table->enum('p013', [0, 3]);
            $table->enum('p014', [0, 3]);
            $table->enum('p015', [0, 2]);
            $table->enum('p016', [0, 3]);
            $table->enum('p017', [0, 3]);
            $table->enum('p018', [0, 3]);
            $table->enum('p019', [0, 3]);
            $table->enum('p020', [0, 3]);
            $table->enum('p021', [0, 1]);

            $table->enum('ab001', [0, 3]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('depot_air_minum');
    }
};
