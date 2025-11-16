<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['tasks' => Task::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        Task::create($validator->validated());

        return response()->json(['message' => 'Task created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            return response()->json(['task' => $task]);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $taskId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $task = Task::find($taskId);
        if ($task) {
            $task->update($validator->validated());
            return response()->json(['message' => 'Task updated successfully'], 201);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 201);
        } else {
            return response()->json(['message' => 'Task not found'], 404);
        }
    }
}
