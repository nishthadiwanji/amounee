<?php

namespace App\Models\TeamMember;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BanUser;
use App\Traits\Encrypted;
use Carbon\Carbon;

class TeamMember extends Model
{
    use SoftDeletes, Encrypted, BanUser;

    protected $dates = ['deleted_at', 'dob', 'doj'];

    protected $fillable = [
        'user_id',
        'profile_photo',
        'employee_id',
        'password',
        'password_confirmation',
        'country_code',
        'phone_number',
        'department',
        'designation',
        'blood_group',
        'dob',
        'doj',
        'added_by',
        'banned'
    ];

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = (is_null($value) || trim($value) == '' || empty($value)) ? NULL : Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function setDojAttribute($value)
    {
        $this->attributes['doj'] = (is_null($value) || trim($value) == '' || empty($value)) ? NULL : Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }


    public function user(){
        return $this->belongsTo('App\Models\Auth\SentinelUser','user_id','id');
    }

    public function photo(){
        return $this->belongsTo('App\Models\FileInfo\FileInfo','profile_photo','id');
    }

    public function addedBy(){
        return $this->belongsTo('App\Models\Auth\SentinelUser','added_by','id');
    }

}
