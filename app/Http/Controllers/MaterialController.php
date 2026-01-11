<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil materi internal yang diupload mahasiswa
        $query = Material::with('user');

        if ($request->filled('course') && $request->course != 'all') {
            $query->where('course', $request->course);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $materials = $query->latest()->get();

        return view('materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        // Validasi Standar Upload File
        $request->validate([
            'course' => 'required|string|max:255',
            'week'   => 'required|integer|min:1|max:16',
            'title'  => 'required|string|max:255',
            'file'   => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480', 
        ]);

        if ($request->hasFile('file')) {
            $safeName = preg_replace('/[^A-Za-z0-9.\-_]/', '', $request->file('file')->getClientOriginalName());
            $fileName = time() . '_' . $safeName;
            $filePath = $request->file('file')->storeAs('materials', $fileName, 'public');

            Material::create([
                'user_id'    => Auth::id(),
                'course'     => $request->course,
                'week'       => $request->week,
                'title'      => $request->title,
                'file_path'  => $filePath,
                'visibility' => 'public',
            ]);

            return redirect()->route('materials.index')->with('success', 'Catatan berhasil dibagikan!');
        }

        return redirect()->back()->with('error', 'Gagal upload file.');
    }

    // --- FITUR EDIT (UPDATE) ---
    public function update(Request $request, Material $material)
    {
        // Hanya pemilik yang bisa edit
        if (Auth::id() !== $material->user_id) {
            abort(403, 'Anda tidak diizinkan mengedit catatan ini.');
        }

        $request->validate([
            'course' => 'required|string|max:255',
            'week'   => 'required|integer|min:1|max:16',
            'title'  => 'required|string|max:255',
            'file'   => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip|max:20480', 
        ]);

        // Update Data Teks
        $material->course = $request->course;
        $material->week = $request->week;
        $material->title = $request->title;

        // Cek jika ada file baru yang diupload
        if ($request->hasFile('file')) {
            // 1. Hapus file lama fisik
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            // 2. Upload file baru
            $safeName = preg_replace('/[^A-Za-z0-9.\-_]/', '', $request->file('file')->getClientOriginalName());
            $fileName = time() . '_' . $safeName;
            $filePath = $request->file('file')->storeAs('materials', $fileName, 'public');
            
            // 3. Update path di database
            $material->file_path = $filePath;
        }

        $material->save();

        return redirect()->route('materials.index')->with('success', 'Catatan berhasil diperbarui!');
    }
    
    public function viewFile($id)
    {
        $material = Material::findOrFail($id);
        $path = $material->file_path; 

        // CARA 1: Cek di Disk 'public' (Biasanya file upload masuk sini)
        if (Storage::disk('public')->exists($path)) {
            return response()->file(Storage::disk('public')->path($path));
        }

        // CARA 2: Cek di Disk 'local' (Alternatif)
        if (Storage::exists($path)) {
            return response()->file(Storage::path($path));
        }

        // Jika tidak ketemu di keduanya
        abort(404, 'File fisik tidak ditemukan di Server.');
    }
    // Fungsi Download tetap sama (Wajib ada)
    public function download($id)
    {
        $material = Material::findOrFail($id);
        $path = $material->file_path;
        
        // Cek apakah file ada di disk public
        if (Storage::disk('public')->exists($path)) {
            // Siapkan nama file baru yang rapi
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $name = \Illuminate\Support\Str::slug($material->title) . '.' . $ext;
            
            // FIX: Gunakan 'response()->download' agar VS Code tidak error
            // 1. Ambil Full Path (Lokasi file lengkap di C:/...)
            $fullPath = Storage::disk('public')->path($path);

            // 2. Return download response
            return response()->download($fullPath, $name);
        }
        
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    public function destroy(Material $material)
    {
        $user = Auth::user();

        // LOGIKA HAPUS:
        // Jika user BUKAN Admin DAN user BUKAN Pemilik file, maka tolak akses.
        // Artinya: Admin boleh hapus, Pemilik boleh hapus.
        if (strtolower($user->role) !== 'admin' && $material->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak menghapus catatan ini.');
        }

        // Hapus file fisik
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        
        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }
}