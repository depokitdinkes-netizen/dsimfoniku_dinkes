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
        Schema::table('tempat_rekreasi', function (Blueprint $table) {
            // Modify kontak field to have proper length constraint
            $table->string('kontak', 35)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tempat_rekreasi', function (Blueprint $table) {
            // Revert kontak field back to default string length
            $table->string('kontak')->change();
        });
    }
};