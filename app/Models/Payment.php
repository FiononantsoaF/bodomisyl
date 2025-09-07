<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use DateTime;

/**
 * Class Payment
 *
 * @property $id
 * @property $appointment_id
 * @property $subscription_id
 * @property $total_amount
 * @property $deposit
 * @property $balance
 * @property $status
 * @property $paid_at
 * @property $created_at
 * @property $updated_at
 *
 * @property Appointment $appointment
 * @property Subscription $subscription
 * @property PaymentTransaction[] $paymentTransactions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $perPage = 20;
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_id', 'subscription_id','client_id' ,'total_amount', 'deposit','method', 'status', 'paid_at'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(\App\Models\Appointment::class, 'appointment_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(\App\Models\Subscription::class, 'subscription_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Clients::class, 'client_id', 'id');
    }
    
    public static function createPayment(array $paymentData): Payment
    {
        return DB::transaction(function () use ($paymentData) {
            return self::create([
                'appointment_id'   => $paymentData['appointment_id'] ?? null,
                'subscription_id'  => $paymentData['subscription_id'] ?? null,
                'client_id'        => $paymentData['client_id'],
                'total_amount'     => $paymentData['total_amount'],
                'deposit'          => $paymentData['amount'],
                'method'           => $paymentData['payment_method'] ?? 'mvola',
                'status'           => 'paid',
                'paid_at'          => now(),
                'reference'        => $paymentData['reference'] ?? null,
            ]);
        });
    }


}
