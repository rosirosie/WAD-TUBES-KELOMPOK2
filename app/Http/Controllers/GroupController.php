<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupTeam;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Menampilkan halaman utama Groups (Directory & Progres Kelompok)
     */
    public function index()
    {
        $subjects = Group::all(); //
        $userName = auth()->user()->name;

        // Mencari kelompok berdasarkan nama user yang login
        $myProjectProgress = GroupTeam::where('leader_name', 'LIKE', "%$userName%")
                            ->orWhereJsonContains('members', $userName)
                            ->get();

        return view('groups.index', compact('subjects', 'myProjectProgress'));
    }

    /**
     * PERBAIKAN: Menangani rute 'groups.directory' (Kembali ke Folder)
     * Ditambahkan untuk memperbaiki RouteNotFoundException
     */
    public function directory()
    {
        return redirect()->route('groups.index');
    }

    public function create()
    {
        $subjects = Group::all(); //
        return view('groups.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'members' => 'required|string', 
            'topic' => 'nullable|string|max:255',
        ]); //

        $memberArray = array_map('trim', explode(',', $request->members));

        GroupTeam::create([
            'group_id' => $validated['group_id'],
            'name' => $validated['name'],
            'leader_name' => $validated['leader_name'],
            'members' => $memberArray,
            'topic' => $validated['topic'],
        ]); //

        return redirect()->route('groups.index')->with('success', 'Kelompok berhasil dibuat!');
    }

    public function showProgress($id)
    {
        // Mencari data tim berdasarkan ID dan menyertakan data mata kuliahnya
        $team = GroupTeam::with('group')->findOrFail($id);
        
        return view('groups.progress_detail', compact('team'));
    }

    public function directoryDetail($subject)
    {
        // Mencari semua kelompok yang ada di mata kuliah tertentu
        $teams = GroupTeam::whereHas('group', function($query) use ($subject) {
            $query->where('subject', $subject);
        })->get();

        return view('groups.directory_detail', compact('teams', 'subject'));
    }
}