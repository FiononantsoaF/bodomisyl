<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class ServiceCategory
 *
 * @property $id
 * @property $name
 * @property $description
 * @property $created_at
 * @property $updated_at
 *
 * @property Service[] $services
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class ServiceCategory extends Model
{

    protected $perPage = 20;
    protected $table = 'service_category';
    protected $primaryKey = 'id';
    protected $fillable = [
            'name',
            'description'
        ];
    use HasFactory;
    
    public function services()
    {
        return $this->hasMany(Services::class);
    }


}
