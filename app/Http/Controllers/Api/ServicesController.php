<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
class ServicesController extends Controller
{
   
    
    /**
     * @OA\Get(
     *     path="/api/services",
     *     summary="liste services",
     *     @OA\Response(response="200", description="Success"),
      *    @OA\Parameter(
     *         name="idservicecateg",
     *         in="query",
     *      )
     * )
     */

    public function index(Request $request)
    {
        $param = $request->all();
        $scat = new Services();
        if(isset($param['idservicecateg'])){
            return $scat->where('service_category_id', $param['idservicecateg'])->get();
        }else{
            return $scat->all();
        }
    }
}
