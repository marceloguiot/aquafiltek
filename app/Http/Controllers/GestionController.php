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

class GestionController extends Controller
{
    //
    

    public function index(){
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
                $gestionesCombinadas = $ultimasGestiones->merge($ultimasGestionesAceptadas);
        
                // Devolver los datos en formato JSON
                $prev = response()->json($gestionesCombinadas);
       

                $all = [];
                $all['previas'] = $prev;
                $all['proximas'] = $prox;


        return Inertia::render('Dashboard', ['datos' => $all]);
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
            // Filtrar los registros por el campo 'codigo' y seleccionar los campos requeridos, incluyendo nombre_cliente de la tabla clientes
    $gestiones = GestionAceptada::join('clientes', 'gestiones_aceptadas.codigo', '=', 'clientes.codigo')
    ->where('gestiones_aceptadas.codigo', $request->id)
    ->select('gestiones_aceptadas.fecha_acepto', 'gestiones_aceptadas.hora_acepto', 'gestiones_aceptadas.precio', 'gestiones_aceptadas.id', 'gestiones_aceptadas.codigo', 'clientes.nombre_cliente')
    ->get();

// Devolver los datos en formato JSON
return response()->json($gestiones);
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

}
