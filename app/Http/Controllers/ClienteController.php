<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Comentarios;
use App\Models\GestionInactivos;
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
    // Retrieve all clients where 'inactivo' is 1, join with gestion_inactivos and users
    $inactiveClients = Cliente::where('clientes.inactivo', 1)
        ->leftJoin('gestion_inactivos', 'clientes.codigo', '=', 'gestion_inactivos.codigo_cliente')
        ->leftJoin('users', 'gestion_inactivos.id_operador', '=', 'users.id')
        ->select(
            'clientes.*', 
            'gestion_inactivos.motivo', 
            'gestion_inactivos.fecha', 
            'gestion_inactivos.hora', 
            'users.name as operador_name'
        )
        ->get();

    // Return the result as a JSON response
    return response()->json($inactiveClients);
}

public function getArchivedClients()
{
    $archivedClients = Cliente::where('clientes.inactivo', 1)
    ->where('clientes.archivado', 1)
    ->leftJoin('gestion_inactivos', 'clientes.codigo', '=', 'gestion_inactivos.codigo_cliente')
    ->leftJoin('users', 'gestion_inactivos.id_operador', '=', 'users.id')
    ->select(
        'clientes.*', 
        'gestion_inactivos.motivo', 
        'gestion_inactivos.fecha', 
        'gestion_inactivos.hora', 
        'users.name as operador_name'
    )
    ->get();

    // Return the result as a JSON response
    return response()->json($archivedClients);
}

public function archivarCliente(Request $request)
{
    // Buscar el cliente por su código
    $cliente = Cliente::where('codigo', $request->id_cliente)->first();

    if (!$cliente) {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }

    // Archivar el cliente (establecer 'archivado' en 1)
    $cliente->archivado = 1;
    $cliente->save();

    return response()->json(['message' => 'exito']);
}

public function desarchivarCliente(Request $request)
{
    // Buscar el cliente por su código
    $cliente = Cliente::where('codigo', $request->id_cliente)->first();

    if (!$cliente) {
        return response()->json(['message' => 'Cliente no encontrado'], 404);
    }

    $cliente->archivado = 0;
    $cliente->save();

    return response()->json(['message' => 'exito']);
}

public function registrarInactivacion(Request $request)
{
    // Validate the request
    $validatedData = $request->validate([
        'id_cliente' => 'required|string',  // Assuming 'clientes' is the table where clients are stored
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

    $cliente = Cliente::where('codigo', $validatedData['id_cliente'])->first();

if ($cliente) {
    $cliente->inactivo = 1;
    $cliente->save();
} else {
    // Manejar el caso donde el cliente no fue encontrado
}

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Inactivation registered successfully',
        'data' => $gestionInactivo
    ]);
}

public function activarCliente(Request $request)
{
    // Actualizar el campo 'inactivo' a 0 en la tabla 'clientes'
    $cliente = Cliente::where('codigo', $request->codigo)->first();
    if ($cliente) {
        $cliente->inactivo = 0;
        $cliente->save();

        // Eliminar el registro asociado en la tabla 'gestion_inactivos'
        GestionInactivos::where('codigo_cliente', $request->codigo)->delete();

        return response()->json(['message' => 'exito'], 200);
    }

    return response()->json(['message' => 'Cliente no encontrado'], 404);
}


}
