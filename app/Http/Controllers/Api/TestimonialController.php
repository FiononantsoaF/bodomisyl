<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Carbon\Carbon;

class TestimonialController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/temoignage",
     *     summary="liste des temoignages",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function __construct()
    {
        $this->middleware('cors');
    }

    public function index()
    {
        $temoignages = Testimonial::where('is_active', true)->get();
        if ($temoignages && $temoignages->count() > 0) {
            $temoignages->transform(function ($item) {
                if ($item->file_path) {
                    $item->file_path = asset('imageTemoignage/' . ltrim($item->file_path, '/'));
                }
                return $item;
            });

            return $this->apiResponse(true, "Témoignages", $temoignages);
        }

        return $this->apiResponse(false, "Pas de témoin", null);
    }

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}