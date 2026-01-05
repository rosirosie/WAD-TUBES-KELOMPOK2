<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = Task::query();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tasks = $query->orderBy('deadline', 'asc')->get();
        
        // Contoh integrasi quote sederhana jika diperlukan oleh Blade
        $quote = ['q' => 'Being wrong opens us up to the possibility of change.', 'a' => 'Mark Manson'];

        return view('tasks.index', compact('tasks', 'quote'));
    }

    /**
     * FUNGSI EXPORT (Tambahkan ini untuk memperbaiki error)
     */
    public function exportTasks()
    {
        $fileName = 'Daftar_Tugas_StudyHub.csv';
        $tasks = Task::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($tasks) {
            $file = fopen('php://output', 'w');
            // Header kolom di Excel
            fputcsv($file, ['No', 'Mata Kuliah', 'Judul Tugas', 'Deadline', 'Status']);

            foreach ($tasks as $index => $task) {
                fputcsv($file, [
                    $index + 1,
                    $task->course,
                    $task->title,
                    $task->deadline ? $task->deadline->format('d-m-Y') : '-',
                    ucfirst($task->status)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Menyimpan tugas baru (Hanya Admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course'   => 'required|string|max:255',
            'title'    => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        Task::create([
            'course'   => $validated['course'],
            'title'    => $validated['title'],
            'deadline' => $validated['deadline'],
            'status'   => 'pending',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Update status atau deadline tugas
     */
    public function update(Request $request, Task $task)
    {
        if ($request->has('status')) {
            $task->update(['status' => $request->status]);
        }

        if ($request->has('deadline')) {
            $task->update(['deadline' => $request->deadline]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Menghapus tugas
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus!');
    }
}