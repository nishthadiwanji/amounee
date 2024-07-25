<?php

namespace App\Models\FileInfo;

use App\Traits\Encrypted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileInfo extends Model
{
    use SoftDeletes, Encrypted;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'file_url',
        'thumbnail_url',
        'name',
        'extension',
        'size',
        'type'
    ];
    
    public function getDisplayThumbnailUrlAttribute(){
        
        return generateUrl(getStorageDisk(), $this->thumbnail_url);
    }
   
    public function getDisplayUrlAttribute(){
        
        return generateUrl(getStorageDisk(), $this->file_url);
    }
}
