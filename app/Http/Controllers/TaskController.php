<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        try {
            $response = Http::get('https://zenquotes.io/api/random');
            $quote = $response->json()[0];
        } catch (\Exception $e) {
            $quote = [
                'q' => 'Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan, Anda dapat mengubah dunia.',
                'a' => 'Nelson Mandela'
            ];
        }

        $query = Task::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderByRaw("FIELD(status, 'pending', 'progress', 'done')")
                       ->orderBy('deadline', 'asc')
                       ->get();

        return view('tasks.index', compact('tasks', 'quote'));
}

    public function store(Request $request)
    {
        if (strtolower(Auth::user()->role) !== 'admin') { 
            abort(403, 'Hanya Admin yang dapgat membuat tugas kelas.'); 
        }

        $validated = $request->validate([
            'course'   => 'required|string|max:100',
            'title'    => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);
    
        Task::create([
            'course'   => $validated['course'],
            'title'    => $validated['title'],
            'deadline' => $validated['deadline'],
            'status'   => 'pending',
            'user_id'  => Auth::id(),
        ]);

        return back()->with('success', 'Tugas kelas berhasil disebarkan!');
    }

    public function update(Request $request, Task $task)
    {
        $user = Auth::user();
        $isAdmin = strtolower($user->role) === 'admin';
        
        $rules = [
            'status' => 'nullable|in:pending,progress,done'
        ];
        
        if ($isAdmin) {
            $rules['course']   = 'nullable|string|max:100';
            $rules['title']    = 'nullable|string|max:255';
            $rules['deadline'] = 'nullable|date';
        }

        $validated = $request->validate($rules);

        if ($request->has('status')) {
            $task->status = $validated['status'];
        }

        if ($isAdmin) {
            if ($request->has('course'))   $task->course = $validated['course'];
            if ($request->has('title'))    $task->title = $validated['title'];
            if ($request->has('deadline')) $task->deadline = $validated['deadline'];
        }

        $task->save();

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        if (strtolower(Auth::user()->role) !== 'admin') { 
            abort(403); 
        }
        $task->delete();
        return back()->with('success', 'Tugas kelas telah dihapus.');
    }
}