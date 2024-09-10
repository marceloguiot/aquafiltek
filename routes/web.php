<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProvinciaController;



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
Route::get('/gestiones', [GestionController::class, 'getClients'])->middleware(['auth', 'verified'])->name('gestiones');

Route::get('/motivate', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('motivate');
Route::get('/recordatorios', function () { return Inertia::render('Recordatorios');})->middleware(['auth', 'verified'])->name('recordatorios');
Route::get('/gestionar', function () { return Inertia::render('PorGestionar');})->middleware(['auth', 'verified'])->name('gestionar');
Route::get('/postventa', function () { return Inertia::render('Motivacion');})->middleware(['auth', 'verified'])->name('postventa');
Route::get('/reportes', function () { return Inertia::render('Reportes');})->middleware(['auth', 'verified'])->name('reportes');
Route::get('/clientes-por-gestionar', [GestionController::class, 'getClientesPorGestionar']);

Route::get('/busqueda', function () { return Inertia::render('Filtros');})->middleware(['auth', 'verified'])->name('busqueda');
Route::get('/calendario', function () { return Inertia::render('VistaCalendario');})->middleware(['auth', 'verified'])->name('calendario');
Route::get('/mapa', function () { return Inertia::render('GestionMapa');})->middleware(['auth', 'verified'])->name('mapa');
Route::get('/gestion_calendario', function () { return Inertia::render('GestionCalendario');})->middleware(['auth', 'verified'])->name('gestion_calendario');
Route::get('/llamadas/upcoming', [GestionController::class, 'getUpcomingLlamadas'])->name('llamadas.upcoming');
Route::get('/proximas_gestiones', [GestionController::class, 'getProximasGestiones'])->middleware('auth')->name('proximas_gestiones');
Route::get('/previas_gestiones', [GestionController::class, 'getUltimasGestiones'])->middleware('auth')->name('previas_gestiones');
Route::get('/postventa', function () { return Inertia::render('Postventa');})->middleware(['auth', 'verified'])->name('postventa');
Route::get('/gestiones-aceptadas', [GestionController::class, 'getGestionesAceptadas'])->middleware(['auth', 'verified'])->name('gestiones-aceptadas');
Route::get('/cliente-nombre/{id_gestion}', [GestionController::class, 'getClienteNombre'])->middleware(['auth', 'verified'])->name('cliente-nombre');
Route::get('/admin', function () { return Inertia::render('VistaAdm');})->middleware(['auth', 'verified'])->name('admin');
Route::get('/users', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('users');
Route::get('api/provincias', [ProvinciaController::class, 'index'])->middleware(['auth', 'verified'])->name('provincias');
Route::get('api/cantones', [CantonController::class, 'index'])->middleware(['auth', 'verified'])->name('cantones');
Route::get('api/parroquias', [ParroquiaController::class, 'index'])->middleware(['auth', 'verified'])->name('parroquias');
Route::get('/api/inactive-clients', [ClienteController::class, 'getInactiveClients'])->middleware(['auth', 'verified'])->name('inactive-clients');
Route::get('/api/archived-clients', [ClienteController::class, 'getArchivedClients'])->middleware(['auth', 'verified'])->name('archived-clients');
Route::get('/api/new-clients', [ClienteController::class, 'getNewClients']);
Route::get('/permisos', [UserController::class, 'fetchPermisos'])->middleware(['auth', 'verified'])->name('permisos');
Route::get('/tira-informativa', [UserController::class, 'getTiraInformativa'])->middleware(['auth', 'verified'])->name('getTira');
Route::get('/gestiones-diarias', [UserController::class, 'getDailyGestiones']);
Route::get('/llamadas-programadas', [GestionController::class, 'getScheduledCalls']);





//metodo POST
Route::post('/cliente',[ClienteController::class, 'store'])->middleware(['auth', 'verified']);
Route::post('/gestion_acepto',[GestionController::class, 'acepto'])->middleware(['auth', 'verified']);
Route::post('/gestion',[GestionController::class, 'otras'])->middleware(['auth', 'verified']);
Route::post('/gestion-averiado',[GestionController::class, 'averiado'])->middleware(['auth', 'verified']);
Route::post('/getDataClient',[ClienteController::class, 'getData'])->middleware(['auth', 'verified'])->name('getDataClient');
Route::post('/getComentarios',[ClienteController::class, 'getComentarios'])->middleware(['auth', 'verified'])->name('getComentarios');
Route::post('/getLlamadas',[GestionController::class, 'getLlamadas'])->middleware(['auth', 'verified'])->name('getLlamadas');
Route::post('/getPasadas',[GestionController::class, 'getFilteredGestiones'])->middleware(['auth', 'verified'])->name('getPasadas');
Route::post('/edprecio',[GestionController::class, 'actualizarPrecio'])->middleware(['auth', 'verified'])->name('edprecio');
Route::post('/cliente_actualizar',[ClienteController::class, 'actualizar'])->middleware(['auth', 'verified'])->name('cliente_actualizar');
Route::post('/getClientSearch', [ClienteController::class, 'getDataClient'])->middleware(['auth', 'verified'])->name('getClientSearch');
Route::post('/getReport', [GestionController::class, 'getReport'])->middleware(['auth', 'verified'])->name('getReport');
Route::post('/getReportNoGest', [GestionController::class, 'getReportNoGest'])->middleware(['auth', 'verified'])->name('getReportNoGest');
Route::post('/getFilteredData', [GestionController::class, 'getFilteredData'])->middleware(['auth', 'verified'])->name('getFilteredData');
Route::post('/postventa', [GestionController::class, 'storePostventa'])->middleware(['auth', 'verified']);
Route::post('/api/getGestiones', [GestionController::class, 'getGestiones'])->middleware(['auth', 'verified']);
Route::post('/eliminar-gestion', [GestionController::class, 'eliminarGestion'])->middleware(['auth', 'verified']);
Route::post('/editar-gestion', [GestionController::class, 'editarGestion'])->middleware(['auth', 'verified'])->name('editar-gestion');
Route::post('/editar-acepto', [GestionController::class, 'editarAcepto'])->middleware(['auth', 'verified'])->name('editar-acepto');
Route::post('/editar-acepto', [GestionController::class, 'editarAcepto'])->middleware(['auth', 'verified'])->name('editar-acepto');
Route::post('/cliente/guardar-permisos', [ClienteController::class, 'guardarPermisos'])->middleware(['auth', 'verified'])->name('cliente.guardarPermisos');
Route::post('/grupos-poblacionales', [ClienteController::class, 'guardarPermisos'])->middleware(['auth', 'verified'])->name('grupos-poblacionales');
Route::post('/cliente/inactivar', [ClienteController::class, 'registrarInactivacion'])->middleware(['auth', 'verified'])->name('cliente.inactivar');
Route::post('/cliente/archivar', [ClienteController::class, 'archivarCliente'])->middleware(['auth', 'verified'])->name('cliente.archivar');
Route::post('/cliente/desarchivar', [ClienteController::class, 'desarchivarCliente'])->middleware(['auth', 'verified'])->name('cliente.desarchivar');
Route::post('/cliente/reactivar', [ClienteController::class, 'activarCliente'])->middleware(['auth', 'verified'])->name('cliente.reactivar');
Route::post('/guardar-escalas', [UserController::class, 'guardarEscalas'])->middleware(['auth', 'verified'])->name('guardar.escalas');
Route::post('/tira-informativa', [UserController::class, 'guardarTira'])->middleware(['auth', 'verified'])->name('guardar.tira');
Route::post('/guardar-descanso', [UserController::class, 'guardarDescanso'])->middleware(['auth', 'verified'])->name('guardar.descanso');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
