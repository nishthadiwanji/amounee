<?php

namespace App\Observers\TeamMember;

use App\Models\TeamMember\TeamMember;

class TeamMemberObserver {
	public function created(TeamMember $member) {
    	log_activity_by_user('team_member_log',$member,'Team Member has been added in the system');
	}
	public function updated(TeamMember $member){
    	log_activity_by_user('team_member_log',$member,'Team Member details have been updated in the system');
	}
    public function deleted(TeamMember $member){
        $member->photo()->delete();
    	log_activity_by_user('team_member_log',$member,'Team Member has been banned in the system');
    }
    public function restored(TeamMember $member){
        $member->photo()->withTrashed()->restore();
    	log_activity_by_user('team_member_log',$member,'Team Member has been activated in the system');
    }
}
