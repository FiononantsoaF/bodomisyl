<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MvolaTransaction extends Model
{
    protected $fillable = [
        'reference',
        'server_correlation_id',
        'appointment_id',
        'subscription_id',
        'client_id',
        'client_phone',
        'amount',
        'price',
        'status',
        'data_post',
        'data_status',
        'data_callback',
        'data_transaction',
    ];

    protected $casts = [
        'data_post' => 'array',
        'data_callback' => 'array',
        'amount' => 'decimal:2',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function appointment()
    {
        return $this->belongsTo(appointments::class, 'appointment_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id'); // Ajustez selon votre modèle
    }

    // Scopes utiles
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByReference($query, $reference)
    {
        return $query->where('reference', $reference);
    }

    public function scopeByServerCorrelationId($query, $serverCorrelationId)
    {
        return $query->where('server_correlation_id', $serverCorrelationId);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }
}

// namespace App\Models;


// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Casts\Attribute;

// class MvolaTransaction extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'data_post',
//         'data_status',
//         'data_transaction',
//     ];

//     protected $casts = [
//         'data_post' => 'array',
//     ];

//     protected function dataStatus(): Attribute
//     {
//         return Attribute::make(
//             get: fn ($value) => unserialize($value),
//             set: fn ($value) => serialize($value)
//         );
//     }

//     protected function dataTransaction(): Attribute
//     {
//         return Attribute::make(
//             get: fn ($value) => unserialize($value),
//             set: fn ($value) => serialize($value)
//         );
//     }

    
// }
