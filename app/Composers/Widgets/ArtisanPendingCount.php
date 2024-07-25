<?php

namespace App\Composers\Widgets;

use Illuminate\View\View;
use App\Models\Artisan\Artisan;

class ArtisanPendingCount
{
	public function __construct(){
		
	}
    public function compose(View $view){
		
    	$view->with('total_pending_artisans', Artisan::where('status', 'pending')->count());
    }
}