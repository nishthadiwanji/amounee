<?php

namespace App\Http\Controllers\TeamMember;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\TeammembersExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportsController extends Controller
{
    use Exportable;
    public function index()
    {
        $members = TeamMember::all();

        return view('modules.team-member.teammember')->with('members', $members);
    }
    public function import(Request $request)
    {
        if ($request->file('imported_file')) {
            Excel::import(new TeammembersImport(), request()->file('imported_file'));
            return back();
        }
    }
    public function export() 
    {
        return Excel::download(new TeammembersExport, 'team_members.xlsx');
    }
}
