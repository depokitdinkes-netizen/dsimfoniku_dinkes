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
        // Check and add missing kelurahan field to gerai_pangan_jajanan table if it doesn't exist
        if (!Schema::hasColumn('gerai_pangan_jajanan', 'kelurahan')) {
            Schema::table('gerai_pangan_jajanan', function (Blueprint $table) {
                $table->string('kelurahan')->after('alamat');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove kelurahan field if it exists
        if (Schema::hasColumn('gerai_pangan_jajanan', 'kelurahan')) {
            Schema::table('gerai_pangan_jajanan', function (Blueprint $table) {
                $table->dropColumn('kelurahan');
            });
        }
    }
};