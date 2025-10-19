<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user guest sudah ada berdasarkan email
        $existingGuest = DB::table('users')->where('email', 'guest@system.local')->first();
        
        if ($existingGuest) {
            $this->command->info('Guest user account already exists with ID: ' . $existingGuest->id);
            $this->command->info('Email: ' . $existingGuest->email);
            $this->command->info('Name: ' . $existingGuest->fullname);
        } else {
            $this->command->info('No guest user found. Please create one manually or check existing users.');
        }
    }
}