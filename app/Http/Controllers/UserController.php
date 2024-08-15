<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use App\Models\Permiso;

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
    
}
