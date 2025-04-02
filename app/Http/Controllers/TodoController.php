<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TodoController extends Controller
{
    /**
     * Display the user's to-do list
     */
    public function index(): Response
    {
        $todos = Auth::user()->todos()->latest()->get();

        return Inertia::render('Todos/Index', [
            'todos' => $todos,
        ]);
    }

    /**
     * Store newly created todolist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Auth::user()->todos()->create($validated);

        return redirect()->route('todos.index')->with('message', 'To-do list created successfully!');
    }


    /**
     * Update specified todolist
     */
    public function update(Request $request, Todo $todo)
    {
        // Authorization
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'completed' => 'sometimes|required|boolean',
            'title' => 'sometimes|required|string|max:255'
        ]);

        if (!empty($validated)) {
            $todo->update($validated);
       } else {
           return redirect()->back()->with('warning', 'No changes detected.');
       }

       $message = 'To-do list updated!';
        if ($request->has('title') && !$request->has('completed')) {
            $message = 'To-do list updated!';
        } elseif ($request->has('completed') && !$request->has('title')) {
             $message = 'To-do list status updated!';
        }

        return redirect()->back()->with('message', $message);
    }

    /**
     * Remove selected todolist from storage
     */
    public function destroy(Todo $todo)
    {
        // Authorization
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $todo->delete();

        return redirect()->back()->with('message', 'To-do list deleted successfully!');
    }
}