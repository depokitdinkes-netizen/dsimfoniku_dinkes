<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Menghapus kolom-kolom yang tidak digunakan lagi
            $table->dropColumn([
                'keterangan_pihak_ketiga_jasa_boga',
                'jumlah_penjamah_pangan',
                'keterangan_pihak_ketiga_pest_control',
                'keterangan_pihak_ketiga_b3',
                'memiliki_alat_rontgen_portable',
                'memiliki_shielding_radiasi'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Menambahkan kembali kolom-kolom yang dihapus
            $table->text('keterangan_pihak_ketiga_jasa_boga')->nullable();
            $table->integer('jumlah_penjamah_pangan')->nullable();
            $table->text('keterangan_pihak_ketiga_pest_control')->nullable();
            $table->text('keterangan_pihak_ketiga_b3')->nullable();
            $table->boolean('memiliki_alat_rontgen_portable')->nullable();
            $table->boolean('memiliki_shielding_radiasi')->nullable();
        });
    }
};