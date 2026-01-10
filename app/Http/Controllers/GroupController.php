<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupTeam;
use App\Models\GroupProgres; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{

    public function index()
    {
        $subjects = Group::all();
        $user = Auth::user();

        $myProjectProgress = GroupTeam::where('leader_name', $user->name)
                                    ->orWhereJsonContains('members', $user->name)
                                    ->get();

        return view('groups.index', compact('subjects', 'myProjectProgress'));
    }

    public function directory()
    {
        return redirect()->route('groups.index');
    }

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id'    => 'required|exists:groups,id',
            'name'        => 'required|string|max:255',
            'leader_name' => 'required|string|max:255',
            'members'     => 'required|array',
            'members.*'   => 'string',
            'topic'       => 'required|string|max:255',
        ]);

        GroupTeam::create([
            'group_id'    => $validated['group_id'],
            'name'        => $validated['name'],
            'leader_name' => $validated['leader_name'],
            'members'     => $validated['members'],
            'topic'       => $validated['topic'],
            'report_link' => null, 
            'ppt_link'    => null, 
        ]);

        $groupSubject = Group::find($validated['group_id']);
        
        return redirect()->route('groups.directory.detail', ['subject' => $groupSubject->subject])
                         ->with('success', 'Kelompok berhasil dibuat!');
    }

    public function directoryDetail($subject)
    {
        $groupModel = Group::where('subject', $subject)->firstOrFail();

        $teams = GroupTeam::where('group_id', $groupModel->id)->get();
     
        $group_id = $groupModel->id;

        return view('groups.directory_detail', compact('teams', 'subject', 'group_id'));
    }

    public function showProgress($id)
    {
        $team = GroupTeam::with('group')->findOrFail($id);

        $progress_data = GroupProgres::where('group_team_id', $team->id)
                                     ->orderBy('week', 'asc') 
                                     ->get();

        return view('groups.progress_detail', compact('team', 'progress_data'));
    }

    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'group_team_id' => 'required|exists:group_teams,id',
            'week'          => 'required|integer|min:1', 
            'title'         => 'required|string|max:255',
            'assigned_to'   => 'required|string',
        ]);

        GroupProgres::create([
            'group_team_id' => $validated['group_team_id'],
            'week'          => $validated['week'],
            'title'         => $validated['title'],
            'assigned_to'   => $validated['assigned_to'],
            'is_completed'  => $request->has('is_completed'), 
        ]);

        return back()->with('success', 'Progress berhasil ditambahkan!');
    }

    public function destroyGroup($id)
    {
        $team = GroupTeam::findOrFail($id);
        $team->delete(); 

        return back()->with('success', 'Kelompok berhasil dihapus!');
    }

    public function destroyProgress($id)
    {
        $progress = GroupProgres::findOrFail($id);
        $progress->delete();

        return back()->with('success', 'Item progress berhasil dihapus!');
    }
 
    public function updateProgress(Request $request, $id)
    {
        $validated = $request->validate([
            'week'          => 'required|integer|min:1',
            'title'         => 'required|string|max:255',
            'assigned_to'   => 'required|string',
        ]);

        $progress = GroupProgres::findOrFail($id);

        $progress->update([
            'week'          => $validated['week'],
            'title'         => $validated['title'],
            'assigned_to'   => $validated['assigned_to'],
            'is_completed'  => $request->has('is_completed'), 
        ]);

        return back()->with('success', 'Data progress berhasil diperbarui!');
    }

    public function updateGroup(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'topic'   => 'required|string|max:255',
            'members' => 'required|array', 
        ]);

        $team = GroupTeam::findOrFail($id);

        $team->update([
            'name'    => $validated['name'],
            'topic'   => $validated['topic'],
            'members' => $validated['members'], 
        ]);

        return back()->with('success', 'Data kelompok berhasil diperbarui!');
    }
}