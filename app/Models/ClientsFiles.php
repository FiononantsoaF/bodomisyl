<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientsFiles extends Model
{
    use HasFactory;
    protected $table = 'clients_files';
    protected $primaryKey = 'id';
    protected $perPage = 20;
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['client_id','file_path'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(\App\Models\Clients::class, 'id', 'client_id');
    }

    public static function getByClientWithActiveSubscription($client_id)
    {
        $today = Carbon::now()->format('Y-m-d');

        return DB::table('clients_files as cf')
            ->join('subscriptions as sub', 'sub.client_id', '=', 'cf.client_id')
            ->where('sub.status', 'active')
            ->whereDate('sub.period_start', '<=', $today)
            ->whereDate('sub.period_end', '>=', $today)
            ->where('cf.client_id', $client_id)
            ->select('cf.*', 'sub.id as subscription_id', 'sub.period_start', 'sub.period_end')
            ->get();
    }
}
