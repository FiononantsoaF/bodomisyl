<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 *
 * @property $id
 * @property $title
 * @property $validity_days
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Session extends Model
{
    

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];
    
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_session')
                    ->withPivot('total_session', 'session_per_period', 'period_type')
                    ->withTimestamps();
    }

    


}
