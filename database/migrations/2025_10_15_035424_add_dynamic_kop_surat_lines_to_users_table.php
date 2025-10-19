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
        Schema::table('users', function (Blueprint $table) {
            // Make baris5 nullable (modify existing column)
            $table->string('baris5')->nullable()->change();
            
            // Add dynamic kop surat lines (baris6-10) - all nullable
            $table->string('baris6')->nullable();
            $table->string('sizebaris6')->default('13px');
            $table->string('baris7')->nullable();
            $table->string('sizebaris7')->default('13px');
            $table->string('baris8')->nullable();
            $table->string('sizebaris8')->default('13px');
            $table->string('baris9')->nullable();
            $table->string('sizebaris9')->default('13px');
            $table->string('baris10')->nullable();
            $table->string('sizebaris10')->default('13px');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop dynamic kop surat lines
            $table->dropColumn([
                'baris6', 'sizebaris6',
                'baris7', 'sizebaris7', 
                'baris8', 'sizebaris8',
                'baris9', 'sizebaris9',
                'baris10', 'sizebaris10'
            ]);
        });
    }
};
