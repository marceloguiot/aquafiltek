<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cliente;
use App\Models\GestionAceptada;
use App\Models\Gestion;
use App\Models\HistorialPrecio;
use App\Models\LlamadasProgramadas;
use App\Models\OperadorCliente;
use App\Models\Comentarios;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Postventa;
use Illuminate\Support\Facades\Storage;

class GestionController extends Controller
{
    //
    

    public function index(Request $request){
         // Obtener la fecha de hoy
        $hoy = Carbon::today();

        // Definir el rango de fechas (por ejemplo, desde hoy hasta 7 días adelante)
        $fecha_inicio = $hoy;
        $fecha_fin = $hoy->copy()->addDays(7);

        $excluirCodigos = OperadorCliente::pluck('codigo_cliente');

        // Obtener las gestiones próximas cuyo código no está en la tabla operador_cliente
        $gestiones = Gestion::whereNotIn('codigo', $excluirCodigos)
            ->leftJoin('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
            ->select(
                'clientes.codigo',
                'clientes.nombre_cliente',
                'clientes.direccion',
                'clientes.telefono',
                DB::raw('MAX(gestiones.id) as gestion_id'), // Selecciona el ID máximo de gestión por cliente
                DB::raw('MAX(gestiones.fecha) as gestion_fecha') // Selecciona la fecha máxima de gestión por cliente
            )
            ->groupBy('clientes.codigo', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
            ->take(3)
            ->get();
        

        if ($gestiones->isEmpty()) {
            $clientes = Cliente::where('estado', 'Por gestionar')
                                ->whereNotIn('codigo', $excluirCodigos)
                                ->take(3) // Limitar a 5 registros
                                ->get();
                    
            $prox = response()->json($clientes);
        }
        else
        {
            
            $prox = response()->json($gestiones);
        }
                    

                // Obtener el usuario autenticado
                $usuarioActual = Auth::user();

                // Obtener las últimas gestiones del usuario actual
                $ultimasGestiones = Gestion::where('id_operador', $usuarioActual->id)
                    ->leftJoin('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
                    ->select('gestiones.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora', 'desc')
                    ->take(3) // Cambia el número de registros según sea necesario
                    ->get();
        
                // Obtener las últimas gestiones aceptadas del usuario actual
                $ultimasGestionesAceptadas = GestionAceptada::where('id_operador', $usuarioActual->id)
                    ->leftJoin('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
                    ->select('gestiones_aceptadas.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
                    ->orderBy('fecha_acepto', 'desc')
                    ->orderBy('hora_acepto', 'desc')
                    ->take(3) // Cambia el número de registros según sea necesario
                    ->get();
        
                // Combinar ambas colecciones
                $gestionesCombinadas = $ultimasGestiones->merge($ultimasGestionesAceptadas)
                    ->sortByDesc('fecha')
                    ->sortByDesc('hora')
                    ->take(3);
        
                // Devolver los datos en formato JSON
                $prev = response()->json($gestionesCombinadas);

                $clienteId = $request->input('id');
                $cliente_actual = null;
                if ($clienteId) {
                    $cliente_actual = Cliente::where('codigo', $clienteId)->first();
                }
       

                $all = [];
                $all['previas'] = $prev;
                $all['proximas'] = $prox;
                if($cliente_actual)
                {
                    $all['actual'] = $cliente_actual;
                }
                else
                {
                    if ($gestiones->isEmpty()) {
                    $all['actual'] = $clientes->first();
                    }
                    else
                    {
                        $all['actual'] = $gestiones->first();
                    }
                }
                


        return Inertia::render('Dashboard', ['datos' => $all]);
    }

    public function getClients(Request $request){
        // Obtener la fecha de hoy
       $hoy = Carbon::today();

       // Definir el rango de fechas (por ejemplo, desde hoy hasta 7 días adelante)
       $fecha_inicio = $hoy;
       $fecha_fin = $hoy->copy()->addDays(7);

       // Subconsulta para obtener los códigos de cliente que están en la tabla operador_cliente
       $excluirCodigos = OperadorCliente::pluck('codigo_cliente');

       // Obtener las gestiones próximas cuyo código no está en la tabla operador_cliente
       $gestiones = Gestion::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                           ->whereNotIn('codigo', $excluirCodigos)
                           ->leftJoin('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
                           ->select('gestiones.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
                           ->take(3)
                           ->get();

       if ($gestiones->isEmpty()) {
           $clientes = Cliente::where('estado', 'Por gestionar')
                               ->whereNotIn('codigo', $excluirCodigos)
                               ->take(3) // Limitar a 5 registros
                               ->get();
                   
           $prox = response()->json($clientes);
       }
       else
       {
           
           $prox = response()->json($gestiones);
       }
                   

               // Obtener el usuario autenticado
               $usuarioActual = Auth::user();

               // Obtener las últimas gestiones del usuario actual
               $ultimasGestiones = Gestion::where('id_operador', $usuarioActual->id)
                   ->leftJoin('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
                   ->select('gestiones.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
                   ->orderBy('fecha', 'desc')
                   ->orderBy('hora', 'desc')
                   ->take(3) // Cambia el número de registros según sea necesario
                   ->get();
       
               // Obtener las últimas gestiones aceptadas del usuario actual
               $ultimasGestionesAceptadas = GestionAceptada::where('id_operador', $usuarioActual->id)
                   ->leftJoin('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
                   ->select('gestiones_aceptadas.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
                   ->orderBy('fecha_acepto', 'desc')
                   ->orderBy('hora_acepto', 'desc')
                   ->take(3) // Cambia el número de registros según sea necesario
                   ->get();
       
               // Combinar ambas colecciones
               $gestionesCombinadas = $ultimasGestiones->merge($ultimasGestionesAceptadas)
                   ->sortByDesc('fecha')
                   ->sortByDesc('hora')
                   ->take(3);
       
               // Devolver los datos en formato JSON
               $prev = response()->json($gestionesCombinadas);

               $clienteId = $request->input('id');
               $cliente_actual = null;
               if ($clienteId) {
                   $cliente_actual = Cliente::where('codigo', $clienteId)->first();
               }
      

               $all = [];
               $all['previas'] = $prev;
               $all['proximas'] = $prox;
               if($cliente_actual)
               {
                   $all['actual'] = $cliente_actual;
               }
               else
               {
                   if ($gestiones->isEmpty()) {
                   $all['actual'] = $clientes->first();
                   }
                   else
                   {
                       $all['actual'] = $gestiones->first();
                   }
               }
               


       return $all;
   }

    public function acepto(Request $request){
        $user = Auth::user();
        $request->request->add(['fecha_gestion' => date("Y-m-d")]);
        $request->request->add(['id_operador' => $user->id]);
        try{
        GestionAceptada::create($request->all());

         //comentarios
         $comentario = [];
         $comentario['comentario'] = 'Acepto por '.$request->precio.' - '.$request->comentarios;
         $comentario['fecha'] = $request->fecha_acepto;
         $comentario['hora'] = $request->hora_acepto;
         $comentario['estado'] = 'Acepto';
         $comentario['id_cliente'] =  $request->codigo;
         $comentario['id_operador'] = $user->id;
         $comentario['fecha_gestion'] = date("Y-m-d");
         $comentario['hora_gestion'] = date("h:i:s");
         Comentarios::create($comentario);

         // Actualizar el estado del cliente
         $cliente = Cliente::where('codigo', $request->codigo)->first();
         if ($cliente) {
             $cliente->estado = 'acepto';
             $cliente->save();
         }

         if($request->ambito == 'llamadas')
         {
             $llamada = LlamadasProgramadas::find($request->id_llamada);
  
             // Actualizar el campo 'realizada' a 1
             $llamada->realizada = 1;
             $llamada->save();
         }

        return $request;
        }
        catch(Error $e){
           
        return 'error'.$e;
        }
        
    }

    public function otras(Request $request){
        $user = Auth::user();
        $request->request->add(['id_cliente' => 123123123]);
        $request->request->add(['fecha_gestion' => date("Y-m-d h:i:s")]);
        $request->request->add(['id_operador' => $user->id]);
        try{
        Gestion::create($request->all());

        //programar la llamada
        $llamada = [];
        $llamada['codigo'] = $request->codigo;
        $llamada['fecha_llamada'] = $request->fecha;
        $llamada['hora_llamada'] = $request->hora;
        $llamada['realizada'] = 0;
        $llamada['id_operador'] = $user->id;
        $llamada['fecha_programada'] = date("Y-m-d h:i:s");
        $llamada['timestamp'] = strtotime(date("Y-m-d h:i:s"));

        
        LlamadasProgramadas::create($llamada);

        //comentarios
        $comentario = [];
        $comentario['comentario'] = $request->comentarios;
        $comentario['fecha'] = $request->fecha;
        $comentario['hora'] = $request->hora;
        $comentario['estado'] = $request->tipo;
        $comentario['id_cliente'] =  $request->codigo;
        $comentario['id_operador'] = $user->id;
        $comentario['fecha_gestion'] = date("Y-m-d");
        $comentario['hora_gestion'] = date("h:i:s");
        Comentarios::create($comentario);

         // Actualizar el estado del cliente
         $cliente = Cliente::where('codigo', $request->codigo)->first();
         if ($cliente) {
             $cliente->estado = $request->tipo;
             $cliente->save();
         }


        if($request->ambito == 'llamadas')
        {
            $llamada = LlamadasProgramadas::find($request->id_llamada);
 
            // Actualizar el campo 'realizada' a 1
            $llamada->realizada = 1;
            $llamada->save();
        }




        return $request;
        }
        catch(Error $e){
           
        return 'error'.$e;
        }
        
    }

    public function averiado(Request $request){
        $user = Auth::user();
        $request->request->add(['id_cliente' => 123123123]);
        $request->request->add(['fecha_gestion' => date("Y-m-d h:i:s")]);
        $request->request->add(['fecha' => date("Y-m-d")]);
        $request->request->add(['hora' => date("h:i:s")]);
        $request->request->add(['id_operador' => $user->id]);
        try{
        Gestion::create($request->all());

       

        //comentarios
        $comentario = [];
        $comentario['comentario'] = $request->comentarios;
        $comentario['fecha'] = $request->fecha;
        $comentario['hora'] = $request->hora;
        $comentario['estado'] = $request->tipo;
        $comentario['id_cliente'] =  $request->codigo;
        $comentario['id_operador'] = $user->id;
        $comentario['fecha_gestion'] = date("Y-m-d");
        $comentario['hora_gestion'] = date("h:i:s");
        Comentarios::create($comentario);

         // Actualizar el estado del cliente
         $cliente = Cliente::where('codigo', $request->codigo)->first();
         if ($cliente) {
             $cliente->estado = $request->tipo;
             $cliente->save();
         }




        return $request;
        }
        catch(Error $e){
           
        return 'error'.$e;
        }
        
    }

    public function getLlamadas(){
        // Get today's date
        $today = Carbon::today();
        // Calculate the date seven days from today
        $sevenDaysAhead = $today->copy()->addDays(7);

        // Get the date and time 48 hours ago
        $twoDaysAgo = Carbon::now()->subHours(48);

        // Retrieve records and join with clientes table to get nombre_cliente
        $llamadas = LlamadasProgramadas::select('llamadas_programadas.*', 'clientes.nombre_cliente')
            ->join('clientes', 'llamadas_programadas.codigo', '=', 'clientes.codigo')
            ->where('llamadas_programadas.realizada', '=', 0)
            ->whereBetween('llamadas_programadas.fecha_llamada', [$today, $sevenDaysAhead])
            ->get();

            $llamadasVencidas = LlamadasProgramadas::select('llamadas_programadas.*', 'clientes.nombre_cliente')
            ->join('clientes', 'llamadas_programadas.codigo', '=', 'clientes.codigo')
            ->where('llamadas_programadas.fecha_llamada', '>=', $twoDaysAgo->toDateString())
            ->where('llamadas_programadas.fecha_llamada', '<', $today)
            ->where('llamadas_programadas.realizada', '=', 0)

            ->get();

        // Return the records as a JSON response
        return response()->json([
            'llamadas' => $llamadas,
            'llamadasVencidas' => $llamadasVencidas
        ]);
    }

    public function getUpcomingLlamadas()
    {
        $timezone = 'America/Mexico_City';
        $currentDate = Carbon::now($timezone)->toDateString();
        $currentTime = Carbon::now($timezone)->toTimeString();
        $timeWindowStart = Carbon::now($timezone)->subMinutes(5)->toTimeString();
        $timeWindowEnd = Carbon::now($timezone)->addMinutes(5)->toTimeString();
        
        

        // Filtrar los registros que cumplen las condiciones
        $llamadas = LlamadasProgramadas::where('fecha_llamada', $currentDate)
            ->where('hora_llamada', '>=', $timeWindowStart)
            ->where('hora_llamada', '<=', $timeWindowEnd)
            ->where('realizada', 0)
            ->where('id_operador', 1)
            ->get();

        // Devolver los datos en formato JSON
        return response()->json($llamadas);
    }




    public function getFilteredGestiones(Request $request)
    {
// Obtener registros de gestiones_aceptadas
$gestionesAceptadas = GestionAceptada::join('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
    ->where('gestiones_aceptadas.codigo', $request->id)
    ->select('gestiones_aceptadas.fecha_acepto', 'gestiones_aceptadas.hora_acepto', 'gestiones_aceptadas.precio', 'gestiones_aceptadas.id', 'gestiones_aceptadas.codigo', 'clientes.nombre_cliente')
    ->get();

// Obtener registros de gestiones donde tipo es 'competencia'
$gestionesCompetencia = Gestion::where('tipo', 'competencia')
    ->where('codigo', $request->id)
    ->select('fecha', 'hora', 'id', 'codigo', 'tipo')
    ->get();

// Combinar ambos resultados en una colección
$gestiones = $gestionesAceptadas->merge($gestionesCompetencia);

// Retornar los resultados combinados
return $gestiones;

        

// Devolver los datos en formato JSON
return response()->json($gestiones);

    }

    public function getReportNoGest(Request $request)
    {
        $fechaLimite = Carbon::now()->subMonths(6);

        // Formato de fecha esperado en la base de datos
        $formatoFecha = 'd/m/Y H:i:s';
    
        // Convertir la fecha límite al formato esperado
        $fechaLimiteFormateada = $fechaLimite->format($formatoFecha);
    
        // Buscar los registros en la tabla clientes donde fecha_gestion sea mayor a seis meses
        $clientesAntiguos = DB::table('clientes')
            ->where('fecha_gestion', '<', $fechaLimiteFormateada)
            ->get();
    
        return response()->json($clientesAntiguos);
    }

    public function actualizarPrecio(Request $request)
    {
        $user = Auth::user();
        $request->request->add(['fecha' => date("Y-m-d h:i:s")]);
        $request->request->add(['id_operador' => $user->id]);

        try{
            HistorialPrecio::create($request->all());
            //comentarios
         $comentario = [];
         $comentario['comentario'] = 'Se actualizo precio a '.$request->precio_ant.' de '.$request->precio_act.' por '.$request->motivo;
         $comentario['fecha'] = $request->fecha;
         $comentario['hora'] = date("h:i:s");
         $comentario['estado'] = 'Actualización de precio';
         $comentario['id_cliente'] =  $request->codigo;
         $comentario['id_operador'] = $user->id;
         $comentario['fecha_gestion'] = date("Y-m-d");
         $comentario['hora_gestion'] = date("h:i:s");
         Comentarios::create($comentario);

        }
        catch(Error $e){
           
            return 'error'.$e;
            }


    }

    public function getProximasGestiones(Request $request)
    {
        // Obtener la fecha de hoy
        $hoy = Carbon::today();

        // Definir el rango de fechas (por ejemplo, desde hoy hasta 7 días adelante)
        $fecha_inicio = $hoy;
        $fecha_fin = $hoy->copy()->addDays(7);

        // Subconsulta para obtener los códigos de cliente que están en la tabla operador_cliente
        $excluirCodigos = OperadorCliente::pluck('codigo_cliente');

        // Obtener las gestiones próximas cuyo código no está en la tabla operador_cliente
        $gestiones = Gestion::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                            ->whereNotIn('codigo', $excluirCodigos)
                            ->get();

        if ($gestiones->isEmpty()) {
            $clientes = Cliente::where('estado', 'Por gestionar')
                                ->whereNotIn('codigo', $excluirCodigos)
                                ->take(5) // Limitar a 5 registros
                                ->get();
                    
            return response()->json($clientes);
        }
                    

        // Devolver los datos en formato JSON
        return response()->json($gestiones);
    }

    public function getUltimasGestiones()
    {
        // Obtener el usuario autenticado
        $usuarioActual = Auth::user();

        // Obtener las últimas gestiones del usuario actual
        $ultimasGestiones = Gestion::where('id_operador', $usuarioActual->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->take(10) // Cambia el número de registros según sea necesario
            ->get();

        // Obtener las últimas gestiones aceptadas del usuario actual
        $ultimasGestionesAceptadas = GestionAceptada::where('id_operador', $usuarioActual->id)
            ->orderBy('fecha_acepto', 'desc')
            ->orderBy('hora_acepto', 'desc')
            ->take(10) // Cambia el número de registros según sea necesario
            ->get();

        // Combinar ambas colecciones
        $gestionesCombinadas = $ultimasGestiones->merge($ultimasGestionesAceptadas);

        // Devolver los datos en formato JSON
        return response()->json($gestionesCombinadas);
    }

    public function getReport(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

       // Obtener los registros en el rango de fechas
       $registros = GestionAceptada::whereBetween('fecha_acepto', [$startDate, $endDate])
       ->join('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
       ->join('users', 'gestiones_aceptadas.id_operador', '=', 'users.id')
       ->select(
           'gestiones_aceptadas.*',
           'clientes.nombre_cliente',
           'users.name as nombre_operador'
       )
       ->get();

   // Obtener los registros por operador
   $registrosPorOperador = GestionAceptada::whereBetween('fecha_acepto', [$startDate, $endDate])
       ->join('users', 'gestiones_aceptadas.id_operador', '=', 'users.id')
       ->select('id_operador', 'users.name as nombre_operador', DB::raw('count(*) as cantidad'))
       ->groupBy('id_operador', 'users.name')
       ->get();

   return response()->json([
       'registros' => $registros,
       'registrosPorOperador' => $registrosPorOperador
   ]);
}

public function getFilteredData(Request $request)
{
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $estado = $request->input('estado');

    $gestiones = Gestion::query()
        ->join('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
        ->join('users', 'gestiones.id_operador', '=', 'users.id')
        ->select(
            'gestiones.*',
            'clientes.nombre_cliente',
            'users.name as nombre_operador'
        )
        ->whereBetween('fecha', [$startDate, $endDate])
        ->where('tipo', $estado)
        ->get();

    if ($estado === 'acepto') {
        $gestionesAceptadas = GestionAceptada::query()
            ->join('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
            ->join('users', 'gestiones_aceptadas.id_operador', '=', 'users.id')
            ->select(
                'gestiones_aceptadas.*',
                'clientes.nombre_cliente',
                'users.name as nombre_operador'
            )
            ->whereBetween('fecha_acepto', [$startDate, $endDate])
            ->get();

        // Unir los resultados de las dos consultas
        $gestiones = $gestiones->merge($gestionesAceptadas);
    }

    return response()->json($gestiones);
}

public function storePostventa(Request $request)
{
    $request->validate([
        'trabajo_realizado' => 'required',
        'pago_realizado' => 'required',
        'fecha_ejecucion' => 'required|date',
        'informe' => 'required|file|mimes:pdf'
    ]);

    $pagoRealizado = number_format((float)$request->pago_realizado, 2, '.', '');
    // Guardar el archivo PDF
    $path = $request->file('informe')->store('informes', 'public');



    // Crear el registro en la tabla de postventa
    Postventa::create([
        'id_gestion' => $request->id_gestion,
        'trabajo_realizado' => $request->trabajo_realizado,
        'pago_realizado' => $pagoRealizado,
        'fecha_ejecucion' => $request->fecha_ejecucion,
        'informe' => $path,
    ]);

    return response()->json(['message' => 'Postventa registrada con éxito']);
}

public function getGestionesAceptadas()
{
    $gestiones = GestionAceptada::join('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
        ->select('gestiones_aceptadas.id', 'clientes.nombre_cliente as cliente_nombre', 'gestiones_aceptadas.fecha_acepto')
        ->get();

    return response()->json($gestiones);
}

public function getClienteNombre($id_gestion)
{
    $gestion = GestionAceptada::findOrFail($id_gestion);
    $cliente = Cliente::where('codigo', $gestion->codigo)->first();

    return response()->json(['nombre_cliente' => $cliente->nombre]);
}

public function getGestiones(Request $request)
{
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');
    $tiposDeseados = ['inspección', 'importante', 'competencia'];

    // Obtener registros de gestiones
    $gestiones = Gestion::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
        ->whereIn('tipo', $tiposDeseados)
        ->get()
        ->map(function($gestion) {
            $cliente = Cliente::where('codigo', $gestion->codigo)->first();
            return [
                'id' => $gestion->id,
                'codigo' => $gestion->codigo,
                'fecha' => $gestion->fecha,
                'hora' => substr($gestion->hora, 0, 5),
                'descripcion' => $gestion->comentarios,
                'cliente' => $cliente ? $cliente->nombre_cliente : 'Desconocido',
                'tipo' => $gestion->tipo
            ];
        });

    // Obtener registros de gestiones aceptadas
    $gestionesAceptadas = GestionAceptada::whereBetween('fecha_acepto', [$fecha_inicio, $fecha_fin])
        ->get()
        ->map(function($gestionAceptada) {
            $cliente = Cliente::where('codigo', $gestionAceptada->codigo)->first();
            return [
                'id' => $gestionAceptada->id,
                'codigo' => $gestionAceptada->codigo,
                'fecha' => $gestionAceptada->fecha_acepto,
                'hora' => substr($gestionAceptada->hora_acepto, 0, 5),
                'descripcion' => $gestionAceptada->comentarios,
                'cliente' => $cliente ? $cliente->nombre_cliente : 'Desconocido',
                'tipo' => 'aceptada'
            ];
        });

        // Inicializar colecciones vacías si no hay registros
        if ($gestiones->isEmpty()) {
            $gestiones = collect([]);
        }
    
        if ($gestionesAceptadas->isEmpty()) {
            $gestionesAceptadas = collect([]);
        }

    // Combinar ambos resultados y ordenar
    $result = $gestiones->merge($gestionesAceptadas)->sortBy('fecha')->sortBy('hora')->values();

    return response()->json($result);
}


public function eliminarGestion(Request $request)
{
    $id = $request->input('id');
    $tipo = $request->input('tipo');

    try {
        if ($tipo === 'aceptada') {
            $gestionAceptada = GestionAceptada::find($id);
            if ($gestionAceptada) {
                $gestionAceptada->delete();
            }
        }
        else {
            $gestion = Gestion::find($id);
            if ($gestion) {
                $gestion->delete();
            }
        }

        return response()->json(['success' => true], 200);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


public function editarGestion(Request $request)
{
    $gestionId = $request->input('id');
    $gestionTipo = $request->input('tipo');

    if ($gestionTipo === 'aceptada') {
        $gestion = GestionAceptada::where('gestiones_aceptadas.id', $gestionId)
            ->leftJoin('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
            ->select('gestiones_aceptadas.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
            ->first();

            if ($gestion) {
                $gestion->tipo = 'aceptada';
            }
    } else {
        $gestion = Gestion::where('gestiones.id', $gestionId)
            ->leftJoin('clientes', 'gestiones.codigo', '=', 'clientes.codigo')
            ->select('gestiones.*', 'clientes.nombre_cliente', 'clientes.direccion', 'clientes.telefono')
            ->first();
    }

    return response()->json($gestion);
}

public function editarAcepto(Request $request)
{
    $gestionTipo = $request->input('tipo');
    $id = $request->input('id');

    try {
        if ($gestionTipo === 'aceptada') {
            $gestion = GestionAceptada::findOrFail($id);
        } else {
            $gestion = Gestion::findOrFail($id);
        }

        // Actualiza los campos necesarios
        $gestion->update($request->all());
        return $gestionTipo;
        //return response()->json(['success' => true, 'message' => 'Gestión actualizada correctamente']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al actualizar la gestión', 'error' => $e->getMessage()], 500);
    }
}

public function getScheduledCalls(Request $request)
{
    // Validar que la fecha fue proporcionada
    $request->validate([
        'fecha' => 'required|date'
    ]);

    // Obtener la fecha desde la solicitud
    $fecha = $request->input('fecha');

    // Consultar las horas ocupadas entre '08:00:00' y '19:00:00' para la fecha especificada
    $horasOcupadas = DB::table('llamadas_programadas')
        ->whereDate('fecha_llamada', $fecha)
        ->whereBetween('hora_llamada', ['08:00:00', '19:00:00'])
        ->pluck('hora_llamada'); // Seleccionar solo las horas

    // Retornar las horas ocupadas como una respuesta JSON
    return response()->json($horasOcupadas);
}




}
