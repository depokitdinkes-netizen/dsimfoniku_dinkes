<?php

namespace Database\Seeders;

use App\Models\FormIKL\GeraiPanganJajanan;
use App\Models\FormIKL\GeraiJajananKeliling;
use App\Models\FormIKL\TempatOlahraga;
use App\Models\FormIKL\TempatRekreasi;
use App\Models\FormIKL\SumurGali;
use App\Models\FormIKL\SumurBorPompa;
use App\Models\FormIKL\RumahMakan;
use App\Models\FormIKL\RenangPemandian;
use App\Models\FormIKL\Puskesmas;
use App\Models\FormIKL\PerpipaanNonPdam;
use App\Models\FormIKL\Perpipaan;
use App\Models\FormIKL\PerlindunganMataAir;
use App\Models\FormIKL\PenyimpananAirHujan;
use App\Models\FormIKL\Kantin;
use App\Models\FormIKL\GeraiKantin;
use App\Models\FormIKL\DepotAirMinum;
use App\Models\FormIKL\AkomodasiLain;
use App\Models\FormIKL\Akomodasi;
use App\Models\FormIKL\TempatIbadah;
use App\Models\FormIKL\Pasar;
use App\Models\User;
use Illuminate\Database\Seeder;

class FixAllUserIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user admin pertama yang akan dijadikan owner default
        $defaultUser = User::where('role', 'ADMIN')->first() ?? User::first();
        
        if (!$defaultUser) {
            $this->command->error('Tidak ada user yang ditemukan dalam database!');
            return;
        }

        $totalUpdated = 0;

        // Daftar semua model yang perlu diperbaiki user_id nya
        $models = [
            'GeraiPanganJajanan' => GeraiPanganJajanan::class,
            'GeraiJajananKeliling' => GeraiJajananKeliling::class,
            'TempatOlahraga' => TempatOlahraga::class,
            'TempatRekreasi' => TempatRekreasi::class,
            'SumurGali' => SumurGali::class,
            'SumurBorPompa' => SumurBorPompa::class,
            'RumahMakan' => RumahMakan::class,
            'RenangPemandian' => RenangPemandian::class,
            'Puskesmas' => Puskesmas::class,
            'Perpipaan' => Perpipaan::class,
            'PerpipaanNonPdam' => PerpipaanNonPdam::class,
            'PerlindunganMataAir' => PerlindunganMataAir::class,
            'PenyimpananAirHujan' => PenyimpananAirHujan::class,
            'Kantin' => Kantin::class,
            'GeraiKantin' => GeraiKantin::class,
            'DepotAirMinum' => DepotAirMinum::class,
            'Akomodasi' => Akomodasi::class,
            'AkomodasiLain' => AkomodasiLain::class,
            'TempatIbadah' => TempatIbadah::class,
            'Pasar' => Pasar::class,
        ];

        foreach ($models as $modelName => $modelClass) {
            try {
                $updatedCount = $modelClass::whereNull('user_id')
                    ->update(['user_id' => $defaultUser->id]);
                
                if ($updatedCount > 0) {
                    $this->command->info("✓ {$modelName}: {$updatedCount} record(s) diperbaiki");
                    $totalUpdated += $updatedCount;
                } else {
                    $this->command->info("✓ {$modelName}: Tidak ada record yang perlu diperbaiki");
                }
            } catch (\Exception $e) {
                $this->command->warn("✗ {$modelName}: Error - " . $e->getMessage());
            }
        }

        $this->command->info("\n=== SUMMARY ===");
        $this->command->info("Total {$totalUpdated} record(s) berhasil diperbaiki dengan user_id: {$defaultUser->id} ({$defaultUser->fullname})");
    }
}
