<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use Sentinel;

class DashboardController extends Controller
{
    public function __invoke(){
        // if(Sentinel::inRole('admin')){
        //     return view('modules.dashboard.admin');
        // }
        // elseif(Sentinel::inRole('team_member')){
        //     return view('modules.dashboard.team-member');
        // }
        return view('modules.dashboard.admin');
    }
}
