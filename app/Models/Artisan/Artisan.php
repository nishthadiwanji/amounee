<?php

namespace App\Models\Artisan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BanUser;
use App\Traits\Encrypted;

class Artisan extends Model
{
    use SoftDeletes, Encrypted, BanUser;

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'user_id',
        'category_id',
        // 'vendor_picture',
        // 'passbook_picture',
        'first_name',
        'last_name',
        'trade_name',
        'gst',
        'country_code',
        'phone_number',
        'email',
        'craft_category',
        'street1',
        'street2',
        'zip',
        'city',
        'state',
        'country',
        'account_name',
        'account_number',
        'bank_name',
        'ifsc',
        // 'id_proof',
        'awards',
        'commission',
        'artisan_cards',
        'approval_note',
        'rejection_note',
        'status',
        'banned',
        'added_by'
    ];

    public function artisanFiles(){
        return $this->hasMany('App\Models\Artisan\ArtisanFile', 'artisan_id', 'id');
    }

    public function photo(){
        $artisan_photo = $this->artisanFiles()->where('file_type', 'profile_photo')->first();
        if (!$artisan_photo) {
            return NULL;
        }
        return $artisan_photo->fileInfo();
    }

    public function artisanCard()
    {
        $artisan_card = $this->artisanFiles()->where('file_type', 'artisan_card')->first();
        if (!$artisan_card) {
            return NULL;
        }
        return $artisan_card->fileInfo();
    }

    public function bankProof()
    {
        return $this->artisanFiles()->where('file_type', 'passbook_picture')->first()->fileInfo();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\SentinelUser', 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category','category_id','id');
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeRejected($query)
    {
        $query->where('status', 'rejected')->where('banned', 0);
    }

    public function scopeApprove($query)
    {
        $query->where('status','approved')->where('banned', 0);
    }
    
    public function scopePending($query)
    {
        $query->where('status','pending');
    }
    
    public function addedBy(){
        return $this->belongsTo('App\Models\Auth\SentinelUser','added_by','id');
    }

    public function artisanStatuses(){
        return $this->hasMany('App\Models\Artisan\ArtisanStatus', 'artisan_id', 'id');
    }

    public function product()
    {
        return $this->hasMany('App\Models\Product\Product', 'artisan_id', 'id');
    }
}
