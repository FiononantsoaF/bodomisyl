<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use DB;
use Response;
use DateTime;

class Testimonial extends Model
{

    protected $perPage = 20;
    protected $table = 'testimonials';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
    */
    protected $fillable = ['file_path', 'is_active'];





}
