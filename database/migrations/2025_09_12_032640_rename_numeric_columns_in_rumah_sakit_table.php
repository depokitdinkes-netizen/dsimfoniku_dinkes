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
            // Rename all problematic numeric columns by adding an 'f' prefix
            $table->renameColumn('1001', 'f1001');
            $table->renameColumn('1003', 'f1003');
            $table->renameColumn('1004', 'f1004');
            $table->renameColumn('2002', 'f2002');
            $table->renameColumn('3001', 'f3001');
            $table->renameColumn('3002', 'f3002');
            $table->renameColumn('4001', 'f4001');
            $table->renameColumn('4002', 'f4002');
            $table->renameColumn('4005', 'f4005');
            $table->renameColumn('5002', 'f5002');
            $table->renameColumn('7001', 'f7001');
            $table->renameColumn('7002', 'f7002');
            $table->renameColumn('7003', 'f7003');
            $table->renameColumn('7004', 'f7004');
            $table->renameColumn('7005', 'f7005');
            $table->renameColumn('9002', 'f9002');
            $table->renameColumn('9003', 'f9003');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Rollback the renaming
            $table->renameColumn('f1001', '1001');
            $table->renameColumn('f1003', '1003');
            $table->renameColumn('f1004', '1004');
            $table->renameColumn('f2002', '2002');
            $table->renameColumn('f3001', '3001');
            $table->renameColumn('f3002', '3002');
            $table->renameColumn('f4001', '4001');
            $table->renameColumn('f4002', '4002');
            $table->renameColumn('f4005', '4005');
            $table->renameColumn('f5002', '5002');
            $table->renameColumn('f7001', '7001');
            $table->renameColumn('f7002', '7002');
            $table->renameColumn('f7003', '7003');
            $table->renameColumn('f7004', '7004');
            $table->renameColumn('f7005', '7005');
            $table->renameColumn('f9002', '9002');
            $table->renameColumn('f9003', '9003');
        });
    }
};