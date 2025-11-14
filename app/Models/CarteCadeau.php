<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use DB;
use Response;
use DateTime;

/**
 * Class CarteCadeau
 *
 * @property $id
 * @property $code
 * @property $beneficiaire
 * @property $contact
 * @property $client_id
 * @property $service_id
 * @property $montant
 * @property $date_emission
 * @property $validite_jours
 * @property $date_fin
 * @property $is_active
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CarteCadeau extends Model
{
    
    protected $perPage = 20;
    protected $table = 'carte_cadeau';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'beneficiaire', 'contact', 'client_id', 'service_id', 'montant', 'date_emission', 'validite_jours', 'date_fin', 'is_active'];

    public function clients(){

        return $this->hasMany(\App\Models\Clients::class, 'id', 'client_id');
    }

    public function services(){
        return $this->hasMany(\App\Models\Services::class, 'id', 'service_id');
    }



}
