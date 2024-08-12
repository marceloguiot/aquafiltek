<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\JsonResponse;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of provinces.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all provinces from the database
        $provincias = Provincia::all();

        // Return the provinces as a JSON response
        return response()->json($provincias);
    }
}
