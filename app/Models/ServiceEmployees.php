<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEmployees extends Model
{
    use HasFactory;

    protected $table = 'service_employees';
    protected $primaryKey = 'id';

    protected $fillable = [
        'service_id',
        'employee_id',
    ];

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}

