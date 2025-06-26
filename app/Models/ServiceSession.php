<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ServiceSession
 *
 * @property $id
 * @property $services_id
 * @property $session_id
 * @property $total_session
 * @property $session_per_period
 * @property $period_type
 * @property $created_at
 * @property $updated_at
 *
 * @property Service $service
 * @property Service $service
 * @property Session $session
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ServiceSession extends Model
{
    

    protected $perPage = 20;
    protected $table = 'service_session';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['services_id', 'session_id', 'total_session', 'session_per_period', 'period_type'];


    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'services_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(\App\Models\Session::class, 'session_id', 'id');
    }
    

}
