<?php
namespace App\Repositories\Payment;

use App\Models\Payment\Payment;
use App\Repositories\EloquentDBRepository;

class PaymentRepository extends EloquentDBRepository
{
    protected $payment;

    public function __construct(Payment $payment){
        $this->model = $payment;
    }

    public function search($search){
        $payments = (new Payment)->newQuery();
        $payments->where(function ($query) use ($search) {
                $query->whereHas('artisan', function($query) use($search){
                    return $query->where(\DB::raw("concat(`artisans`.`first_name`,' ',`artisans`.`last_name`)"), 'like', "%" . $search . "%");
                })->orWhere('payment_type','like','%'.$search.'%');
        })->with(['artisan:id,first_name,last_name', 'deletedBy:id,first_name,middle_name,last_name']);
        return $payments;
    }

    public function getInformation($id){
        $payment = $this->model->withTrashed()->find($id);
        return $payment;
    }

    public function createPayment($attributes)
    {
        $data = [
            //For seeders
            'user_id'  => $attributes['user_id'],
            'artisan_id' => $attributes['artisan_id'],
            'payment_amount' => $attributes['payment_amount'],
            'payment_type' => $attributes['payment_type'],
            'paid_amount' => $attributes['paid_amount'] ?? NULL,
            'date_payment' => $attributes['date_payment'] ?? NULL,
            'note' => $attributes['note'] ?? NULL,
        ];
        $response = $this->model->create($data);
        return $response;
    }

    public function updatePayment(Payment $payment, $attributes)
    {
        $data = [
            'user_id'  => $attributes['user_id'],
            'artisan_id' => $attributes['artisan_id'],
            'payment_amount' => $attributes['payment_amount'],
            'payment_type' => $attributes['payment_type'],
            'paid_amount' => $attributes['paid_amount'] ?? NULL,
            'date_payment' => $attributes['date_payment'] ?? NULL,
            'note' => $attributes['note'] ?? NULL,
        ];
        $response = $payment->update($data);
        return $response;
        
    }

    public function deletePayment(Payment $payment, $deleted_by){
        if($payment){
            $payment->update(['deleted_by' => $deleted_by]);
            $payment->delete();
            return $payment;
        }
        return false;
    }
}