<?php

namespace App\Utils;

class Export
{
    public const DELAPAN_PULUH   = "DELAPAN_PULUH";     // 8
    public const RUMAH_SAKIT     = "RUMAH_SAKIT";       // 1
    public const SAM_DELAPAN     = "SAM_DELAPAN";       // 1
    public const SAM_PMA         = "SAM_PMA";           // 1
    public const SAM_SEBELAS     = "SAM_SEBELAS";       // 2
    public const SAM_TIGA_BELAS  = "SAM_TIGA_BELAS";    // 1
    public const SAM_TUJUH_BELAS = "SAM_TUJUH_BELAS";   // 1
    public const TUJUH_PULUH     = "TUJUH_PULUH";       // 7


    public static function score($score, $form, $pembangunan = true)
    {
        return "$score" . match ($form) {
            self::DELAPAN_PULUH => " - " . ($score >= 80 ? "Memenuhi Syarat" : "Tidak Memenuhi Syarat"),
            self::RUMAH_SAKIT => " - " . ($score >= 6500 ? ($score >= 8600 ? "Sangat Baik" : "Baik") : "Kurang"), // Based on normalized score to 10,000
            self::SAM_DELAPAN => " / 8 - Risiko " . ($score <= 2 ? "Rendah" : ($score <= 4 ? "Sedang" : ($score <= 6 ? "Tinggi" : "Amat Tinggi"))),
            self::SAM_PMA => " / " . ($pembangunan ? "16 - Risiko " . ($score <= 4 ? "Rendah" : ($score <= 8 ? "Sedang" : ($score <= 12 ? "Tinggi" : "Amat Tinggi"))) : "11 - Risiko " . ($score <= 2 ? "Rendah" : ($score <= 5 ? "Sedang" : ($score <= 8 ? "Tinggi" : "Amat Tinggi")))),
            self::SAM_SEBELAS => " / 11 - Risiko " . ($score <= 2 ? "Rendah" : ($score <= 5 ? "Sedang" : ($score <= 9 ? "Tinggi" : "Amat Tinggi"))),
            self::SAM_TIGA_BELAS => " / 13 - Risiko " . ($score <= 3 ? "Rendah" : ($score <= 6 ? "Sedang" : ($score <= 9 ? "Tinggi" : "Amat Tinggi"))),
            self::SAM_TUJUH_BELAS => " / 17 - Risiko " . ($score <= 4 ? "Rendah" : ($score <= 9 ? "Sedang" : ($score <= 14 ? "Tinggi" : "Amat Tinggi"))),
            self::TUJUH_PULUH => " - " . ($score >= 70 ? "Memenuhi Syarat" : "Tidak Memenuhi Syarat"),
        };
    }
}
