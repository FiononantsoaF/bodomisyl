<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Class FicheClient
 *
 * @property $id
 * @property $client_id
 * @property $objectifs
 * @property $indications
 * @property $observations
 * @property $created_at
 * @property $updated_at
 *
 * @property Client $client
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class FicheClient extends Model
{
    
    use HasFactory;

    protected $table = 'fiche_client';
    protected $primaryKey = 'id';
    protected $perPage = 20;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'objectifs', 'indications', 'observations','consultations','programmes'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id', 'id');
    }

    public function createFiche($client_id,$objectifs, $indications,$consultations, $programmes)
    { 
        $fiches=FicheClient::where(['client_id' => $client_id])->first();
        if($fiches){
            $fiches->where('client_id', $client_id)->first()
                            ->update(["objectifs"=>$objectifs,
                            "indications"=>$indications,
                            "consulations"=>$consultations,
                            "programmes"=>$programmes,
                            ]);

        } else {
            $fiche = new FicheClient();
            $fiche ->client_id = $client_id;
            $fiche ->objectifs = $objectifs;
            $fiche ->indications =$indications;
            $fiche ->consultations = $consultations;
            $fiche ->programmes =$programmes;
            $fiche ->save();
        }

    }
    

}
