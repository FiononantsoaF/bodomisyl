<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


/**
 * Class JobCategory
 *
 * @property $id
 * @property $name
 * @property $created_at
 * @property $updated_at
 * @property $service_category_id
 *
 * @property ServiceCategory $serviceCategory
 * @property Employee[] $employees
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class JobCategory extends Model
{
    

    protected $table = 'job_category';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

 
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany(\App\Models\Employee::class, 'id', 'job_categ_id');
    }
    

}
