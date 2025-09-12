<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('gerai_jajanan_keliling', function (Blueprint $table) {
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
            $table->enum('u003', ['Pikulan', 'Gerobak', 'Becak/roda tiga', 'Sepeda', 'Motor', 'Mobil']);
            $table->time('u005a');
            $table->time('u005b');
            $table->string('u006');
            $table->bigInteger('u007')->nullable();
            $table->string('u010');
            $table->enum('u011', ['A1', 'A2']);

            $table->enum('au001', [0, 2]);
            $table->enum('au002', [0, 3]);

            $table->enum('bp001', [0, 3]);
            $table->enum('bp002', [0, 3]);
            $table->enum('bp003', [0, 3]);
            $table->enum('bp004', [0, 3]);
            $table->enum('bp005', [0, 3]);
            $table->enum('bp006', [0, 3]);
            $table->enum('bp007', [0, 2]);
            $table->enum('bp008', [0, 3]);
            $table->enum('bp009', [0, 3]);
            $table->enum('bp010', [0, 2]);
            $table->enum('bp011', [0, 3]);
            $table->enum('bp012', [0, 2]);
            $table->enum('bp013', [0, 2]);
            $table->enum('bp014', [0, 2]);

            $table->enum('ca001', [0, 2]);
            $table->enum('ca002', [0, 1]);
            $table->enum('ca003', [0, 2]);
            $table->enum('ca004', [0, 2]);
            $table->enum('ca005', [0, 3]);
            $table->enum('ca006', [0, 3]);
            $table->enum('ca007', [0, 3]);
            $table->enum('ca008', [0, 3]);
            $table->enum('ca009', [0, 3]);

            $table->enum('dm001', [0, 2]);
            $table->enum('dm002', [0, 3]);
            $table->enum('dm003', [0, 3]);
            $table->enum('dm004', [0, 3]);

            $table->enum('ep001', [0, 3]);
            $table->enum('ep002', [0, 2]);
            $table->enum('ep003', [0, 2]);
            $table->enum('ep004', [0, 3]);
            $table->enum('ep005', [0, 2]);
            $table->enum('ep006', [0, 2])->nullable()->default(0);
            $table->enum('ep007', [0, 2])->nullable()->default(0);
            $table->enum('ep008', [0, 2]);
            $table->enum('ep009', [0, 3])->nullable()->default(0);
            $table->enum('ep010', [0, 3]);
            $table->enum('ep011', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('gerai_jajanan_keliling');
    }
};
