<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class CarteCadeauService
 *
 * @property $id
 * @property $service_id
 * @property $reduction_percent
 * @property $amount
 * @property $is_active
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CarteCadeauService extends Model
{
    

    protected $perPage = 20;
    protected $table = 'carte_cadeau_service';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['service_id', 'reduction_percent', 'amount', 'is_active'];

    public function service()
    {
        return $this->belongsTo(\App\Models\Services::class, 'service_id', 'id');
    }

    public function getServicesNonInclus()
    {
        return DB::table('services')
            ->whereNotIn('id', function($query) {
                $query->select('service_id')
                    ->from('carte_cadeau_services');
            })
            ->get();
    }

    



}
