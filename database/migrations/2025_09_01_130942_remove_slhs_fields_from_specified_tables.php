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
        // Daftar tabel yang akan dihapus field SLHS-nya
        $tables = [
            'sekolah',
            'rumah_sakit',
            'puskesmas',
            'tempat_rekreasi',
            'renang_pemandian',
            'akomodasi',
            'akomodasi_lain',
            'tempat_olahraga' // gelanggang olahraga
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
                    
                    // Cek juga untuk field dengan nama alternatif yang mungkin ada
                    if (Schema::hasColumn($tableName, 'tanggal_terbit_slhs')) {
                        $columnsToDelete[] = 'tanggal_terbit_slhs';
                    }
                    
                    if (Schema::hasColumn($tableName, 'expired')) {
                        $columnsToDelete[] = 'expired';
                    }
                    
                    if (!empty($columnsToDelete)) {
                        $table->dropColumn($columnsToDelete);
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
        // Daftar tabel yang akan dikembalikan field SLHS-nya
        $tables = [
            'sekolah',
            'rumah_sakit',
            'puskesmas',
            'tempat_rekreasi',
            'renang_pemandian',
            'akomodasi',
            'akomodasi_lain',
            'tempat_olahraga' // gelanggang olahraga
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Menambahkan kembali kolom SLHS
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
};
