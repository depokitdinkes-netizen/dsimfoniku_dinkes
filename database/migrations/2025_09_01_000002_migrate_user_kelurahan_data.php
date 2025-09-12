<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing data from users table to user_kelurahan table
        $users = DB::table('users')
            ->where('role', 'ADMIN')
            ->whereNotNull('kelurahan')
            ->whereNotNull('kecamatan')
            ->get();

        foreach ($users as $user) {
            DB::table('user_kelurahan')->insert([
                'user_id' => $user->id,
                'kelurahan' => $user->kelurahan,
                'kecamatan' => $user->kecamatan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore data back to users table
        $userKelurahan = DB::table('user_kelurahan')->get();
        
        foreach ($userKelurahan as $data) {
            DB::table('users')
                ->where('id', $data->user_id)
                ->update([
                    'kelurahan' => $data->kelurahan,
                    'kecamatan' => $data->kecamatan,
                ]);
        }
        
        // Clear the user_kelurahan table
        DB::table('user_kelurahan')->truncate();
    }
};
