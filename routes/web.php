<?php

use App\Http\Controllers\Api\UserKelurahanController as ApiUserKelurahanController;
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
use App\Http\Controllers\GeocodingController;
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

// geocoding proxy
Route::controller(GeocodingController::class)->prefix('api/geocoding')->group(function () {
    Route::get('/search', 'search')->name('geocoding.search');
    Route::get('/reverse', 'reverse')->name('geocoding.reverse');
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

// API: Get user's kelurahan and kecamatan
Route::get('/api/user-kelurahan', [ApiUserKelurahanController::class, 'getUserKelurahan'])
    ->middleware('auth')
    ->name('api.user-kelurahan');

// restoran
Route::resource('restoran', RestoranController::class)->middleware('admin-own-data');

// jasa boga
Route::resource('jasa-boga-katering', JasaBogaKateringController::class)->middleware('admin-own-data');

// rumah makan
Route::resource('rumah-makan', RumahMakanController::class)->middleware('admin-own-data');

// sentra kantin
Route::resource('kantin', KantinController::class)->middleware('admin-own-data');

// sentra kantin - gerai
Route::resource('gerai-kantin', GeraiKantinController::class)->except('create')->middleware('admin-own-data');
Route::get('gerai-kantin/create/{kantin}', [GeraiKantinController::class, 'create'])->name('gerai-kantin.create')->middleware('admin-own-data');

// depot air minum
Route::resource('depot-air-minum', DepotAirMinumController::class)->middleware('admin-own-data');

// sumur gali
Route::resource('sumur-gali', SumurGaliController::class)->middleware('admin-own-data');

// sumur bor dengan pompa
Route::resource('sumur-bor-pompa', SumurBorPompaController::class)->middleware('admin-own-data');

// perpipaan pdam
Route::resource('perpipaan', PerpipaanController::class)->middleware('admin-own-data');

// perpipaan non pdam
Route::resource('perpipaan-non-pdam', PerpipaanNonPDAMController::class)->middleware('admin-own-data');

// perlindungan mata air
Route::resource('perlindungan-mata-air', PerlindunganMataAirController::class)->middleware('admin-own-data');

// penyimpanan air hujan
Route::resource('penyimpanan-air-hujan', PenyimpananAirHujanController::class)->middleware('admin-own-data');

// gerai pangan jajanan
Route::resource('gerai-pangan-jajanan', GeraiPanganJajananController::class)->middleware('admin-own-data');

// gerai pangan jajanan keliling
Route::resource('gerai-jajanan-keliling', GeraiJajananKelilingController::class)->middleware('admin-own-data');

// rumah sakit
Route::resource('rumah-sakit', RumahSakitController::class)->middleware('admin-own-data');

// sekolah
Route::resource('sekolah', SekolahController::class)->middleware('admin-own-data');

// puskesmas
Route::resource('puskesmas', PuskesmasController::class)->parameters(['puskesmas' => 'puskesmas'])->middleware('admin-own-data');

// tempat rekreasi
Route::resource('tempat-rekreasi', TempatRekreasiController::class)->middleware('admin-own-data');

// kolam renang
Route::resource('renang-pemandian', RenangPemandianController::class)->middleware('admin-own-data');

// akomodasi
Route::resource('akomodasi', AkomodasiController::class)->middleware('admin-own-data');

// akomodasi lainnya
Route::resource('akomodasi-lain', AkomodasiLainController::class)->middleware('admin-own-data');

// gelanggang olahraga
Route::resource('tempat-olahraga', TempatOlahragaController::class)->middleware('admin-own-data');

// pasar
Route::resource('pasar', PasarController::class)->middleware('admin-own-data');

// pasar internal
Route::resource('pasar-internal', PasarInternalController::class)->middleware('admin-own-data');

// tempat ibadah
Route::resource('tempat-ibadah', TempatIbadahController::class)->middleware('admin-own-data');

// stasiun
Route::resource('stasiun', StasiunController::class)->middleware('admin-own-data');
