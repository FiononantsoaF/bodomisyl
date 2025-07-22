<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Currency
 *
 * @property $id
 * @property $money
 * @property $value
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Currency extends Model
{
    
    protected $table = 'currency';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['money', 'value'];

    public static function changeCurrency($id, $money, $value){
        Currency::where('id', $id)->first()
                                    ->update(["money"=> $money,
                                    "value"=> $value,
                                    ]);
    }



}
