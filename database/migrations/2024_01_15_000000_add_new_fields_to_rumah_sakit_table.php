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
            // Tambah field untuk pemeriksaan air hemodialisa
            $table->integer('f1005')->nullable()->comment('Apakah melakukan pemeriksaan air hemodialisa?')->after('f1004');
            $table->integer('f1006')->nullable()->comment('Apakah melakukan pemeriksaan endotoksin Air Hemodialisa setiap bulan?')->after('f1005');
            $table->integer('f1007')->nullable()->comment('Apakah hasil endotoksin memenuhi 0,25 IU/ml')->after('f1006');
            
            // Tambah field untuk limbah domestik
            $table->integer('6001d')->nullable()->comment('Melakukan penanganan limbah dengan 3R*')->after('6001c');
            $table->integer('6001e')->nullable()->comment('Pemilahan Limbah Domestik yang dilakukan*')->after('6001d');
            $table->integer('6001f')->nullable()->comment('Memiliki TPS limbah domestik*')->after('6001e');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            $table->dropColumn(['f1005', 'f1006', 'f1007', '6001d', '6001e', '6001f']);
        });
    }
};