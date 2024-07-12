<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cliente;
use App\Models\GestionAceptada;
use App\Models\Gestion;
use App\Models\LlamadasProgramadas;
use App\Models\Comentarios;
use Carbon\Carbon;
use Auth;

class GestionController extends Controller
{
    //
    

    public function index(){
         // Retrieve the first seven matching records
         $clientes = Cliente::where('inactivo', 0)
         ->where('estado', 'Por gestionar')
         //->where('id', '>', 894)
         ->orderBy('id')
         ->limit(7)
         ->get();
        return Inertia::render('Dashboard', ['datos' => $clientes]);
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
            /*
            ->where(function($query) use ($twoDaysAgo) {
                $query->where('llamadas_programadas.fecha_llamada', '>', $twoDaysAgo->toDateString())
                      ->orWhere(function($query) use ($twoDaysAgo) {
                          $query->where('llamadas_programadas.fecha_llamada', '=', $twoDaysAgo->toDateString())
                                ->where('llamadas_programadas.hora_llamada', '>=', $twoDaysAgo->toTimeString());
                      });
            })
                      */
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
        // Filtrar los registros por el campo 'codigo' y seleccionar los campos requeridos
        $gestiones = GestionAceptada::where('codigo', $request->id)
            ->select('fecha_acepto', 'hora_acepto', 'precio')
            ->get();

        // Devolver los datos en formato JSON
        return response()->json($gestiones);
    }

}
