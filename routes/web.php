<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GestionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//metodo GET

Route::get('/dashboard', [GestionController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/motivate', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('motivate');
Route::get('/recordatorios', function () { return Inertia::render('Recordatorios');})->middleware(['auth', 'verified'])->name('recordatorios');
Route::get('/gestionar', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('gestionar');
Route::get('/postventa', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('postventa');
Route::get('/reportes', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('reportes');
Route::get('/busqueda', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('busqueda');
Route::get('/calendario', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('calendario');
Route::get('/mapa', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('mapa');
Route::get('/gestion_calendario', function () { return Inertia::render('GestionCalendario');})->middleware(['auth', 'verified'])->name('gestion_calendario');

//metodo POST

Route::post('/cliente',[ClienteController::class, 'store'])->middleware(['auth', 'verified']);
Route::post('/gestion_acepto',[GestionController::class, 'acepto'])->middleware(['auth', 'verified']);
Route::post('/gestion',[GestionController::class, 'otras'])->middleware(['auth', 'verified']);
Route::post('/getDataClient',[ClienteController::class, 'getData'])->middleware(['auth', 'verified'])->name('getDataClient');
Route::post('/getComentarios',[ClienteController::class, 'getComentarios'])->middleware(['auth', 'verified'])->name('getComentarios');
Route::post('/getLlamadas',[GestionController::class, 'getLlamadas'])->middleware(['auth', 'verified'])->name('getLlamadas');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
