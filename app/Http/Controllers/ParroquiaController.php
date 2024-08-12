<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parroquia;
use Illuminate\Http\JsonResponse;

class ParroquiaController extends Controller
{
    /**
     * Display a listing of provinces.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all provinces from the database
        $parroquias = Parroquia::all();

        // Return the provinces as a JSON response
        return response()->json($parroquias);
    }
}
