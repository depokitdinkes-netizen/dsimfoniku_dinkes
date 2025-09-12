<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('restoran', function (Blueprint $table) {
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
            $table->enum('u009', ['UMUM', 'HOTEL']);

            $table->enum('l001', [0, 3]);
            $table->enum('l002', [0, 1]);
            $table->enum('l003', [0, 1]);

            $table->enum('p001', [0, 1]);
            $table->enum('p002', [0, 2]);
            $table->enum('p003', [0, 1]);
            $table->enum('p004', [0, 3]);
            $table->enum('p005', [0, 2]);
            $table->enum('p006', [0, 1]);
            $table->enum('p007', [0, 1]);
            $table->enum('p008', [0, 1]);
            $table->enum('p009', [0, 1]);
            $table->enum('p010', [0, 1]);
            $table->enum('p011', [0, 1]);
            $table->enum('p012', [0, 1]);
            $table->enum('p013', [0, 1]);
            $table->enum('p014', [0, 1]);
            $table->enum('p015', [0, 2]);
            $table->enum('p016', [0, 2]);
            $table->enum('p017', [0, 2]);
            $table->enum('p018', [0, 1]);
            $table->enum('p019', [0, 2]);
            $table->enum('p020', [0, 2]);
            $table->enum('p021', [0, 2]);
            $table->enum('p022', [0, 1]);
            $table->enum('p023', [0, 1]);
            $table->enum('p024', [0, 1])->default(0);
            $table->enum('p025', [0, 2]);
            $table->enum('p026', [0, 3]);

            $table->enum('fk001', [0, 1]);
            $table->enum('fk002', [0, 1]);
            $table->enum('fk003', [0, 1]);
            $table->enum('fk004', [0, 2]);
            $table->enum('fk005', [0, 2]);
            $table->enum('fk006', [0, 1]);

            $table->enum('apbb001', [0, 1]);
            $table->enum('apbb002', [0, 2]);
            $table->enum('apbb003', [0, 2]);
            $table->enum('apbb004', [0, 2]);
            $table->enum('apbb005', [0, 2]);
            $table->enum('apbb006', [0, 2]);
            $table->enum('apbb007', [0, 2]);
            $table->enum('apbb008', [0, 2]);
            $table->enum('apbb009', [0, 2]);
            $table->enum('apbb010', [0, 2]);
            $table->enum('apbb011', [0, 1]);
            $table->enum('apbb012', [0, 2]);
            $table->enum('apbb013', [0, 2]);
            $table->enum('apbb014', [0, 2]);
            $table->enum('apbb015', [0, 1])->default(0);

            $table->enum('ap001', [0, 2]);
            $table->enum('ap002', [0, 2]);
            $table->enum('ap003', [0, 2]);
            $table->enum('ap004', [0, 2]);
            $table->enum('ap005', [0, 2]);
            $table->enum('ap006', [0, 1]);
            $table->enum('ap007', [0, 1]);
            $table->enum('ap008', [0, 1]);
            $table->enum('ap009', [0, 1]);
            $table->enum('ap010', [0, 2]);
            $table->enum('ap011', [0, 1]);
            $table->enum('ap012', [0, 2]);
            $table->enum('ap013', [0, 1]);
            $table->enum('ap014', [0, 2]);
            $table->enum('ap015', [0, 1]);
            $table->enum('ap016', [0, 1]);
            $table->enum('ap017', [0, 1]);
            $table->enum('ap018', [0, 1]);
            $table->enum('ap019', [0, 1]);
            $table->enum('ap020', [0, 1]);
            $table->enum('ap021', [0, 1]);
            $table->enum('ap022', [0, 1]);
            $table->enum('ap023', [0, 1]);
            $table->enum('ap024', [0, 1]);
            $table->enum('ap025', [0, 1]);
            $table->enum('ap026', [0, 1]);
            $table->enum('ap027', [0, 3]);
            $table->enum('ap028', [0, 3]);
            $table->enum('ap029', [0, 2]);

            $table->enum('ap030', [0, 2]);
            $table->enum('ap031', [0, 2]);
            $table->enum('ap032', [0, 2]);
            $table->enum('ap033', [0, 1]);
            $table->enum('ap034', [0, 2]);
            $table->enum('ap035', [0, 2]);
            $table->enum('ap036', [0, 2]);
            $table->enum('ap037', [0, 3]);
            $table->enum('ap038', [0, 2]);
            $table->enum('ap039', [0, 2]);
            $table->enum('ap040', [0, 2]);

            $table->enum('pk001', [0, 1]);
            $table->enum('pk002', [0, 2]);
            $table->enum('pk003', [0, 1]);
            $table->enum('pk004', [0, 1]);
            $table->enum('pk005', [0, 1]);
            $table->enum('pk006', [0, 2]);

            $table->enum('bp001', [0, 2]);
            $table->enum('bp002', [0, 2]);
            $table->enum('bp003', [0, 2]);

            $table->enum('pc001', [0, 1]);
            $table->enum('pc002', [0, 1]);
            $table->enum('pc003', [0, 1]);
            $table->enum('pc004', [0, 1]);
            $table->enum('pc005', [0, 2]);
            $table->enum('pc006', [0, 2]);
            $table->enum('pc007', [0, 1]);
            $table->enum('pc008', [0, 1]);
            $table->enum('pc009', [0, 1]);
            $table->enum('pc010', [0, 1]);
            $table->enum('pc011', [0, 1]);
            $table->enum('pc012', [0, 1]);
            $table->enum('pc013', [0, 1]);
            $table->enum('pc014', [0, 2]);

            $table->enum('pg001', [0, 2]);
            $table->enum('pg002', [0, 2]);
            $table->enum('pg003', [0, 2]);
            $table->enum('pg004', [0, 2]);
            $table->enum('pg005', [0, 2]);
            $table->enum('pg006', [0, 2]);
            $table->enum('pg007', [0, 1]);
            $table->enum('pg008', [0, 1]);
            $table->enum('pg009', [0, 2]);
            $table->enum('pg010', [0, 2]);
            $table->enum('pg011', [0, 2]);
            $table->enum('pg012', [0, 2]);
            $table->enum('pg013', [0, 3]);
            $table->enum('pg014', [0, 3]);
            $table->enum('pg015', [0, 2]);
            $table->enum('pg016', [0, 3]);
            $table->enum('pg017', [0, 3]);
            $table->enum('pg018', [0, 2]);
            $table->enum('pg019', [0, 3]);
            $table->enum('pg020', [0, 3]);
            $table->enum('pg021', [0, 3]);
            $table->enum('pg022', [0, 3]);
            $table->enum('pg023', [0, 3]);
            $table->enum('pg024', [0, 3]);
            $table->enum('pg025', [0, 3]);
            $table->enum('pg026', [0, 3]);
            $table->enum('pg027', [0, 3]);
            $table->enum('pg028', [0, 3]);
            $table->enum('pg029', [0, 1]);
            $table->enum('pg030', [0, 2]);
            $table->enum('pg031', [0, 2]);
            $table->enum('pg032', [0, 1]);
            $table->enum('pg033', [0, 1]);
            $table->enum('pg034', [0, 3]);
            $table->enum('pg035', [0, 1]);
            $table->enum('pg036', [0, 1]);
            $table->enum('pg037', [0, 2]);
            $table->enum('pg038', [0, 3]);
            $table->enum('pg039', [0, 3]);
            $table->enum('pg040', [0, 3]);
            $table->enum('pg041', [0, 2]);
            $table->enum('pg042', [0, 2]);
            $table->enum('pg043', [0, 2]);
            $table->enum('pg044', [0, 3]);
            $table->enum('pg045', [0, 3]);

            $table->enum('fh001', [0, 3]);
            $table->enum('fh002', [0, 2]);
            $table->enum('fh003', [0, 3]);
            $table->enum('fh004', [0, 3]);
            $table->enum('fh005', [0, 2]);
            $table->enum('fh006', [0, 3]);
            $table->enum('fh007', [0, 1]);
            $table->enum('fh008', [0, 3]);
            $table->enum('fh009', [0, 3]);
            $table->enum('fh010', [0, 2]);
            $table->enum('fh011', [0, 2]);
            $table->enum('fh012', [0, 1]);

            $table->enum('pr001', [0, 2]);
            $table->enum('pr002', [0, 3]);
            $table->enum('pr003', [0, 3]);
            $table->enum('pr004', [0, 3]);
            $table->enum('pr005', [0, 3]);
            $table->enum('pr006', [0, 2]);
            $table->enum('pr007', [0, 3]);
            $table->enum('pr008', [0, 3]);
            $table->enum('pr009', [0, 2]);
            $table->enum('pr010', [0, 2]);
            $table->enum('pr011', [0, 2]);
            $table->enum('pr012', [0, 2]);
            $table->enum('pr013', [0, 2]);

            $table->enum('pm001', [0, 3]);
            $table->enum('pm002', [0, 2]);
            $table->enum('pm003', [0, 3]);
            $table->enum('pm004', [0, 2]);
            $table->enum('pm005', [0, 2]);
            $table->enum('pm006', [0, 2]);
            $table->enum('pm007', [0, 2]);
            $table->enum('pm008', [0, 2]);

            $table->enum('pp001', [0, 3]);
            $table->enum('pp002', [0, 3]);
            $table->enum('pp003', [0, 2]);
            $table->enum('pp004', [0, 1]);

            $table->enum('pp005', [0, 3]);
            $table->enum('pp006', [0, 2]);
            $table->enum('pp007', [0, 3]);
            $table->enum('pp008', [0, 3]);
            $table->enum('pp009', [0, 3]);
            $table->enum('pp010', [0, 3]);

            $table->enum('pp011', [0, 3]);
            $table->enum('pp012', [0, 3]);
            $table->enum('pp013', [0, 3]);
            $table->enum('pp014', [0, 3]);
            $table->enum('pp015', [0, 3]);
            $table->enum('pp016', [0, 3]);
            $table->enum('pp017', [0, 3]);
            $table->enum('pp018', [0, 3]);
            $table->enum('pp019', [0, 3]);
            $table->enum('pp020', [0, 3]);
            $table->enum('pp021', [0, 3]);

            $table->enum('drh001', [0, 1])->default(0);
            $table->enum('drh002', [0, 2])->default(0);
            $table->enum('drh003', [0, 2])->default(0);
            $table->enum('drh004', [0, 1])->default(0);
            $table->enum('drh005', [0, 2])->default(0);
            $table->enum('drh006', [0, 1])->default(0);
            $table->enum('drh007', [0, 3]);
            $table->enum('drh008', [0, 2]);
            $table->enum('drh009', [0, 1]);

            $table->enum('rp001', [0, 2]);
            $table->enum('rp002', [0, 3]);
            $table->enum('rp003', [0, 2]);
            $table->enum('rp004', [0, 2])->default(0);
            $table->enum('rp005', [0, 2])->default(0);

            $table->enum('kk001', [0, 1]);
            $table->enum('kk002', [0, 1]);
            $table->enum('kk003', [0, 1]);
            $table->enum('kk004', [0, 1]);
            $table->enum('kk005', [0, 1]);
            $table->enum('kk006', [0, 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('restoran');
    }
};
