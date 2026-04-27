<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SuratJalanController;

// ─── Auth ───────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('surat-jalan.index'));

    // ── Profile ──────────────────────────────────────────────────────────────
    Route::put('/profile/info',     [ProfileController::class, 'updateInfo'])->name('profile.info');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ── Surat Jalan ──────────────────────────────────────────────────────────
    Route::get('/surat-jalan', [SuratJalanController::class, 'index'])->name('surat-jalan.index');
    Route::get('/surat-jalan/json', [SuratJalanController::class, 'getBySuratJalanJson'])->name('surat-jalan.json');
    Route::get('/surat-jalan/create', [SuratJalanController::class, 'create'])->name('surat-jalan.create');
    Route::post('/surat-jalan', [SuratJalanController::class, 'store'])->name('surat-jalan.store');
    Route::get('/surat-jalan/{id}/print', [SuratJalanController::class, 'show'])->name('surat-jalan.show');

    // ── Admin-only Surat Jalan actions ───────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::get('/surat-jalan/deleted', [SuratJalanController::class, 'deleted'])->name('surat-jalan.deleted');
        Route::put('/surat-jalan/{suratJalan}/status', [SuratJalanController::class, 'updateStatus'])->name('surat-jalan.status');
        Route::delete('/surat-jalan/{suratJalan}', [SuratJalanController::class, 'destroy'])->name('surat-jalan.destroy');
    });

    // ── Master Barang ────────────────────────────────────────────────────────
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    Route::middleware('admin')->group(function () {
        Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
        Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
        Route::post('/barang/import', [BarangController::class, 'import'])->name('barang.import');
    });

    // ── Projects ─────────────────────────────────────────────────────────────
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

    Route::middleware('admin')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // ─── Project Export ──────────────────────────────────────────────────────
    Route::get('/projects/{project}/export-pdf', [ProjectController::class, 'exportPdf'])->name('projects.export-pdf');
});
