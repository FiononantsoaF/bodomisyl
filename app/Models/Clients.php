<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    /*protected $fillable = [
            'name',
            'email',
            'phone',
            'password',
            'adress',
        ];*/
    use HasFactory;
}
