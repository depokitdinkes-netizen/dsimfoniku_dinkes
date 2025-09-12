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
        // Daftar semua tabel inspeksi yang perlu ditambahkan kolom user_id
        $tables = [
            'restoran',
            'jasa_boga_katering',
            'rumah_makan',
            'kantin',
            'gerai_kantin',
            'depot_air_minum',
            'sumur_gali',
            'sumur_bor_pompa',
            'perpipaan',
            'perpipaan_non_pdam',
            'perlindungan_mata_air',
            'penyimpanan_air_hujan',
            'gerai_pangan_jajanan',
            'gerai_jajanan_keliling',
            'sekolah',
            'rumah_sakit',
            'puskesmas',
            'tempat_rekreasi',
            'renang_pemandian',
            'akomodasi',
            'akomodasi_lain',
            'tempat_olahraga',
            'tempat_ibadah'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'restoran',
            'jasa_boga_katering',
            'rumah_makan',
            'kantin',
            'gerai_kantin',
            'depot_air_minum',
            'sumur_gali',
            'sumur_bor_pompa',
            'perpipaan',
            'perpipaan_non_pdam',
            'perlindungan_mata_air',
            'penyimpanan_air_hujan',
            'gerai_pangan_jajanan',
            'gerai_jajanan_keliling',
            'sekolah',
            'rumah_sakit',
            'puskesmas',
            'tempat_rekreasi',
            'renang_pemandian',
            'akomodasi',
            'akomodasi_lain',
            'tempat_olahraga',
            'tempat_ibadah'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                });
            }
        }
    }
};
