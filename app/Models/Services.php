<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $perPage = 20;

      /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
    public function serviceCategory()
    {
        return $this->belongsTo(\App\Models\ServiceCategory::class, 'service_category_id', 'id');
    }
    
    // public function servicecategory()
    // {
    //   return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    // }

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'service_session')
                    ->withPivot('total_session', 'session_per_period', 'period_type')
                    ->withTimestamps();
    }
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'service_category_id', 'price', 'duration_minutes','validity_days', 'image_url','validity_days'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'id', 'service_id');
    }
}
