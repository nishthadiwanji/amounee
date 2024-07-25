<?php

namespace App\Composers\Widgets;

use Illuminate\View\View;
use App\Models\Artisan\Artisan;

class ArtisanRejectCount
{
	public function __construct(){
		
	}
    public function compose(View $view){
		
    	$view->with('total_rejected_artisans', Artisan::where('status', 'rejected')->count());
    }
}