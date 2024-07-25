<?php

namespace App\Models\Artisan;

use Illuminate\Database\Eloquent\Model;

class ArtisanStatus extends Model
{
    //
    protected $fillable = [
        'artisan_id',
        'status'
    ];
    public function artisan(){
        return $this->belongsTo('App\Models\Artisan\Artisan', 'artisan_id', 'id');
    }
}
