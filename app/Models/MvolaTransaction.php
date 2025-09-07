<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MvolaTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_post',
        'data_status',
        'data_transaction',
    ];

    protected $casts = [
        'data_post' => 'array',
    ];

    protected function dataStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => unserialize($value),
            set: fn ($value) => serialize($value)
        );
    }

    protected function dataTransaction(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => unserialize($value),
            set: fn ($value) => serialize($value)
        );
    }

    
}
