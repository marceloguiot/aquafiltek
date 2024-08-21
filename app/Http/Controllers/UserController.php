<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\Permiso;
use App\Models\UserEscala;
use App\Models\TiraInformativa;
use Illuminate\Support\Facades\DB;

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
    
}
