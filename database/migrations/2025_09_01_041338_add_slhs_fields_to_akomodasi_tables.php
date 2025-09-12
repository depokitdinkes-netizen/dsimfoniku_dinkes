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
        // Menambahkan field SLS ke tabel akomodasi, akomodasi_lain, dan tempat_rekreasi
        $tables = ['akomodasi', 'akomodasi_lain', 'tempat_rekreasi'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Cek apakah kolom SLS sudah ada sebelum menambahkan
                    if (!Schema::hasColumn($tableName, 'dokumen_sls')) {
                        $table->string('dokumen_sls')->nullable()->comment('Link upload dokumen Sertifikat Laik Sehat (SLS)');
                    }
                    
                    if (!Schema::hasColumn($tableName, 'sls_issued_date')) {
                        $table->date('sls_issued_date')->nullable()->comment('Tanggal terbit dokumen SLS');
                    }
                    
                    if (!Schema::hasColumn($tableName, 'sls_expire_date')) {
                        $table->date('sls_expire_date')->nullable()->comment('Tanggal berakhir dokumen SLS (periode 3 tahun)');
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
        // Menghapus field SLS dari tabel akomodasi, akomodasi_lain, dan tempat_rekreasi
        $tables = ['akomodasi', 'akomodasi_lain', 'tempat_rekreasi'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Hanya hapus kolom jika ada
                    $columnsToDelete = [];
                    
                    if (Schema::hasColumn($tableName, 'dokumen_sls')) {
                        $columnsToDelete[] = 'dokumen_sls';
                    }
                    
                    if (Schema::hasColumn($tableName, 'sls_issued_date')) {
                        $columnsToDelete[] = 'sls_issued_date';
                    }
                    
                    if (Schema::hasColumn($tableName, 'sls_expire_date')) {
                        $columnsToDelete[] = 'sls_expire_date';
                    }
                    
                    if (!empty($columnsToDelete)) {
                        $table->dropColumn($columnsToDelete);
                    }
                });
            }
        }
    }
};
