<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canton;
use Illuminate\Http\JsonResponse;

class CantonController extends Controller
{
    /**
     * Display a listing of provinces.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all provinces from the database
        $cantones = Canton::all();

        // Return the provinces as a JSON response
        return response()->json($cantones);
    }
}
