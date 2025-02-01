<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::simplePaginate(5);

            return response()->json([
                'message' => 'Resultados obtenidos',
                'data' => $users
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
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'role_id' => 'required',
                'password' => 'required'
            ]);
            if($validator->fails()){
                return response()->json([
                    'message' => 'Ha ocurrido un error',
                    'code' => $validator->getRules()
                ], 500);
            }

            // Check this later
            // $user = new User();
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->role_id = $request->role_id;
            // $user->password = Hash::make($request->password);
            // $user->save();
            $user = $request->all();
            $user = User::create($user);

            return response()->json([
                'message' => 'Registro insertado',
                'data' => $user
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
            $user = User::find($id);

            if(!empty($user)){
                $validator = Validator::make($request->all(),[
                    'name' => 'required',
                    'email' => 'required|unique:users,email,'.$user->id,
                    'role_id' => 'required'
                ]);
                if($validator->fails()){
                    return response()->json([
                        'message' => 'Ha ocurrido un error',
                        'error' => $validator->getRules()
                    ], 500);
                }
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role_id = $request->role_id;

                $user->save();

                return response()->json([
                    'message' => 'Registro actualizado',
                    'data' => $user
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if(!empty($user)){
                $user->delete();
                return response()->json([
                    'message' => 'Registro eliminado',
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

    public function updatePassword(Request $request, int $id){
        try {
            $user = User::find($id);
            if(!empty($user)){
                $user->password = Hash::make($request->password);
                $user->save();

                return response()->json([
                    'message' => 'Registro actualizado',
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
}
