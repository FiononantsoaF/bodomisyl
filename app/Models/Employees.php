<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $perPage = 20;
    protected $fillable = [
        'name',
        'email',
        'job_categ_id',
        'phone',
        'specialty',
        'address',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobCategory()
    {
        return $this->belongsTo(\App\Models\JobCategory::class, 'job_categ_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\appointments::class, 'employee_id', 'id');
    }

    public function creneaux()
    {
        return $this->belongsToMany(Creneau::class, 'employees_creneau', 'employee_id', 'creneau_id')
                    ->withPivot('id', 'is_active','jour')
                    ->withTimestamps()
                    ->orderBy('creneau', 'asc');
    }




}
