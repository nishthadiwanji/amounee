<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;
use Carbon\Carbon;
class Payment extends Model
{
    use SoftDeletes, Encrypted;

    protected $dates = ['deleted_at', 'date_payment'];

    protected $fillable = [
        'artisan_id',
        'user_id',
        'payment_amount',
        'payment_type',
        'paid_amount',
        'date_payment',
        'note',
        'deleted_by',
    ];

    public function setDatePaymentAttribute($value)
    {
        $this->attributes['date_payment'] = (is_null($value) || trim($value) == '' || empty($value)) ? NULL : Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }

    public function getTransactionId() {
        return "pay-a-".sprintf('%03d', $this->id);
    }

    public function artisan(){
        return $this->belongsTo('App\Models\Artisan\Artisan','artisan_id','id');
    }

    public function user(){
        return $this->belongsTo('App\Models\Auth\SentinelUser','user_id','id');
    }

    public function deletedBy()
    {
        return $this->belongsTo('App\Models\Auth\SentinelUser', 'deleted_by', 'id');
    }
    
}
