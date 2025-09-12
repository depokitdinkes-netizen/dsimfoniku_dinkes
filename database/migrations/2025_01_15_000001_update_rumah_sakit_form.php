<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Add user_id field
            
            // Modify kelas enum to include 'D'
            $table->enum('kelas', ['A', 'B', 'C', 'D'])->change();
            
            // Add document tracking fields
            $table->enum('dokumen-rintek-tps-b3', ['Ya', 'Tidak'])->nullable()->after('rencana-tindak-lanjut');
            $table->string('nomor-dokumen-rintek-tps-b3')->nullable()->after('dokumen-rintek-tps-b3');
            $table->enum('dokumen-pertek-ipal', ['Ya', 'Tidak'])->nullable()->after('nomor-dokumen-rintek-tps-b3');
            $table->string('nomor-dokumen-pertek-ipal')->nullable()->after('dokumen-pertek-ipal');
            $table->enum('pengisian-sikelim', ['Ya', 'Tidak'])->nullable()->after('nomor-dokumen-pertek-ipal');
            $table->text('alasan-sikelim')->nullable()->after('pengisian-sikelim');
            $table->enum('pengisian-dsmiling', ['Ya', 'Tidak'])->nullable()->after('alasan-sikelim');
            $table->text('alasan-dsmiling')->nullable()->after('pengisian-dsmiling');
            
            // Change evaluation fields from enum to integer
            $table->integer('1001')->default(0)->change();
            $table->integer('1002a')->nullable()->default(0)->change();
            $table->integer('1002b')->nullable()->default(0)->change();
            $table->integer('1002c')->nullable()->default(0)->change();
            $table->integer('1003')->default(0)->change();
            $table->integer('1004')->default(0)->change();
            
            $table->integer('2001a')->default(0)->change();
            $table->integer('2001b')->default(0)->change();
            $table->integer('2002')->default(0)->change();
            $table->integer('2003a')->default(0)->change();
            $table->integer('2003b')->default(0)->change();
            $table->integer('2003c')->default(0)->change();
            $table->integer('2003d')->default(0)->change();
            $table->integer('2003e')->default(0)->change();
            $table->integer('2003f')->default(0)->change();
            $table->integer('2003g')->default(0)->change();
            $table->integer('2003h')->default(0)->change();
            $table->integer('2003i')->default(0)->change();
            $table->integer('2003j')->default(0)->change();
            $table->integer('2003k')->default(0)->change();
            $table->integer('2004a')->default(0)->change();
            $table->integer('2004b')->default(0)->change();
            $table->integer('2004c')->default(0)->change();
            $table->integer('2004d')->default(0)->change();
            $table->integer('2004e')->default(0)->change();
            $table->integer('2004f')->default(0)->change();
            $table->integer('2004g')->default(0)->change();
            $table->integer('2004h')->default(0)->change();
            $table->integer('2004i')->default(0)->change();
            $table->integer('2004j')->default(0)->change();
            $table->integer('2004k')->default(0)->change();
            $table->integer('2004l')->default(0)->change();
            $table->integer('2004m')->default(0)->change();
            $table->integer('2004n')->default(0)->change();
            $table->integer('2004o')->default(0)->change();
            $table->integer('2004p')->default(0)->change();
            $table->integer('2005a')->default(0)->change();
            $table->integer('2005b')->default(0)->change();
            $table->integer('2005c')->default(0)->change();
            $table->integer('2005d')->default(0)->change();
            $table->integer('2005e')->default(0)->change();
            $table->integer('2005f')->default(0)->change();
            $table->integer('2005g')->default(0)->change();
            $table->integer('2005h')->default(0)->change();
            $table->integer('2005i')->default(0)->change();
            
            $table->integer('3001')->default(0)->change();
            // Add new fields for section 3
            $table->text('keterangan_pihak_ketiga_jasa_boga')->nullable()->after('3001');
            $table->integer('jumlah_penjamah_pangan')->nullable()->after('keterangan_pihak_ketiga_jasa_boga');
            $table->integer('jumlah_penjamah_bersertifikat')->nullable()->after('jumlah_penjamah_pangan');
            $table->integer('3002')->default(0)->change();
            
            $table->integer('4001')->default(0)->change();
            $table->integer('4002')->default(0)->change();
            $table->integer('4003a')->default(0)->change();
            $table->integer('4003b')->default(0)->change();
            $table->integer('4003c')->default(0)->change();
            $table->integer('4003d')->default(0)->change();
            $table->integer('4004a')->default(0)->change();
            // Change 4004b from integer to varchar
            $table->string('4004b')->nullable()->change();
            $table->integer('4004c')->default(0)->change();
            $table->integer('4004d')->default(0)->change();
            $table->integer('4004e')->default(0)->change();
            $table->integer('4004f')->default(0)->change();
            $table->integer('4005')->default(0)->change();
            $table->integer('4006a')->default(0)->change();
            $table->integer('4006b')->default(0)->change();
            $table->integer('4006c')->default(0)->change();
            $table->integer('4006d')->default(0)->change();
            $table->integer('4006e')->default(0)->change();
            
            $table->integer('5001a')->default(0)->change();
            $table->integer('5001b')->default(0)->change();
            $table->integer('5001c')->default(0)->change();
            $table->integer('5001d')->default(0)->change();
            $table->integer('5001e')->default(0)->change();
            $table->integer('5001f')->default(0)->change();
            $table->integer('5001g')->default(0)->change();
            $table->integer('5001h')->default(0)->change();
            $table->integer('5001i')->default(0)->change();
            $table->integer('5001j')->default(0)->change();
            // Add new fields for section 5
            $table->text('keterangan_pihak_ketiga_pest_control')->nullable()->after('5001j');
            $table->text('nomor_perizinan_pest_control')->nullable()->after('keterangan_pihak_ketiga_pest_control');
            $table->integer('5002')->default(0)->change();
            
            $table->integer('6001a')->default(0)->change();
            $table->integer('6001b')->default(0)->change();
            $table->integer('6001c')->default(0)->change();
            $table->integer('6002a')->default(0)->change();
            $table->integer('6002b')->default(0)->change();
            $table->integer('6002c')->default(0)->change();
            $table->integer('6002d')->default(0)->change();
            // Add new fields for section 6
            $table->text('keterangan_pihak_ketiga_b3')->nullable()->after('6002d');
            $table->text('nomor_perizinan_tps_b3')->nullable()->after('keterangan_pihak_ketiga_b3');
            $table->integer('6003a')->default(0)->change();
            $table->integer('6003b')->default(0)->change();
            $table->text('nomor_perizinan_ipal')->nullable()->after('6003b');
            $table->integer('6004a')->default(0)->change();
            $table->integer('6004b')->default(0)->change();
            $table->integer('6004c')->default(0)->change();
            $table->integer('6004d')->default(0)->change();
            $table->integer('6004e')->default(0)->change();
            
            // Change section 7 fields from enum to integer and add new fields
            $table->integer('7001')->default(0)->after('6004e');
            $table->integer('7002')->default(0)->after('7001');
            $table->integer('7003')->default(0)->after('7002');
            $table->boolean('memiliki_alat_rontgen_portable')->nullable()->after('7003');
            $table->boolean('memiliki_shielding_radiasi')->nullable()->after('memiliki_alat_rontgen_portable');
            $table->integer('7004')->nullable()->after('memiliki_shielding_radiasi');
            $table->integer('7005')->nullable()->after('7004');
            
            // Drop old 7a, 7b, 7c fields
            $table->dropColumn(['7a', '7b', '7c']);
            
            $table->integer('8001a')->default(0)->change();
            $table->integer('8001b')->default(0)->change();
            $table->integer('8001c')->default(0)->change();
            $table->integer('8001d')->default(0)->change();
            $table->integer('8001e')->default(0)->change();
            $table->integer('8002a')->default(0)->change();
            $table->integer('8002b')->default(0)->change();
            
            $table->integer('9001a')->default(0)->change();
            $table->integer('9001b')->default(0)->change();
            $table->integer('9001c')->default(0)->change();
            $table->integer('9001d')->default(0)->change();
            $table->integer('9001e')->default(0)->change();
            $table->integer('9001f')->default(0)->change();
            $table->integer('9002')->default(0)->change();
            $table->integer('9003')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Remove user_id field
            $table->dropColumn('user_id');
            
            // Revert kelas enum
            $table->enum('kelas', ['A', 'B', 'C'])->change();
            
            // Remove document tracking fields
            $table->dropColumn([
                'dokumen-rintek-tps-b3',
                'nomor-dokumen-rintek-tps-b3',
                'dokumen-pertek-ipal',
                'nomor-dokumen-pertek-ipal',
                'pengisian-sikelim',
                'alasan-sikelim',
                'pengisian-dsmiling',
                'alasan-dsmiling'
            ]);
            
            // Remove new fields
            $table->dropColumn([
                'keterangan_pihak_ketiga_jasa_boga',
                'jumlah_penjamah_pangan',
                'jumlah_penjamah_bersertifikat',
                'keterangan_pihak_ketiga_pest_control',
                'nomor_perizinan_pest_control',
                'keterangan_pihak_ketiga_b3',
                'nomor_perizinan_tps_b3',
                'nomor_perizinan_ipal',
                'memiliki_alat_rontgen_portable',
                'memiliki_shielding_radiasi',
                '7001',
                '7002',
                '7003',
                '7004',
                '7005'
            ]);
            
            // Add back old 7a, 7b, 7c fields
            $table->enum('7a', [0, 400])->after('6004e');
            $table->enum('7b', [0, 300])->after('7a');
            $table->enum('7c', [0, 300])->after('7b');
            
            // Revert evaluation fields back to enum (this is complex and may require data migration)
            // Note: This rollback may cause data loss if integer values don't match enum values
        });
    }
};