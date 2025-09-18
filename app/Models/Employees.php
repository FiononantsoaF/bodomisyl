<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        
    public function changeActive()
    {
        $this->is_active = $this->is_active == 1 ? 0 : 1;
        $this->save();
    }

    public function creneauxDisponibles($date)
    {
        $jourSemaine = Carbon::parse($date)->dayOfWeek;

        $creneaux = $this->creneaux()
            ->wherePivot('is_active', 1)
            ->wherePivot('jour', $jourSemaine)
            ->orderByRaw("STR_TO_DATE(creneau, '%H:%i') ASC")
            ->get();

        $creneauxPris = DB::table('appointments')
            ->where('employee_id', $this->id)
            ->whereDate('start_times', $date)
            ->pluck('start_times')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        return $creneaux->map(function($c) use ($creneauxPris) {
            return [
                'id'       => $c->id,              // id de employees_creneau
                'time'     => $c->creneau,                // heure du créneau (vient de la table creneau)
                'is_taken' => in_array($c->creneau, $creneauxPris),
            ];
        });
    }

    public function services()
    {
        return $this->belongsToMany(Services::class, 'service_employees', 'employee_id', 'service_id')
                    ->withTimestamps();
    }






}
