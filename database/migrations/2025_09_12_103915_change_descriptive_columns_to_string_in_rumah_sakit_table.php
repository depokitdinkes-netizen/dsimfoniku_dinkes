<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Mengubah tipe kolom dari integer ke string
            $table->string('f1005')->nullable()->change();
            $table->string('f1006')->nullable()->change();
            $table->string('f1007')->nullable()->change();
            $table->string('6001d')->nullable()->change();
            $table->string('6001e')->nullable()->change();
            $table->string('6001f')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rumah_sakit', function (Blueprint $table) {
            // Mengembalikan tipe kolom ke integer jika di-rollback
            $table->integer('f1005')->nullable()->change();
            $table->integer('f1006')->nullable()->change();
            $table->integer('f1007')->nullable()->change();
            $table->integer('6001d')->nullable()->change();
            $table->integer('6001e')->nullable()->change();
            $table->integer('6001f')->nullable()->change();
        });
    }
};