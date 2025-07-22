<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DB;
use Response;
use DateTime;


/**
 * Class Creneau
 *
 * @property $id
 * @property $creneau
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Creneau extends Model
{
    

    protected $table = 'creneau';
    protected $primaryKey = 'id';
    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['creneau','is_active'];

    public function changeactive()
    {
        $this->is_active = $this->is_active == 1 ? 0 : 1;
        $this->save();
    }

    public function getActiveCreneauxSorted()
    {
        $creneaux = DB::table('creneau')
            ->where('is_active', 1)
            ->orderByRaw('STR_TO_DATE(creneau, "%H:%i") ASC')
            ->get();

        return $creneaux;
    }

}
