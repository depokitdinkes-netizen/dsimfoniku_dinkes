<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('gerai_kantin', function (Blueprint $table) {
            $table->foreignId('id-kantin')->constrained('kantin', 'id')->cascadeOnDelete();
            $table->id();
            $table->string('subjek');
            $table->string('pengelola');
            $table->string('kontak');
            $table->enum('status-operasi', [0, 1]);
            $table->tinyInteger('skor')->length(3);
            $table->text('catatan-lain')->nullable();
            $table->text('rencana-tindak-lanjut')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->enum('pp001', [0, 2]);
            $table->enum('pp002', [0, 2]);
            $table->enum('pp003', [0, 3]);
            $table->enum('pp004', [0, 3]);
            $table->enum('pp005', [0, 3]);
            $table->enum('pp006', [0, 2]);
            $table->enum('pp007', [0, 2]);
            $table->enum('pp008', [0, 2]);
            $table->enum('pp009', [0, 2]);
            $table->enum('pp010', [0, 3]);
            $table->enum('pp011', [0, 3]);
            $table->enum('pp012', [0, 1]);
            $table->enum('pp013', [0, 1]);
            $table->enum('pp014', [0, 1]);
            $table->enum('pp015', [0, 1]);
            $table->enum('pp016', [0, 1]);
            $table->enum('pp017', [0, 1]);

            $table->enum('ppbp001', [0, 1]);
            $table->enum('ppbp002', [0, 1]);
            $table->enum('ppbp003', [0, 2]);
            $table->enum('ppbp004', [0, 2]);
            $table->enum('ppbp005', [0, 2]);
            $table->enum('ppbp006', [0, 2]);
            $table->enum('ppbp007', [0, 2]);
            $table->enum('ppbp008', [0, 2]);
            $table->enum('ppbp009', [0, 2]);
            $table->enum('ppbp010', [0, 2]);
            $table->enum('ppbp011', [0, 2]);
            $table->enum('ppbp012', [0, 2]);
            $table->enum('ppbp013', [0, 2]);

            $table->enum('pppp001', [0, 2]);
            $table->enum('pppp002', [0, 2]);
            $table->enum('pppp003', [0, 2]);
            $table->enum('pppp004', [0, 3]);
            $table->enum('pppp005', [0, 3]);
            $table->enum('pppp006', [0, 2]);
            $table->enum('pppp007', [0, 3]);
            $table->enum('pppp008', [0, 3]);
            $table->enum('pppp009', [0, 3]);
            $table->enum('pppp010', [0, 3]);
            $table->enum('pppp011', [0, 3]);
            $table->enum('pppp012', [0, 3]);
            $table->enum('pppp013', [0, 3]);
            $table->enum('pppp014', [0, 3]);
            $table->enum('pppp015', [0, 3]);
            $table->enum('pppp016', [0, 3]);
            $table->enum('pppp017', [0, 3]);
            $table->enum('pppp018', [0, 3]);
            $table->enum('pppp019', [0, 2]);
            $table->enum('pppp020', [0, 3]);

            $table->enum('pppl001', [0, 2]);
            $table->enum('pppl002', [0, 3]);
            $table->enum('pppl003', [0, 3]);
            $table->enum('pppl004', [0, 3]);
            $table->enum('pppl005', [0, 2]);
            $table->enum('pppl006', [0, 3]);
            $table->enum('pppl007', [0, 3]);
            $table->enum('pppl008', [0, 3]);
            $table->enum('pppl009', [0, 3]);

            $table->enum('ppm001', [0, 3]);
            $table->enum('ppm002', [0, 3]);
            $table->enum('ppm003', [0, 3]);
            $table->enum('ppm004', [0, 3]);
            $table->enum('ppm005', [0, 3]);
            $table->enum('ppm006', [0, 3]);
            $table->enum('ppm007', [0, 3]);
            $table->enum('ppm008', [0, 3]);
            $table->enum('ppm009', [0, 3]);

            $table->enum('pmpm001', [0, 3]);
            $table->enum('pmpm002', [0, 3]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('gerai_kantin');
    }
};
