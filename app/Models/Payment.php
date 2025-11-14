<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reference',
        'appointment_id',
        'subscription_id',
        'client_id',
        'total_amount',
        'deposit',
        'amount',
        'method',
        'payment_method',
        'status',
        'transaction_data',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'transaction_data' => 'array',
    ];

    // Relations
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function mvolaTransaction()
    {
        return $this->hasOne(MvolaTransaction::class, 'reference', 'reference');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByReference($query, $reference)
    {
        return $query->where('reference', $reference);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Helpers
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isPartial()
    {
        return $this->status === 'partial';
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);
    }

    // Méthode statique pour créer un paiement
    public static function createPayment(array $data)
    {
        return self::create([
            'reference' => $data['reference'] ?? null,
            'appointment_id' => $data['appointment_id'] ?? null,
            'subscription_id' => $data['subscription_id'] ?? null,
            'client_id' => $data['client_id'] ?? null,
            'total_amount' => $data['total_amount'],
            'deposit' => $data['deposit'] ?? $data['amount'] ?? 0,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'status' => $data['status'] ?? 'pending',
            'transaction_data' => $data['transaction_data'] ?? null,
            'paid_at' => $data['status'] === 'paid' ? now() : null,
        ]);
    }

    // Calculer le montant restant
    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->amount;
    }

    // Vérifier si le paiement est complet
    public function isFullyPaid()
    {
        return $this->amount >= $this->total_amount;
    }
}