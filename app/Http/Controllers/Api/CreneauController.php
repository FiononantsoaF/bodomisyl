<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Creneau;
class CreneauController extends Controller
{
   
    
    /**
     * @OA\Get(
     *     path="/api/creneaus",
     *     summary="liste creneaus",
     *     @OA\Response(response="200", description="Success"),
     * )
     */

    public function index()
    {
        $creneau = new Creneau();
        $cre=$creneau->getActiveCreneauxSorted();
        return $this->apiResponse(true, "Listes des créneaus ",$cre, 200);
        
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}