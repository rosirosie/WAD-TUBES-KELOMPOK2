<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Menampilkan halaman utama Groups (Directory & Progres Kelompok)
     */
    public function index()
    {
        $subjects = Group::all();
        $user = Auth::user();

        // Mencari kelompok dimana user menjadi ketua ATAU menjadi anggota
        $myProjectProgress = GroupTeam::where('leader_name', $user->name)
                                    ->orWhereJsonContains('members', $user->name)
                                    ->get();

        return view('groups.index', compact('subjects', 'myProjectProgress'));
    }

    /**
     * Redirect jika user mengakses /groups-directory langsung
     */
    public function directory()
    {
        return redirect()->route('groups.index');
    }

    /**
     * Fungsi Export Excel/CSV (DIPERBAIKI: Menggunakan Native PHP agar anti-error)
     */
    public function exportGroups(Request $request)
    {
        $fileName = 'Daftar_Kelompok_StudyHub.csv';
        $teams = GroupTeam::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Kelompok', 'Ketua Kelompok', 'Anggota Kelompok', 'Tema/Topik');

        $callback = function() use($teams, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($teams as $index => $team) {
                $row['No'] = $index + 1;
                $row['Nama Kelompok'] = $team->name;
                $row['Ketua'] = $team->leader_name;
                $row['Anggota'] = is_array($team->members) ? implode(', ', $team->members) : $team->members;
                $row['Topik'] = $team->topic ?? '-';

                fputcsv($file, array($row['No'], $row['Nama Kelompok'], $row['Ketua'], $row['Anggota'], $row['Topik']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Menyimpan Data Kelompok Baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'group_id'    => 'required|exists:groups,id',
            'name'        => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'members'     => 'required|array',   // UBAH JADI ARRAY (Karena input name="members[]")
            'members.*'   => 'string',           // Pastikan isi array adalah string
            'topic'       => 'required|string|max:255',
            'report_link' => 'nullable|url',     // Tambahan sesuai form
            'ppt_link'    => 'nullable|url',     // Tambahan sesuai form
        ]);

        // 2. Simpan ke Database
        GroupTeam::create([
            'group_id'    => $validated['group_id'],
            'name'        => $validated['name'],
            'leader_name' => $validated['leader_name'],
            'members'     => $validated['members'], // Langsung simpan array
            'topic'       => $validated['topic'],
            'report_link' => $validated['report_link'] ?? null,
            'ppt_link'    => $validated['ppt_link'] ?? null,
        ]);

        // 3. Redirect kembali ke folder mata kuliah tersebut
        $groupSubject = Group::find($validated['group_id']);
        
        return redirect()->route('groups.directory.detail', ['subject' => $groupSubject->subject])
                         ->with('success', 'Kelompok berhasil dibuat!');
    }

    /**
     * Menampilkan Detail Folder Mata Kuliah (Tabel Kelompok)
     */
    public function directoryDetail($subject)
    {
        // 1. Cari dulu ID Group berdasarkan nama Subject
        $groupModel = Group::where('subject', $subject)->firstOrFail();

        // 2. Ambil semua tim yang ada di group_id tersebut
        $teams = GroupTeam::where('group_id', $groupModel->id)->get();
        
        // 3. Ambil group_id untuk dikirim ke Modal
        $group_id = $groupModel->id;

        return view('groups.directory_detail', compact('teams', 'subject', 'group_id'));
    }

    public function showProgress($id)
    {
        $team = GroupTeam::with('group')->findOrFail($id);
        return view('groups.progress_detail', compact('team'));
    }
}