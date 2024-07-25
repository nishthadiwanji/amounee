<?php

namespace App\Composers\Widgets;

use Illuminate\View\View;
use App\Models\Artisan\Artisan;

class ArtisanApproveCount
{
	public function __construct(){
		
	}
    public function compose(View $view){
		
    	$view->with('total_approved_artisans', Artisan::where('status','approved')->where('banned',0)->count());
    }
}