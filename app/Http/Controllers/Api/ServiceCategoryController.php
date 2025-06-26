<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Employees;
use App\Models\Services;
class ServiceCategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/service-category",
     *     summary="liste categories",
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function __construct()
    {
        $this->middleware('cors');
    }

    public function index()
    {
        $categories = ServiceCategory::with('services')->get();
        $scat = new Employees();
        $prestataires = $scat->all();
        $result = $categories->map(function ($category) {
            return [
                'title' => $category->name,
                'description' => $category->description,
                'image' => asset('images/' . str_replace(' ', '', $category->name) . '.png'),
                'details' => [
                    'services' => $category->services->map(function ($service) {
                        return [
                            'title' => $service->title,
                            'duration_minutes' => $service->duration_minutes,
                            'validity_days'=> $service->validity_days,
                            'price' => number_format($service->price, 0, '', ' ') . ' Ar',
                            'description' => $service->description,
                            'session' => $service->sessions,
                        ];
                    }),
                ],
            ];
        });

        return response()->json([
        'services' => $result,
        'prestataires' => $prestataires
    ]);
    }


    // public function index()
    // {
    //     $scat = ServiceCategory::all();
    //     foreach ($scat as $category) {
    //         $category->image_url = asset('images/categories/' . str_replace(' ','',$category->name).".png");
    //     }

    // return response()->json($scat);
    // }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
