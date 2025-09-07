<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property $id
 * @property $title
 * @property $created_at
 *
 * @property Session[] $sessions
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model
{
    
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $perPage = 20;
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions()
    {
        return $this->hasMany(\App\Models\Session::class, 'id', 'category_id');
    }
    

}
