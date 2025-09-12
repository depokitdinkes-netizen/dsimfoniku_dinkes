<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormIKL\AkomodasiController;
use App\Http\Controllers\FormIKL\AkomodasiLainController;
use App\Http\Controllers\FormIKL\DepotAirMinumController;
use App\Http\Controllers\FormIKL\GeraiJajananKelilingController;
use App\Http\Controllers\FormIKL\GeraiKantinController;
use App\Http\Controllers\FormIKL\GeraiPanganJajananController;
use App\Http\Controllers\FormIKL\JasaBogaKateringController;
use App\Http\Controllers\FormIKL\KantinController;
use App\Http\Controllers\FormIKL\PasarController;
use App\Http\Controllers\FormIKL\PasarInternalController;
use App\Http\Controllers\FormIKL\PenyimpananAirHujanController;
use App\Http\Controllers\FormIKL\PerlindunganMataAirController;
use App\Http\Controllers\FormIKL\PerpipaanController;
use App\Http\Controllers\FormIKL\PerpipaanNonPDAMController;
use App\Http\Controllers\FormIKL\PuskesmasController;
use App\Http\Controllers\FormIKL\RenangPemandianController;
use App\Http\Controllers\FormIKL\RestoranController;
use App\Http\Controllers\FormIKL\RumahMakanController;
use App\Http\Controllers\FormIKL\RumahSakitController;
use App\Http\Controllers\FormIKL\SekolahController;
use App\Http\Controllers\FormIKL\StasiunController;
use App\Http\Controllers\FormIKL\SumurBorPompaController;
use App\Http\Controllers\FormIKL\SumurGaliController;
use App\Http\Controllers\FormIKL\TempatIbadahController;
use App\Http\Controllers\FormIKL\TempatOlahragaController;
use App\Http\Controllers\FormIKL\TempatRekreasiController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// dashboard
Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');

// auth
Route::view('/login', 'pages.login')->name('login');
Route::controller(AuthController::class)->group(function () {
    Route::post('/auth', 'auth')->name('auth');
    Route::post('/deauth', 'deauth')->name('logout')->middleware('auth');
});

// inspection
Route::get('/inspeksi', fn() => view('pages.inspection.index', ['page_name' => 'inspection']))->name('inspection');

// history
Route::controller(PageController::class)->group(function () {
    Route::get('/histori-hasil-inspeksi', 'history')->name('history')->middleware(['auth', 'not-user']);
    Route::get('/archived-histori', 'archived')->name('archived')->middleware('superadmin');
});

// user
Route::resource('manajemen-user', UserController::class)->middleware('auth');
Route::resource('manajemen-user', UserController::class)->only(['index'])->middleware('not-user');
Route::resource('manajemen-user', UserController::class)->only(['store', 'destroy'])->middleware('superadmin');

// kop surat preview
Route::controller(App\Http\Controllers\KopSuratController::class)->middleware('auth')->group(function () {
    Route::get('/preview-kop-surat-pdf', 'preview')->name('kop-surat.preview.pdf');
});

