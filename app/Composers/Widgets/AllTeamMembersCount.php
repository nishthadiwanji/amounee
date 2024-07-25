<?php

namespace App\Composers\Widgets;

use Illuminate\View\View;
use App\Models\TeamMember\TeamMember;

class AllTeamMembersCount
{
	public function __construct(){
		
	}
    public function compose(View $view){
		
    	$view->with('total_team_members', TeamMember::count());
    }
}