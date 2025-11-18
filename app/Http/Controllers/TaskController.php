<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
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
        return new SuccessResource(
            'User Logged In Successfully',
            ['tasks' => request()->user()->tasks],
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }


        request()->user()->tasks()->create($validator->validated());

        return new SuccessResource(
            'Task created successfully',
            null,
            201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            return new SuccessResource(
                'Task created successfully',
                [
                    'task' => $task
                ],
            );
        } else {
            return new ErrorResource('Task not found.', null, 404);
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
        ]);

        if ($validator->fails()) {
            return new ErrorResource('Validation failed', $validator->errors(), 422);
        }

        $task = Task::find($taskId);
        if ($task) {
            $task->update($validator->validated());
            return new SuccessResource(
                'Task updated successfully',
                null            );
        } else {
            return new ErrorResource('Task not found', 404);

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
            return new SuccessResource(
                'Task deleted successfully',
                null,
                204
            );
        } else {
            return new ErrorResource('Task not found', 404);

        }
    }
}
