<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $roles = Role::all('id','name');

            return response()->json([
                'message' => 'Resultados obtenidos',
                'data' => $roles
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }  
}
