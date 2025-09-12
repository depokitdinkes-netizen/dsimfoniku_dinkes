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
        Schema::table('gerai_pangan_jajanan', function (Blueprint $table) {
            // Add user_id column with foreign key constraint
            if (!Schema::hasColumn('gerai_pangan_jajanan', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gerai_pangan_jajanan', function (Blueprint $table) {
            // Drop foreign key and column
            if (Schema::hasColumn('gerai_pangan_jajanan', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
