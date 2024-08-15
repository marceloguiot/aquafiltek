<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Comentarios;
use App\Models\Permiso;

class ClienteController extends Controller
{

    public function store(Request $request)
    {
        $request->estado = 'Por gestionar';
        $request->lng = '-79.8720240';
        $request->validate([
            'codigo' => 'required|unique:clientes',
            'coordenadas' => 'required',
            'nombre_cliente' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'correo' => 'required|email|unique:clientes',
        ]);

        $coordenadas = explode(',', $request->input('coordenadas'));
        $lng = $coordenadas[0];
        $lat = $coordenadas[1];
        $request->request->add(['lng' => $lng]);
        $request->request->add(['lat' => $lat]);
        //agregarmos el estado
        $request->request->add(['estado' => 'Por gestionar']);

        Cliente::create($request->all());

        return 'Listo';
    }

    public function getData(Request $request){
         // Validate the request input
         $request->validate([
            'id' => 'required|max:255',
        ]);

        // Retrieve the client by codigo
        $cliente = Cliente::where('codigo', $request->input('id'))->first();

        // Check if the client exists
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        // Return the client data
        return response()->json($cliente, 200);
    }
    public function getComentarios(Request $request){
         // Retrieve the client by codigo
         $comentarios = Comentarios::where('id_cliente', $request->input('id'))
         ->join('users', 'comentarios.id_operador', '=', 'users.id')
         ->orderBy('fecha_gestion', 'desc')
         ->orderBy('hora_gestion', 'desc')
         ->select('comentarios.*', 'users.name as operador_name')
         ->get();
         return response()->json($comentarios, 200);
    }

    public function actualizar(Request $request){
         // Encontrar el cliente por su código
         $cliente = Cliente::where('codigo', $request->codigo)->first();
         $cliente->update($request->all());
        
    }

    public function getDataClient(Request $request)
{
    $field = $request->input('field');
    $value = $request->input('value');

    // Asegúrate de validar el campo y el valor aquí

    $clientes = Cliente::where($field, 'LIKE', "%{$value}%")->get();

    return response()->json($clientes);
}

public function guardarPermisos(Request $request)
{


        $user = $request->id_user;
        $clientes = filter_var($request->clientes, FILTER_VALIDATE_BOOLEAN);
        $inactivar = filter_var($request->inactivacion, FILTER_VALIDATE_BOOLEAN);
    // Buscar si ya existe un registro de permisos para el usuario
    $permiso = Permiso::where('id_user', $user)->first();

    if ($permiso) {
        // Actualizar el permiso existente
        $permiso->editar = $clientes;
        $permiso->inactivar = $inactivar;
        $permiso->save();
    } else {
        // Crear un nuevo registro de permisos
        Permiso::create([
            'id_user' => $request->id_user,
            'editar' => $clientes,
            'inactivar' => $inactivar
        ]);
    }

    // Redireccionar o devolver una respuesta exitosa
    return response()->json('Listo', 200);
}


public function getNewClients()
{
   
    $newClients = Cliente::where('estado', 'Nuevo')->get();
    return response()->json($newClients);
}

public function getInactiveClients()
{
    // Retrieve all clients where 'inactivo' is 1
    $inactiveClients = Cliente::where('inactivo', 1)->get();

    // Return the result as a JSON response
    return response()->json($inactiveClients);
}

public function registrarInactivacion(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'id_cliente' => 'required|integer',  // Assuming 'clientes' is the table where clients are stored
        'motivo' => 'required|string|max:255',
    ]);

    // Get current date and time
    $fecha = now()->format('Y-m-d');
    $hora = now()->format('H:i:s');

    // Create the inactivation record
    $gestionInactivo = GestionInactivos::create([
        'id_operador' => auth()->id(),  // Assuming the logged-in user's ID is the operator
        'codigo_cliente' => $validatedData['id_cliente'],
        'motivo' => $validatedData['motivo'],
        'fecha' => $fecha,
        'hora' => $hora,
    ]);

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Inactivation registered successfully',
        'data' => $gestionInactivo
    ]);
}

}
