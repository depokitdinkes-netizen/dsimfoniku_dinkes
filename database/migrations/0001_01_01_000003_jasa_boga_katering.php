<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('jasa_boga_katering', function (Blueprint $table) {
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
            $table->bigInteger('u006')->nullable();
            $table->enum('u009', ['A', 'B', 'C']);

            $table->enum('l001', [0, 3]);
            $table->enum('l002', [0, 1]);
            $table->enum('l003', [0, 1]);

            $table->enum('b001', [0, 1]);
            $table->enum('b002', [0, 1]);
            $table->enum('b003', [0, 1]);
            $table->enum('b004', [0, 1]);
            $table->enum('b005', [0, 1]);
            $table->enum('b006', [0, 1]);
            $table->enum('b007', [0, 1]);
            $table->enum('b008', [0, 1]);
            $table->enum('b009', [0, 1]);
            $table->enum('b010', [0, 1]);
            $table->enum('b011', [0, 1]);
            $table->enum('b012', [0, 1]);
            $table->enum('b013', [0, 1])->default(0);
            $table->enum('b014', [0, 1]);
            $table->enum('b015', [0, 1]);
            $table->enum('b016', [0, 1]);
            $table->enum('b017', [0, 1]);
            $table->enum('b018', [0, 1]);
            $table->enum('b019', [0, 1]);
            $table->enum('b020', [0, 1]);
            $table->enum('b021', [0, 1])->default(0);
            $table->enum('b022', [0, 1]);
            $table->enum('b023', [0, 1]);
            $table->enum('b024', [0, 1]);
            $table->enum('b025', [0, 1]);
            $table->enum('b026', [0, 1])->default(0);
            $table->enum('b027', [0, 1])->default(0);
            $table->enum('b028', [0, 1])->default(0);
            $table->enum('b029', [0, 2])->default(0);
            $table->enum('b030', [0, 2])->default(0);
            $table->enum('b031', [0, 1])->default(0);
            $table->enum('b032', [0, 1])->default(0);
            $table->enum('b033', [0, 1])->default(0);

            $table->enum('p001', [0, 3]);
            $table->enum('p002', [0, 3]);

            $table->enum('f001', [0, 1])->default(0);
            $table->enum('f002', [0, 1])->default(0);
            $table->enum('f003', [0, 1])->default(0);
            $table->enum('f004', [0, 2])->default(0);

            $table->enum('eb001', [0, 1]);
            $table->enum('eb002', [0, 2]);
            $table->enum('eb003', [0, 2])->default(0);
            $table->enum('eb004', [0, 2])->default(0);

            $table->enum('fb001', [0, 2]);
            $table->enum('fb002', [0, 2]);
            $table->enum('fb003', [0, 2]);
            $table->enum('fb004', [0, 2]);
            $table->enum('fb005', [0, 2]);
            $table->enum('fb006', [0, 2]);
            $table->enum('fb007', [0, 1]);
            $table->enum('fb008', [0, 2]);
            $table->enum('fb009', [0, 2]);
            $table->enum('fb0010', [0, 2]);

            $table->enum('adu001', [0, 1]);
            $table->enum('adu002', [0, 1]);
            $table->enum('adu003', [0, 1]);
            $table->enum('adu004', [0, 1]);
            $table->enum('adu005', [0, 1]);
            $table->enum('adu006', [0, 1])->default(0);
            $table->enum('adu007', [0, 1]);
            $table->enum('adu008', [0, 1]);
            $table->enum('adu009', [0, 1]);
            $table->enum('adu010', [0, 2]);
            $table->enum('adu011', [0, 1]);
            $table->enum('adu012', [0, 2]);
            $table->enum('adu013', [0, 1]);
            $table->enum('adu014', [0, 2]);
            $table->enum('adu015', [0, 1]);
            $table->enum('adu016', [0, 1]);
            $table->enum('adu017', [0, 1]);
            $table->enum('adu018', [0, 1]);
            $table->enum('adu019', [0, 1]);
            $table->enum('adu020', [0, 1]);
            $table->enum('adu021', [0, 1]);
            $table->enum('adu022', [0, 1]);
            $table->enum('adu023', [0, 3]);
            $table->enum('adu024', [0, 3]);
            $table->enum('adu025', [0, 2]);

            $table->enum('pbp001', [0, 2]);
            $table->enum('pbp002', [0, 2]);
            $table->enum('pbp003', [0, 2]);
            $table->enum('pbp004', [0, 1]);
            $table->enum('pbp005', [0, 1]);
            $table->enum('pbp006', [0, 1]);
            $table->enum('pbp007', [0, 2]);
            $table->enum('pbp008', [0, 2]);
            $table->enum('pbp009', [0, 2]);
            $table->enum('pbp010', [0, 3]);
            $table->enum('pbp011', [0, 2])->default(0);
            $table->enum('pbp012', [0, 2]);
            $table->enum('pbp013', [0, 1])->default(0);
            $table->enum('pbp014', [0, 2]);
            $table->enum('pbp015', [0, 1])->default(0);

            $table->enum('pbp016', [0, 1]);
            $table->enum('pbp017', [0, 1]);
            $table->enum('pbp018', [0, 1]);
            $table->enum('pbp019', [0, 1]);
            $table->enum('pbp020', [0, 2]);

            $table->enum('pbk001', [0, 2]);
            $table->enum('pbk002', [0, 2])->default(0);
            $table->enum('pbk003', [0, 2]);

            $table->enum('ap001', [0, 1]);
            $table->enum('ap002', [0, 1]);
            $table->enum('ap003', [0, 1]);
            $table->enum('ap004', [0, 1]);
            $table->enum('ap005', [0, 2]);
            $table->enum('ap006', [0, 2]);
            $table->enum('ap007', [0, 1]);
            $table->enum('ap008', [0, 1]);
            $table->enum('ap009', [0, 1]);
            $table->enum('ap010', [0, 1]);
            $table->enum('ap011', [0, 1]);
            $table->enum('ap012', [0, 1]);
            $table->enum('ap013', [0, 2]);

            $table->enum('ppp001', [0, 1])->default(0);
            $table->enum('ppp002', [0, 1])->default(0);
            $table->enum('ppp003', [0, 1])->default(0);
            $table->enum('ppp004', [0, 2]);
            $table->enum('ppp005', [0, 2]);
            $table->enum('ppp006', [0, 2]);
            $table->enum('ppp007', [0, 2]);
            $table->enum('ppp008', [0, 2]);
            $table->enum('ppp009', [0, 2]);
            $table->enum('ppp010', [0, 1])->default(0);
            $table->enum('ppp011', [0, 1]);
            $table->enum('ppp012', [0, 2]);
            $table->enum('ppp013', [0, 2]);
            $table->enum('ppp014', [0, 2]);
            $table->enum('ppp015', [0, 2]);
            $table->enum('ppp016', [0, 3]);
            $table->enum('ppp017', [0, 2]);
            $table->enum('ppp018', [0, 3]);
            $table->enum('ppp019', [0, 2]);
            $table->enum('ppp020', [0, 3]);
            $table->enum('ppp021', [0, 3]);
            $table->enum('ppp022', [0, 2]);
            $table->enum('ppp023', [0, 3]);
            $table->enum('ppp024', [0, 3]);
            $table->enum('ppp025', [0, 3]);
            $table->enum('ppp026', [0, 3]);
            $table->enum('ppp027', [0, 3]);
            $table->enum('ppp028', [0, 3]);
            $table->enum('ppp029', [0, 3]);
            $table->enum('ppp030', [0, 3]);
            $table->enum('ppp031', [0, 3]);
            $table->enum('ppp032', [0, 3]);
            $table->enum('ppp033', [0, 1]);
            $table->enum('ppp034', [0, 2]);
            $table->enum('ppp035', [0, 2]);
            $table->enum('ppp036', [0, 2]);
            $table->enum('ppp037', [0, 1]);
            $table->enum('ppp038', [0, 1]);
            $table->enum('ppp039', [0, 3]);
            $table->enum('ppp040', [0, 1]);
            $table->enum('ppp041', [0, 1]);
            $table->enum('ppp042', [0, 2]);
            $table->enum('ppp043', [0, 3]);
            $table->enum('ppp044', [0, 3]);
            $table->enum('ppp045', [0, 3]);
            $table->enum('ppp046', [0, 2])->default(0);
            $table->enum('ppp047', [0, 2]);
            $table->enum('ppp048', [0, 2]);
            $table->enum('ppp049', [0, 3]);
            $table->enum('ppp050', [0, 3]);
            $table->enum('ppp051', [0, 3]);

            $table->enum('fhsp001', [0, 3]);
            $table->enum('fhsp002', [0, 2]);
            $table->enum('fhsp003', [0, 3]);
            $table->enum('fhsp004', [0, 3]);
            $table->enum('fhsp005', [0, 2]);
            $table->enum('fhsp006', [0, 1]);
            $table->enum('fhsp007', [0, 1]);
            $table->enum('fhsp008', [0, 3]);
            $table->enum('fhsp009', [0, 1]);
            $table->enum('fhsp010', [0, 1]);
            $table->enum('fhsp011', [0, 1]);
            $table->enum('fhsp012', [0, 1]);
            $table->enum('fhsp013', [0, 3]);
            $table->enum('fhsp014', [0, 3]);
            $table->enum('fhsp015', [0, 1]);
            $table->enum('fhsp016', [0, 2]);
            $table->enum('fhsp017', [0, 3]);
            $table->enum('fhsp018', [0, 2]);
            $table->enum('fhsp019', [0, 2]);
            $table->enum('fhsp020', [0, 1])->default(0);

            $table->enum('pl001', [0, 2]);
            $table->enum('pl002', [0, 3]);
            $table->enum('pl003', [0, 3]);
            $table->enum('pl004', [0, 3]);
            $table->enum('pl005', [0, 3]);
            $table->enum('pl006', [0, 2]);
            $table->enum('pl007', [0, 3]);
            $table->enum('pl008', [0, 3]);
            $table->enum('pl009', [0, 2])->default(0);
            $table->enum('pl010', [0, 2]);
            $table->enum('pl011', [0, 2]);
            $table->enum('pl012', [0, 2]);

            $table->enum('ppm001', [0, 3]);
            $table->enum('ppm002', [0, 2]);
            $table->enum('ppm003', [0, 2]);
            $table->enum('ppm004', [0, 3]);
            $table->enum('ppm005', [0, 2]);
            $table->enum('ppm006', [0, 2]);
            $table->enum('ppm007', [0, 2])->default(0);
            $table->enum('ppm008', [0, 2]);
            $table->enum('ppm009', [0, 2])->default(0);

            $table->enum('ppm010', [0, 3]);
            $table->enum('ppm011', [0, 3]);
            $table->enum('ppm012', [0, 2]);
            $table->enum('ppm013', [0, 1]);

            $table->enum('ppm014', [0, 3]);
            $table->enum('ppm015', [0, 3]);
            $table->enum('ppm016', [0, 2])->default(0);
            $table->enum('ppm017', [0, 2])->default(0);
            $table->enum('ppm018', [0, 2])->default(0);

            $table->enum('dr001', [0, 1])->default(0);
            $table->enum('dr002', [0, 1])->default(0);
            $table->enum('dr003', [0, 2])->default(0);
            $table->enum('dr004', [0, 2])->default(0);
            $table->enum('dr005', [0, 1])->default(0);
            $table->enum('dr006', [0, 2])->default(0);
            $table->enum('dr007', [0, 1])->default(0);
            $table->enum('dr008', [0, 3]);
            $table->enum('dr009', [0, 2]);

            $table->enum('rp001', [0, 2]);
            $table->enum('rp002', [0, 3])->default(0);
            $table->enum('rp003', [0, 3])->default(0);
            $table->enum('rp004', [0, 2])->default(0);
            $table->enum('rp005', [0, 3])->default(0);
            $table->enum('rp006', [0, 2])->default(0);
            $table->enum('rp007', [0, 2]);

            $table->enum('kkl001', [0, 1]);
            $table->enum('kkl002', [0, 1]);
            $table->enum('kkl003', [0, 1]);
            $table->enum('kkl004', [0, 1]);
            $table->enum('kkl005', [0, 1]);
            $table->enum('kkl006', [0, 1])->default(0);
            $table->enum('kkl007', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('jasa_boga_katering');
    }
};
