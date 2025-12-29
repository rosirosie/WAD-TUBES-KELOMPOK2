<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->status == 'upcoming') {
            $query->where('due_date', '>=', Carbon::now())
                  ->where('is_completed', false);
        } else if ($request->status == 'completed') {
            $query->where('is_completed', true);
        }

        $tasks = $query->orderBy('due_date', 'asc')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('tasks'));
    }

    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $task->update(['is_completed' => true]);

        return redirect()->back()->with('success', 'Tugas berhasil diselesaikan');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>=', now())->where('is_completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
}
