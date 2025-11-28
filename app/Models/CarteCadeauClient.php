<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarteCadeauClient
 *
 * @property $id
 * @property $code
 * @property $benef_name
 * @property $carte_cadeau_service_id
 * @property $benef_contact
 * @property $client_id
 * @property $amount
 * @property $start_date
 * @property $validy_days
 * @property $end_date
 * @property $is_active
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
    class CarteCadeauClient extends Model
    {
        
        protected $perPage = 20;
        protected $table = 'carte_cadeau_client';
        protected $primaryKey = 'id';

        protected $fillable = ['code', 'benef_name', 'carte_cadeau_service_id', 'benef_contact', 'client_id','message', 'amount', 'start_date', 'validy_days', 'end_date', 'is_active','benef_client_id','is_paid'];

        public function carteCadeauService()
        {
            return $this->belongsTo(\App\Models\CarteCadeauService::class, 'carte_cadeau_service_id', 'id');
        }

        public function appointments(){
            return $this->hasMany(\App\Models\appointments::class, 'carte_cadeau_code', 'code');
        }

        public function subscriptions(){
            return $this->belongsTo(\App\Models\Subscription::class, 'carte_cadeau_code', 'code');
        }

        public function payments()
        {
            return $this->hasMany(Payment::class, 'code_carte_cadeau', 'code');
        }

        public function clients()
        {
            return $this->belongsTo(Clients::class, 'client_id', 'id');
        }

        public function changeActive()
        {
            $this->is_active = $this->is_active == 1 ? 0 : 1;
            $this->save();
        }

        




    }
