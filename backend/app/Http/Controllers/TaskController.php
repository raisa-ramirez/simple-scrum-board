<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\State;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = Task::with(['category','state'])->simplePaginate(5);            

            return response()->json([
                'message' => 'Resultados obtenidos',
                'data' => $tasks
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
            $task = $request->all();
            $validator = Validator::make($task,[
                'title' => 'required',
                'description' => 'required',
                'state_id' => 'required',
                'category_id' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Ha ocurrido un error',
                    'error' => $validator->getRules()
                ], 500);
            }

            $task = Task::create($task);

            return response()->json([
                'message' => 'Registro insertado',
                'data' => $task
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
    public function update(Request $request, string $id)
    {
        try {
            $task = Task::find($id);

            if(!empty($task)){
                $validator = Validator::make($request->all(),[
                    'title' => 'required',
                    'description' => 'required',
                    'state_id' => 'required',
                    'category_id' => 'required'
                ]);

                if($validator->fails()){
                    return response()->json([
                        'message' => 'Ha ocurrido un error',
                        'error' => $validator->getRules()
                    ], 500);
                }
                $task->title = $request->title;
                $task->description = $request->description;
                $task->state_id = $request->state_id;
                $task->category_id = $request->category_id;

                $task->save();

                return response()->json([
                    'message' => 'Registro actualizado',
                    'data' => $task
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
            $task = Task::find($id);

            if(!empty($task)){
                $task->delete();
                return response()->json([
                    'message' => 'Registro eliminado'
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

    public function updateState(Request $request, string $id)
    {
        try {
            $task = Task::find($id);

            if(!empty($task)){
                $task->state_id = $request->state_id;
                $task->save();

                return response()->json([
                    'message' => 'Registro actualizado',
                    'data' => $task
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

    public function tasksByUser(int $userId){
        try {
            $categories = Category::with(['tasks','user'])
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

            return response()->json([
                'message' => 'Resultados obtenidos',
                'tasksByCategory' => $categories,
                'allTasks' => $categories->pluck('tasks')->flatten()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'code' => $th->getCode()
            ], 500);
        }
    }
}
