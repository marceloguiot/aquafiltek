<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\Permiso;
use App\Models\Descanso;
use App\Models\UserEscala;
use App\Models\TiraInformativa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function fetchPermisos()
    {
        // Assuming the request contains the user ID or other identifier
        $usuarioActual = Auth::user();

        $id_user = $usuarioActual->id;

        // Fetch the permisos based on the user ID
        $permisos = Permiso::where('id_user', $id_user)->select('editar', 'inactivar')->first();

        // Check if the permisos were found
        if ($permisos) {
            return response()->json([
                'editar' => $permisos->editar,
                'inactivar' => $permisos->inactivar,
            ]);
        } else {
            return response()->json(['error' => 'Permisos not found'], 404);
        }
    }

    public function guardarEscalas(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'escalas' => 'required|array',
            'escalas.*.escala_id' => 'required|in:2,4,6,8,10',
            'escalas.*.numero' => 'required|integer|min:0',
        ]);

        // Iterar sobre las escalas y guardarlas en la base de datos
        foreach ($validatedData['escalas'] as $escalaData) {
            UserEscala::updateOrCreate(
                [
                    'usuario_id' => $validatedData['usuario_id'],
                    'escala_id' => $escalaData['escala_id'],
                ],
                [
                    'numero' => $escalaData['numero'],
                    'fecha' => now(),
                ]
            );
        }

        // Responder con un mensaje de éxito
        return response()->json(['message' => 'Escalas guardadas exitosamente.'], 200);
    }

    public function guardarTira(Request $request){

    $validatedData = $request->validate([
        'permanente' => 'required|boolean',
        'mensaje' => 'required|string',
        'minutos' => 'required|integer',
    ]);

    $tiraInformativa = TiraInformativa::create($validatedData);

    return response()->json($tiraInformativa, 201);
    }

    public function getTiraInformativa()
{
    $mensajes = DB::table('tira_informativa')->get();
    return response()->json($mensajes);
}
public function getDailyGestiones()
{
    // Obtener la fecha de hoy
    $hoy = Carbon::today();

    // Contar las gestiones del día de hoy en cada tabla
    $gestionesCount = DB::table('gestiones')
        ->whereDate('fecha', $hoy)
        ->count();

    $gestionesAceptadasCount = DB::table('gestiones_aceptadas')
        ->whereDate('fecha_acepto', $hoy)
        ->count();

    $gestionesInactivosCount = DB::table('gestion_inactivos')
        ->whereDate('fecha', $hoy)
        ->count();

    // Sumar todas las gestiones
    $totalGestiones = $gestionesCount + $gestionesAceptadasCount + $gestionesInactivosCount;

    return response()->json([
        'gestionesCount' => $gestionesCount,
        'gestionesAceptadasCount' => $gestionesAceptadasCount,
        'gestionesInactivosCount' => $gestionesInactivosCount,
        'totalGestiones' => $totalGestiones,
    ]);
}

public function guardarDescanso(Request $request){
    try{
        $usuarioActual = Auth::user();

        $id_user = $usuarioActual->id;
        $hoy = Carbon::today();
    
        $descanso = new Descanso();
        $descanso->id_user = $id_user;
        $descanso->fecha = $hoy;
        $descanso->tiempo = $request->tiempo;
        $descanso->tipo = 'Descanso';
        $descanso->save();
    
        return response()->json(['message' => 'exito'], 200);
    }
    catch(Exception $e){
        return response()->json(['message' => 'error']);
    }
   
}
    
}
