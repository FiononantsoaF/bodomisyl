<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


/**
 * Class EmployeesCreneau
 *
 * @property $id
 * @property $employee_id
 * @property $creneau_id
 * @property $is_active
 * @property $created_at
 * @property $updated_at
 *
 * @property Creneau $creneau
 * @property Employee $employee
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EmployeesCreneau extends Model
{
    
    protected $table = 'employees_creneau';
    protected $primaryKey = 'id';
    use HasFactory;
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id', 'creneau_id', 'is_active','jour'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creneau()
    {
        return $this->belongsTo(\App\Models\Creneau::class, 'creneau_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employee_id', 'id');
    }

    public function changeactive()
    {
        $this->is_active = $this->is_active == 1 ? 0 : 1;
        $this->save();
    }

    public static function getByEmployee($employee_id)
    {
        return DB::table('employees_creneau as ec')
            ->select(
                "ec.id",
                "ec.is_active",
                "e.employee_id",
                "ec.creneau_id",
                "c.creneau",
                "ec.jour"
            )
            ->join('employees as e', 'e.id', '=', 'ec.employee_id')
            ->join('creneau as c', 'c.id', '=', 'ec.creneau_id')
            ->where('ec.employee_id', '=', $employee_id)
            ->orderBy('ec.id', 'desc')
            ->get();
    }
    
    public static function createEmployeeCreneau($employee_id,$creneau_id, $jours)
    {
            $empc = new EmployeesCreneau();
            $empc ->employee_id = $employee_id;
            $empc ->creneau_id = $creneau_id;
            $empc ->is_active =1;
            $empc->jour = $jours;
            $empc ->save();
    }
    public static function isCreneauAvailable($employeeId, $creneauId, $dayOfWeek)
    {
        $creneau = self::where('employee_id', $employeeId)
                      ->where('jour', $dayOfWeek)
                      ->where('creneau_id', $creneauId)
                      ->where('is_active', 1)
                      ->exists();
        return $creneau;
    }


    public  function getAvailableDaysForHour($employeeId, $creneauId)
    {
        return EmployeesCreneau::where('employee_id', $employeeId)
                              ->where('creneau_id', $creneauId)
                              ->where('is_active', 1)
                              ->pluck('jour')
                              ->toArray();
    }
    public  $daysMapping = [
        1 => 'Lundi',
        2 => 'Mardi', 
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
        7 => 'Dimanche'
    ];
 

    

}
