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
        // Daftar tabel IKL yang akan diupdate
        $tables = [
            'restoran',
            'jasa_boga_katering',
            'tpp_tertentu',
            'depot_air_minum',
            'gerai_pangan_jajanan',
            'gerai_jajanan_keliling',
            'tempat_olahraga',
            'renang_pemandian',
            'tempat_rekreasi',
            'akomodasi',
            'akomodasi_lain',
            'sumur_gali',
            'sumur_bor_pompa',
            'perpipaan',
            'perpipaan_non_pdam',
            'perlindungan_mata_air',
            'rumah_makan',
            'kantin',
            'gerai_kantin',
            'sekolah',
            'puskesmas',
            'rumah_sakit',
            'pasar',
            'tempat_ibadah',
            'penyimpanan_air_hujan'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Cek apakah kolom sudah ada sebelum menambahkan
                    if (!Schema::hasColumn($tableName, 'dokumen_slhs')) {
                        $table->string('dokumen_slhs')->nullable();
                    }
                    
                    if (!Schema::hasColumn($tableName, 'slhs_issued_date')) {
                        $table->date('slhs_issued_date')->nullable();
                    }
                    
                    if (!Schema::hasColumn($tableName, 'slhs_expire_date')) {
                        $table->date('slhs_expire_date')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Daftar tabel IKL yang akan di-rollback
        $tables = [
            'restoran',
            'jasa_boga_katering',
            'tpp_tertentu',
            'depot_air_minum',
            'gerai_pangan_jajanan',
            'gerai_jajanan_keliling',
            'tempat_olahraga',
            'renang_pemandian',
            'tempat_rekreasi',
            'akomodasi',
            'akomodasi_lain',
            'sumur_gali',
            'sumur_bor_pompa',
            'perpipaan',
            'perpipaan_non_pdam',
            'perlindungan_mata_air',
            'rumah_makan',
            'kantin',
            'gerai_kantin',
            'sekolah',
            'puskesmas',
            'rumah_sakit',
            'pasar',
            'tempat_ibadah',
            'penyimpanan_air_hujan'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Hanya hapus kolom jika ada
                    $columnsToDelete = [];
                    
                    if (Schema::hasColumn($tableName, 'dokumen_slhs')) {
                        $columnsToDelete[] = 'dokumen_slhs';
                    }
                    
                    if (Schema::hasColumn($tableName, 'slhs_issued_date')) {
                        $columnsToDelete[] = 'slhs_issued_date';
                    }
                    
                    if (Schema::hasColumn($tableName, 'slhs_expire_date')) {
                        $columnsToDelete[] = 'slhs_expire_date';
                    }
                    
                    if (!empty($columnsToDelete)) {
                        $table->dropColumn($columnsToDelete);
                    }
                });
            }
        }
    }
};
