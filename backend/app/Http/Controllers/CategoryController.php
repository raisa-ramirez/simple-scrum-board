<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $category = Category::simplePaginate(5);

            return response()->json([
                'message' => 'Resultados obtenidos',
                'data' => $category
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Ha ocurrido un error',
                    'error' => $validator->getRules()
                ], 500);
            }
            $category = $request->all();

            Category::create($category);

            return response()->json([
                'message' => 'Registro insertado',
                'data' => $category
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    } 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $category = Category::find($id);

            if(!empty($category)){
                $category->name = $request->name;
                $category->updated_at = now();

                $category->save();

                return response()->json([
                    'message' => 'Registro actualizado',
                    'data' => $category
                ], 200);
            } 
            return response()->json([
                'message' => 'Registro no encontrado',
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::find($id);

            if(!empty($category)){
                $category->delete();

                return response()->json([
                    'message' => 'Registro eliminado',
                ], 200);  
            }

            return response()->json([
                'message' => 'Registro no encontrado'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }

    public function categoriesByUser(int $userId){
        try {
            $categories = User::find($userId)->categories;

            return response()->json([
                'message' => 'Resultados obtenidos',
                'data' => $categories,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }    
}
