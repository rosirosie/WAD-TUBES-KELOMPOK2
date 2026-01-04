<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua materi beserta info pengunggah (Admin atau Mahasiswa lain)
        $query = Material::with('user');

        // 2. Filter Mata Kuliah jika dipilih
        if ($request->filled('course') && $request->course != 'all') {
            $query->where('course', $request->course);
        }

        // 3. Search Judul Materi
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Urutkan dari yang terbaru
        $materials = $query->latest()->get();

        return view('materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        // Validasi input: Sekarang semua mahasiswa bisa melewati ini
        $request->validate([
            'course' => 'required|string|max:255',
            'week'   => 'required|integer|min:1|max:16',
            'title'  => 'required|string|max:255',
            'file'   => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240', 
        ]);

        if ($request->hasFile('file')) {
            // Berikan nama unik agar tidak bentrok jika judul file sama
            $fileName = time() . '_' . $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('materials', $fileName, 'public');

            Material::create([
                'user_id'    => Auth::id(), // ID pengunggah (siapa saja yang login)
                'course'     => $request->course,
                'week'       => $request->week,
                'title'      => $request->title,
                'file_path'  => $filePath,
                'visibility' => 'public', // Otomatis publik agar bisa dilihat teman sekelas
            ]);

            return redirect()->route('materials.index')->with('success', 'Catatan/Materi berhasil dibagikan!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    public function destroy(Material $material)
    {
        $user = Auth::user();

        // LOGIKA PENGHAPUSAN:
        // Admin bisa hapus semua file, Mahasiswa hanya bisa hapus file unggahannya sendiri
        if (strtolower($user->role) !== 'admin' && $material->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan menghapus catatan orang lain.');
        }

        // Hapus file dari folder storage agar tidak menumpuk
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }
}