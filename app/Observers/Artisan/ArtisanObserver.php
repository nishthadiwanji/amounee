<?php

namespace App\Observers\Artisan;

use App\Models\Artisan\Artisan;

class ArtisanObserver {
	public function created(Artisan $artisan) {
    	log_activity_by_user('artisan_log',$artisan,'Artisan has been added in the system');
	}
	public function updated(Artisan $artisan){
    	log_activity_by_user('artisan_log',$artisan,'Artisan details have been updated in the system');
	}
    public function deleted(Artisan $artisan){
        $artisan->photo()->delete();
    	log_activity_by_user('artisan_log',$artisan,'Artisan has been banned in the system');
    }
    public function restored(Artisan $artisan){
        $artisan->photo()->withTrashed()->restore();
    	log_activity_by_user('artisan_log',$artisan,'Artisan has been activated in the system');
    }
}
