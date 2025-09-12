<?php

namespace App\Http\Controllers;

use App\Models\FormIKL\Akomodasi;
use App\Models\FormIKL\AkomodasiLain;
use App\Models\FormIKL\DepotAirMinum;
use App\Models\FormIKL\GeraiJajananKeliling;
use App\Models\FormIKL\GeraiKantin;
use App\Models\FormIKL\GeraiPanganJajanan;
use App\Models\FormIKL\JasaBogaKatering;
use App\Models\FormIKL\Kantin;
use App\Models\FormIKL\Pasar;
use App\Models\FormIKL\PasarInternal;
use App\Models\FormIKL\PenyimpananAirHujan;
use App\Models\FormIKL\PerlindunganMataAir;
use App\Models\FormIKL\Perpipaan;
use App\Models\FormIKL\PerpipaanNonPdam;
use App\Models\FormIKL\Puskesmas;
use App\Models\FormIKL\RenangPemandian;
use App\Models\FormIKL\Restoran;
use App\Models\FormIKL\RumahMakan;
use App\Models\FormIKL\RumahSakit;
use App\Models\FormIKL\Sekolah;
use App\Models\FormIKL\Stasiun;
use App\Models\FormIKL\SumurBorPompa;
use App\Models\FormIKL\SumurGali;
use App\Models\FormIKL\TempatOlahraga;
use App\Models\FormIKL\TempatRekreasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller {
    protected function inspectionResults() {
        $table = [
            'name',         // [00] nama tempat
            'owner',        // [01] penanggung jawab
            'reviewer',     // [02] nama pemeriksa
            'date',         // [03] tanggal pemeriksaan
            'form',         // [04] form
            'type',         // [05] type
            'updated_at',   // [06] last update
            'sud',          // [07] show update delete
            'address',      // [08] address
            'score',        // [09] score
            'title',        // [10] title
            'icon',         // [11] icon
            'color',        // [12] color
            'kelurahan',    // [13] kelurahan
            'kecamatan',    // [14] kecamatan
            'operasi',      // [15] status operasi
            'slhs_expire_date',   // [16] SLHS expire date
            'slhs_issued_date',   // [17] SLHS issued date
        ];

        // Untuk guest, tampilkan semua data
        // Untuk user yang login, filter berdasarkan role
        $isAdmin = false;
        $userId = null;
        
        if (Auth::check()) {
            $user = Auth::user();
            $isAdmin = $user->role === 'ADMIN';
            $userId = $user->id;
        }

        return collect(array_merge(
            $this->getRestoranData($table, $isAdmin, $userId),
            $this->getJasaBogaKateringData($table, $isAdmin, $userId),
            $this->getRumahMakanData($table, $isAdmin, $userId),
            $this->getKantinData($table, $isAdmin, $userId),
            $this->getDepotAirMinumData($table, $isAdmin, $userId),
            $this->getSumurGaliData($table, $isAdmin, $userId),
            $this->getSumurBorPompaData($table, $isAdmin, $userId),
            $this->getPerpipaanData($table, $isAdmin, $userId),
            $this->getPerpipaanNonPdamData($table, $isAdmin, $userId),
            $this->getPerlindunganMataAirData($table, $isAdmin, $userId),
            $this->getPenyimpananAirHujanData($table, $isAdmin, $userId),
            $this->getGeraiPanganJajananData($table, $isAdmin, $userId),
            $this->getGeraiJajananKelilingData($table, $isAdmin, $userId),
            $this->getSekolahData($table, $isAdmin, $userId),
            $this->getRumahSakitData($table, $isAdmin, $userId),
            $this->getPuskesmasData($table, $isAdmin, $userId),
            $this->getTempatRekreasiData($table, $isAdmin, $userId),
            $this->getRenangPemandianData($table, $isAdmin, $userId),
            $this->getAkomodasiData($table, $isAdmin, $userId),
            $this->getAkomodasiLainData($table, $isAdmin, $userId),
            $this->getTempatOlahragaData($table, $isAdmin, $userId),
            $this->getStasiunData($table, $isAdmin, $userId),
            $this->getPasarData($table, $isAdmin, $userId),
            $this->getPasarInternalData($table, $isAdmin, $userId),
        ));
    }

    protected function archivedResults() {
        $table = [
            'name',         // [00] nama tempat
            'owner',        // [01] penanggung jawab
            'reviewer',     // [02] nama pemeriksa
            'date',         // [03] tanggal pemeriksaan
            'form',         // [04] form
            'type',         // [05] type
            'updated_at',   // [06] last update
            'sud',          // [07] show update delete
            'address',      // [08] address
            'score',        // [09] score
            'title',        // [10] title
            'icon',         // [11] icon
            'color',        // [12] color
            'operasi',      // [13] status operasi
        ];

        return collect(array_merge(
            Restoran::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'restoran',
                $table[5] => $item['u009'],
                $table[6] => $item['updated_at'],
                $table[7] => "restoran/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Restoran ' . ucfirst(strtolower($item->u009)),
                $table[11] => 'ri-restaurant-2-line',
                $table[12] => 'rose-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            JasaBogaKatering::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'jasa-boga-katering',
                $table[5] => $item['u009'],
                $table[6] => $item['updated_at'],
                $table[7] => "jasa-boga-katering/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Jasa Boga Katering Gol ' . $item->u009,
                $table[11] => 'ri-restaurant-line',
                $table[12] => 'red-400',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            RumahMakan::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'rumah-makan',
                $table[5] => $item['u009'],
                $table[6] => $item['updated_at'],
                $table[7] => "rumah-makan/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Rumah Makan Tipe ' . ucfirst(strtolower($item->u009)),
                $table[11] => 'ri-home-8-line',
                $table[12] => 'red-700',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Kantin::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'kantin',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "kantin/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Sentra Kantin',
                $table[11] => 'ri-store-3-line',
                $table[12] => 'orange-400',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            // GeraiKantin::onlyTrashed()->get()->map(fn($item) => [
            //     $table[0] => $item['subjek'],
            //     $table[1] => $item['pengelola'],
            //     $table[2] => Kantin::find($item['id-kantin'])['nama-pemeriksa'],
            //     $table[3] => Kantin::find($item['id-kantin'])['tanggal-penilaian'],
            //     $table[4] => 'gerai-kantin',
            //     $table[5] => 'NONE',
            //     $table[6] => $item['updated_at'],
            //     $table[7] => "gerai-kantin/$item->id",
            //     $table[8] => Kantin::find($item['id-kantin'])['alamat'],
            //     $table[9] => $item['skor'],
            //     $table[10] => 'Gerai Kantin',
            //     $table[11] => 'ri-store-3-line',
            //     $table[12] => 'orange-400',
            //     $table[13] => $item['status-operasi'],
            // ])->toArray(),
            DepotAirMinum::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'depot-air-minum',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "depot-air-minum/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Depot Air Minum',
                $table[11] => 'ri-drinks-fill',
                $table[12] => 'blue-600',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            SumurGali::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'sumur-gali',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "sumur-gali/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Perpipaan Sumur Gali',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            SumurBorPompa::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'sumur-bor-pompa',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "sumur-bor-pompa/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Perpipaan Sumur Gali dengan Pompa',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Perpipaan::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'perpipaan',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "perpipaan/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Perpipaan PDAM',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            PerpipaanNonPdam::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'perpipaan-non-pdam',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "perpipaan-non-pdam/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Perpipaan Non PDAM',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            PerlindunganMataAir::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'perlindungan-mata-air',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "perlindungan-mata-air/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Perlindungan Mata Air',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            PenyimpananAirHujan::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'penyimpanan-air-hujan',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "penyimpanan-air-hujan/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Penyimpanan Air Hujan',
                $table[11] => 'ri-drop-fill',
                $table[12] => 'blue-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            GeraiPanganJajanan::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'gerai-pangan-jajanan',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "gerai-pangan-jajanan/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Gerai Pangan Jajanan',
                $table[11] => 'ri-store-line',
                $table[12] => 'yellow-900',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            GeraiJajananKeliling::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'gerai-jajanan-keliling',
                $table[5] => $item['u011'],
                $table[6] => $item['updated_at'],
                $table[7] => "gerai-jajanan-keliling/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Gerai Jajanan Keliling Gol ' . $item->u011,
                $table[11] => 'ri-store-2-line',
                $table[12] => 'yellow-800',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Sekolah::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'sekolah',
                $table[5] => $item['jenis_sekolah'] ?? 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "sekolah/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Sekolah ' . ($item['jenis_sekolah'] ?? ''),
                $table[11] => 'ri-graduation-cap-line',
                $table[12] => 'black/70',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            RumahSakit::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian']->format('Y-m-d'),
                $table[4] => 'rumah-sakit',
                $table[5] => $item['kelas'],
                $table[6] => $item['updated_at'],
                $table[7] => "rumah-sakit/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Puskesmas',
                $table[11] => 'ri-hospital-line',
                $table[12] => 'slate-400',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Puskesmas::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'puskesmas',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "puskesmas/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Puskesmas',
                $table[11] => 'ri-stethoscope-line',
                $table[12] => 'gray-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            TempatRekreasi::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'tempat-rekreasi',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "tempat-rekreasi/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Tempat Rekreasi',
                $table[11] => 'ri-sparkling-line',
                $table[12] => 'violet-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            RenangPemandian::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'renang-pemandian',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "renang-pemandian/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Arena Renang / Pemandian Alam',
                $table[11] => 'ri-community-line',
                $table[12] => 'sky-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Akomodasi::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'akomodasi',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "akomodasi/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Akomodasi',
                $table[11] => 'ri-home-office-line',
                $table[12] => 'stone-600',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            AkomodasiLain::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'akomodasi-lain',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "akomodasi-lain/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Akomodasi Lainnya',
                $table[11] => 'ri-home-office-fill',
                $table[12] => 'stone-700',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            TempatOlahraga::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'tempat-olahraga',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "tempat-olahraga/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Tempat Olahraga',
                $table[11] => 'ri-building-4-line',
                $table[12] => 'orange-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Stasiun::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'stasiun',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "stasiun/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Stasiun',
                $table[11] => 'ri-train-line',
                $table[12] => 'blue-600',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            Pasar::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian']->format('Y-m-d'),
                $table[4] => 'pasar',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "pasar/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Pasar',
                $table[11] => 'ri-store-3-line',
                $table[12] => 'orange-400',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
            PasarInternal::onlyTrashed()->get()->map(fn($item) => [
                $table[0] => $item['subjek'],
                $table[1] => $item['pengelola'],
                $table[2] => $item['nama-pemeriksa'],
                $table[3] => $item['tanggal-penilaian'],
                $table[4] => 'pasar-internal',
                $table[5] => 'NONE',
                $table[6] => $item['updated_at'],
                $table[7] => "pasar-internal/$item->id",
                $table[8] => $item['alamat'],
                $table[9] => $item['skor'],
                $table[10] => 'Pasar Internal',
                $table[11] => 'ri-store-3-fill',
                $table[12] => 'orange-500',
                $table[13] => $item['status-operasi'],
            ])->toArray(),
        ));
    }

    public function dashboard() {
        // Dashboard guest - dapat diakses tanpa login
        $inspections = $this->inspectionResults()
            ->sortByDesc('updated_at')
            ->take(3)
            ->values();
        
        $totalResults = $this->inspectionResults()->count();
        $totalResultsByYear = array_reverse(array_count_values(array_map(fn($item) => date('Y', strtotime($item['date'])), $this->inspectionResults()->toArray())), true);

        return view('pages.dashboard', [
            'page_name' => 'dashboard',
            'inspections' => $inspections,
            'total_results' => $totalResults,
            'total_results_by_year' => $totalResultsByYear,
        ]);
    }

    private function getRestoranData($table, $isAdmin, $userId) {
        $query = Restoran::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'restoran',
            $table[5] => $item['u009'],
            $table[6] => $item['updated_at'],
            $table[7] => "restoran/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Restoran ' . ucfirst(strtolower($item->u009)),
            $table[11] => 'ri-restaurant-2-line',
            $table[12] => 'rose-500',
            $table[13] => $item['kelurahan'],
            $table[14] => $item['kecamatan'],
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'],
            $table[17] => $item['slhs_issued_date'],
        ])->toArray();
    }

    private function getJasaBogaKateringData($table, $isAdmin, $userId) {
        $query = JasaBogaKatering::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'jasa-boga-katering',
            $table[5] => $item['u009'],
            $table[6] => $item['updated_at'],
            $table[7] => "jasa-boga-katering/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Jasa Boga Katering Gol ' . $item->u009,
            $table[11] => 'ri-restaurant-line',
            $table[12] => 'red-400',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'],
            $table[17] => $item['slhs_issued_date'],
        ])->toArray();
    }

    private function getRumahMakanData($table, $isAdmin, $userId) {
        $query = RumahMakan::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'rumah-makan',
            $table[5] => $item['u009'],
            $table[6] => $item['updated_at'],
            $table[7] => "rumah-makan/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Rumah Makan Tipe ' . ucfirst(strtolower($item->u009)),
            $table[11] => 'ri-home-8-line',
            $table[12] => 'red-700',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getKantinData($table, $isAdmin, $userId) {
        $query = Kantin::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'kantin',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "kantin/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Sentra Kantin',
            $table[11] => 'ri-store-3-line',
            $table[12] => 'orange-400',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getDepotAirMinumData($table, $isAdmin, $userId) {
        $query = DepotAirMinum::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'depot-air-minum',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "depot-air-minum/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Depot Air Minum',
            $table[11] => 'ri-drinks-fill',
            $table[12] => 'blue-600',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getSumurGaliData($table, $isAdmin, $userId) {
        $query = SumurGali::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'sumur-gali',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "sumur-gali/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Perpipaan Sumur Gali',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getSumurBorPompaData($table, $isAdmin, $userId) {
        $query = SumurBorPompa::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'sumur-bor-pompa',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "sumur-bor-pompa/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Perpipaan Sumur Gali dengan Pompa',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPerpipaanData($table, $isAdmin, $userId) {
        $query = Perpipaan::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'perpipaan',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "perpipaan/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Perpipaan PDAM',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPerpipaanNonPdamData($table, $isAdmin, $userId) {
        $query = PerpipaanNonPdam::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'perpipaan-non-pdam',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "perpipaan-non-pdam/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Perpipaan Non PDAM',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPerlindunganMataAirData($table, $isAdmin, $userId) {
        $query = PerlindunganMataAir::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'perlindungan-mata-air',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "perlindungan-mata-air/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Perlindungan Mata Air',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPenyimpananAirHujanData($table, $isAdmin, $userId) {
        $query = PenyimpananAirHujan::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'penyimpanan-air-hujan',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "penyimpanan-air-hujan/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Penyimpanan Air Hujan',
            $table[11] => 'ri-drop-fill',
            $table[12] => 'blue-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getGeraiPanganJajananData($table, $isAdmin, $userId) {
        $query = GeraiPanganJajanan::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'gerai-pangan-jajanan',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "gerai-pangan-jajanan/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Gerai Pangan Jajanan',
            $table[11] => 'ri-store-line',
            $table[12] => 'yellow-900',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getGeraiJajananKelilingData($table, $isAdmin, $userId) {
        $query = GeraiJajananKeliling::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'gerai-jajanan-keliling',
            $table[5] => $item['u011'],
            $table[6] => $item['updated_at'],
            $table[7] => "gerai-jajanan-keliling/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Gerai Jajanan Keliling Gol ' . $item->u011,
            $table[11] => 'ri-store-2-line',
            $table[12] => 'yellow-800',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getSekolahData($table, $isAdmin, $userId) {
        $query = Sekolah::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'sekolah',
            $table[5] => $item['jenis_sekolah'] ?? 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "sekolah/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Sekolah ' . ($item['jenis_sekolah'] ?? ''),
            $table[11] => 'ri-graduation-cap-line',
            $table[12] => 'black/70',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'],
            $table[17] => $item['slhs_issued_date'],
        ])->toArray();
    }

    private function getRumahSakitData($table, $isAdmin, $userId) {
        $query = RumahSakit::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian']->format('Y-m-d'),
            $table[4] => 'rumah-sakit',
            $table[5] => $item['kelas'],
            $table[6] => $item['updated_at'],
            $table[7] => "rumah-sakit/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Rumah Sakit',
            $table[11] => 'ri-hospital-line',
            $table[12] => 'slate-400',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPuskesmasData($table, $isAdmin, $userId) {
        $query = Puskesmas::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'puskesmas',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "puskesmas/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Puskesmas',
            $table[11] => 'ri-stethoscope-line',
            $table[12] => 'gray-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getTempatRekreasiData($table, $isAdmin, $userId) {
        $query = TempatRekreasi::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'tempat-rekreasi',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "tempat-rekreasi/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Tempat Rekreasi',
            $table[11] => 'ri-sparkling-line',
            $table[12] => 'violet-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getRenangPemandianData($table, $isAdmin, $userId) {
        $query = RenangPemandian::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'renang-pemandian',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "renang-pemandian/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Arena Renang / Pemandian Alam',
            $table[11] => 'ri-community-line',
            $table[12] => 'sky-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getAkomodasiData($table, $isAdmin, $userId) {
        $query = Akomodasi::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'akomodasi',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "akomodasi/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Akomodasi',
            $table[11] => 'ri-home-office-line',
            $table[12] => 'stone-600',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getAkomodasiLainData($table, $isAdmin, $userId) {
        $query = AkomodasiLain::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'akomodasi-lain',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "akomodasi-lain/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Akomodasi Lainnya',
            $table[11] => 'ri-home-office-fill',
            $table[12] => 'stone-700',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getTempatOlahragaData($table, $isAdmin, $userId) {
        $query = TempatOlahraga::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'tempat-olahraga',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "tempat-olahraga/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Tempat Olahraga',
            $table[11] => 'ri-building-4-line',
            $table[12] => 'orange-500',
            $table[13] => $item->kelurahan,
            $table[14] => $item->kecamatan,
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    public function history(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $page = (int) $request->query('p', 1);

        // Handle data per page
        $dpp = $request->query('dpp');
        if ($dpp) {
            session()->put('dpp', $dpp);
        }

        if (!session()->get('dpp')) {
            session()->put('dpp', 5);
        }

        // Get all inspections and apply filters
        $inspections = $this->inspectionResults()
            ->sortByDesc('updated_at')
            ->when($request['s'], fn($items) => $items->filter(fn($item) => stripos($item['name'], $request['s']) !== false))
            ->when($request['my'], fn($items) => $items->filter(fn($item) => Carbon::parse($item['date'])->format('Y-m') === $request['my']))
            ->when($request['ft'], fn($items) => $items->filter(fn($item) => in_array($item['form'], $request['ft'])))
            ->when($request['kec'], fn($items) => $items->filter(fn($item) => $item['kecamatan'] === $request['kec']))
            ->when($request['kel'], fn($items) => $items->filter(fn($item) => in_array($item['kelurahan'], $request['kel'])))
            ->when($request['jenis_sekolah'], fn($items) => $items->filter(fn($item) => $item['form'] === 'sekolah' && $item['type'] === $request['jenis_sekolah']))
            ->when($request['slhs_status'], fn($items) => $items->filter(fn($item) => $this->getSlhsStatus($item) === $request['slhs_status']))
            ->values()->toArray();

        $total_records = count($inspections);
        $dpp_value = (int) session()->get('dpp');
        $total_pages = $total_records > 0 ? ceil($total_records / $dpp_value) : 1;

        // Ensure page is within valid range
        if ($page < 1) $page = 1;
        if ($page > $total_pages) $page = $total_pages;

        return view('pages.history.index', [
            'page_name' => 'history',
            'inspections' => array_slice($inspections, ($page - 1) * $dpp_value, $dpp_value),
            'dpp' => $dpp_value,
            'page_index' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
        ]);
    }

    public function archived(Request $request) {
        return view('pages.history.archived', [
            'page_name' => 'history',
            'inspections' => $this->archivedResults()->sortByDesc('updated_at')
                ->when($request['s'], fn($items) => $items->filter(fn($item) => stripos($item['name'], $request['s']) !== false))
        ]);
    }

    /**
     * Helper method untuk menentukan status SLHS berdasarkan issued date
     */
    private function getSlhsStatus($item) {
        $issuedDate = $item['slhs_issued_date'] ?? null;
        
        if (!$issuedDate) {
            return 'no-data';
        }
        
        $today = Carbon::now();
        $issued = Carbon::parse($issuedDate);
        $expire = $issued->copy()->addYears(3); // Expire berdasarkan issued + 3 tahun
        
        $diffInDays = $today->diffInDays($expire, false);
        
        // Determine status based on days remaining
        if ($diffInDays < 0) return 'expired';
        if ($diffInDays <= 30) return 'critical';
        if ($diffInDays <= 180) return 'warning';
        if ($diffInDays <= 365) return 'caution';
        if ($diffInDays < 730) return 'good';  // Ubah dari <= menjadi <
        return 'excellent';
    }

    private function getStasiunData($table, $isAdmin, $userId) {
        $query = Stasiun::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'stasiun',
            $table[5] => $item['jenis_stasiun'] ?? 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "stasiun/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Stasiun',
            $table[11] => 'ri-train-line',
            $table[12] => 'blue-500',
            $table[13] => $item['kelurahan'],
            $table[14] => $item['kecamatan'],
            $table[15] => $item['status-operasi'],
            $table[16] => $item['slhs_expire_date'] ?? null,
            $table[17] => $item['slhs_issued_date'] ?? null,
        ])->toArray();
    }

    private function getPasarData($table, $isAdmin, $userId) {
        $query = Pasar::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian']->format('Y-m-d'),
            $table[4] => 'pasar',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "pasar/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Pasar',
            $table[11] => 'ri-store-2-line',
            $table[12] => 'green-600',
            $table[13] => $item['kelurahan'],
            $table[14] => $item['kecamatan'],
            $table[15] => $item['status-operasi'],
            $table[16] => null,
            $table[17] => null,
        ])->toArray();
    }

    private function getPasarInternalData($table, $isAdmin, $userId) {
        $query = PasarInternal::query();
        if ($isAdmin && $userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->get()->map(fn($item) => [
            $table[0] => $item['subjek'],
            $table[1] => $item['pengelola'],
            $table[2] => $item['nama-pemeriksa'],
            $table[3] => $item['tanggal-penilaian'],
            $table[4] => 'pasar-internal',
            $table[5] => 'NONE',
            $table[6] => $item['updated_at'],
            $table[7] => "pasar-internal/$item->id",
            $table[8] => $item['alamat'],
            $table[9] => $item['skor'],
            $table[10] => 'Pasar Internal',
            $table[11] => 'ri-building-2-line',
            $table[12] => 'purple-600',
            $table[13] => $item['kelurahan'],
            $table[14] => $item['kecamatan'],
            $table[15] => $item['status-operasi'],
            $table[16] => null,
            $table[17] => null,
        ])->toArray();
    }
}
