<?php

namespace App\Models\Artisan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BanUser;
use App\Traits\Encrypted;

class ArtisanFile extends Model
{
    use SoftDeletes, Encrypted, BanUser;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'artisan_id',
        'file_type',
        'file_id'
    ];

    public function fileInfo()
    {
        return $this->hasOne('App\Models\FileInfo\FileInfo', 'id', 'file_id');
    }

    public function artisan(){
        return $this->belongsTo('App\Models\Artisan\Artisan', 'artisan_id', 'id');
    }
}