// restoran
Route::resource('restoran', RestoranController::class)->middleware('admin-own-data');
Route::resource('restoran', RestoranController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('restoran', RestoranController::class)->only(['destroy'])->middleware('superadmin');

// jasa boga
Route::resource('jasa-boga-katering', JasaBogaKateringController::class)->middleware('admin-own-data');
Route::resource('jasa-boga-katering', JasaBogaKateringController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('jasa-boga-katering', JasaBogaKateringController::class)->only(['destroy'])->middleware('superadmin');

// rumah makan
Route::resource('rumah-makan', RumahMakanController::class)->middleware('admin-own-data');
Route::resource('rumah-makan', RumahMakanController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('rumah-makan', RumahMakanController::class)->only(['destroy'])->middleware('superadmin');

// sentra kantin
Route::resource('kantin', KantinController::class)->middleware('admin-own-data');
Route::resource('kantin', KantinController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('kantin', KantinController::class)->only(['destroy'])->middleware('superadmin');

// sentra kantin - gerai
Route::resource('gerai-kantin', GeraiKantinController::class)->except('create')->middleware('admin-own-data');
Route::resource('gerai-kantin', GeraiKantinController::class)->except('create')->only(['edit', 'update'])->middleware('not-user');
Route::resource('gerai-kantin', GeraiKantinController::class)->except('create')->only(['destroy'])->middleware('superadmin');
Route::get('gerai-kantin/create/{kantin}', [GeraiKantinController::class, 'create'])->name('gerai-kantin.create')->middleware('admin-own-data');

// depot air minum
Route::resource('depot-air-minum', DepotAirMinumController::class);
Route::resource('depot-air-minum', DepotAirMinumController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('depot-air-minum', DepotAirMinumController::class)->only(['destroy'])->middleware('superadmin');

// sumur gali
Route::resource('sumur-gali', SumurGaliController::class);
Route::resource('sumur-gali', SumurGaliController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('sumur-gali', SumurGaliController::class)->only(['destroy'])->middleware('superadmin');

// sumur bor dengan pompa
Route::resource('sumur-bor-pompa', SumurBorPompaController::class);
Route::resource('sumur-bor-pompa', SumurBorPompaController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('sumur-bor-pompa', SumurBorPompaController::class)->only(['destroy'])->middleware('superadmin');

// perpipaan pdam
Route::resource('perpipaan', PerpipaanController::class);
Route::resource('perpipaan', PerpipaanController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('perpipaan', PerpipaanController::class)->only(['destroy'])->middleware('superadmin');

// perpipaan non pdam
Route::resource('perpipaan-non-pdam', PerpipaanNonPDAMController::class);
Route::resource('perpipaan-non-pdam', PerpipaanNonPDAMController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('perpipaan-non-pdam', PerpipaanNonPDAMController::class)->only(['destroy'])->middleware('superadmin');

// perlindungan mata air
Route::resource('perlindungan-mata-air', PerlindunganMataAirController::class);
Route::resource('perlindungan-mata-air', PerlindunganMataAirController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('perlindungan-mata-air', PerlindunganMataAirController::class)->only(['destroy'])->middleware('superadmin');

// penyimpanan air hujan
Route::resource('penyimpanan-air-hujan', PenyimpananAirHujanController::class);
Route::resource('penyimpanan-air-hujan', PenyimpananAirHujanController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('penyimpanan-air-hujan', PenyimpananAirHujanController::class)->only(['destroy'])->middleware('superadmin');

// gerai pangan jajanan
Route::resource('gerai-pangan-jajanan', GeraiPanganJajananController::class);
Route::resource('gerai-pangan-jajanan', GeraiPanganJajananController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('gerai-pangan-jajanan', GeraiPanganJajananController::class)->only(['destroy'])->middleware('superadmin');

// gerai pangan jajanan keliling
Route::resource('gerai-jajanan-keliling', GeraiJajananKelilingController::class);
Route::resource('gerai-jajanan-keliling', GeraiJajananKelilingController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('gerai-jajanan-keliling', GeraiJajananKelilingController::class)->only(['destroy'])->middleware('superadmin');

// rumah sakit
Route::resource('rumah-sakit', RumahSakitController::class);
Route::resource('rumah-sakit', RumahSakitController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('rumah-sakit', RumahSakitController::class)->only(['destroy'])->middleware('superadmin');

// sekolah
Route::resource('sekolah', SekolahController::class);
Route::resource('sekolah', SekolahController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('sekolah', SekolahController::class)->only(['destroy'])->middleware('superadmin');

// puskesmas
Route::resource('puskesmas', PuskesmasController::class)->parameters(['puskesmas' => 'puskesmas']);
Route::resource('puskesmas', PuskesmasController::class)->parameters(['puskesmas' => 'puskesmas'])->only(['edit', 'update'])->middleware('not-user');
Route::resource('puskesmas', PuskesmasController::class)->parameters(['puskesmas' => 'puskesmas'])->only(['destroy'])->middleware('superadmin');

// tempat rekreasi
Route::resource('tempat-rekreasi', TempatRekreasiController::class);
Route::resource('tempat-rekreasi', TempatRekreasiController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('tempat-rekreasi', TempatRekreasiController::class)->only(['destroy'])->middleware('superadmin');

// kolam renang
Route::resource('renang-pemandian', RenangPemandianController::class);
Route::resource('renang-pemandian', RenangPemandianController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('renang-pemandian', RenangPemandianController::class)->only(['destroy'])->middleware('superadmin');

// akomodasi
Route::resource('akomodasi', AkomodasiController::class);
Route::resource('akomodasi', AkomodasiController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('akomodasi', AkomodasiController::class)->only(['destroy'])->middleware('superadmin');

// akomodasi lainnya
Route::resource('akomodasi-lain', AkomodasiLainController::class);
Route::resource('akomodasi-lain', AkomodasiLainController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('akomodasi-lain', AkomodasiLainController::class)->only(['destroy'])->middleware('superadmin');

// gelanggang olahraga
Route::resource('tempat-olahraga', TempatOlahragaController::class);
Route::resource('tempat-olahraga', TempatOlahragaController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('tempat-olahraga', TempatOlahragaController::class)->only(['destroy'])->middleware('superadmin');

// pasar
Route::resource('pasar', PasarController::class);
Route::resource('pasar', PasarController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('pasar', PasarController::class)->only(['destroy'])->middleware('superadmin');

// pasar internal
Route::resource('pasar-internal', PasarInternalController::class);
Route::resource('pasar-internal', PasarInternalController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('pasar-internal', PasarInternalController::class)->only(['destroy'])->middleware('superadmin');

// pasar
Route::resource('tempat-ibadah', TempatIbadahController::class);
Route::resource('tempat-ibadah', TempatIbadahController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('tempat-ibadah', TempatIbadahController::class)->only(['destroy'])->middleware('superadmin');

// stasiun
Route::resource('stasiun', StasiunController::class);
Route::resource('stasiun', StasiunController::class)->only(['edit', 'update'])->middleware('not-user');
Route::resource('stasiun', StasiunController::class)->only(['destroy'])->middleware('superadmin');
