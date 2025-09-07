<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Employees;
use App\Models\Services;
use App\Models\Promotion;
use Carbon\Carbon;

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
        $categories = ServiceCategory::with(['services' => function($query) {
            $query->where('is_active', 1);
        }])
        ->where('is_active', 1)
        ->get();

        $scat = new Employees();
        $prestataires = Employees::where('is_active', 1)
            ->with(['creneaux' => function ($query) {
                $query->wherePivot('is_active', 1)
                    ->orderByRaw("STR_TO_DATE(creneau, '%H:%i') ASC");
            }])
            ->get();
        $logo = asset('images/LOGODOMISYL_mobile.png');
        $back = asset('images/BACKGROUND.png');
        $result = $categories->map(function ($category) {
            return [
                'title' => $category->name,
                'description' => $category->description,
                'remarque' => $category->remarque,
                'image' => asset('imageformule/'.$category->image_url),
                'details' => [
                    'types' => $category->services->map(function ($service) {
                        $promotions = new Promotion();
                        $promotion =$promotions->getPromoPrice($service->id);
                        return [
                            'id' => $service->id,
                            'title' => $service->title,
                            'duration_minutes' => $service->duration_minutes,
                            'validity_days'=> $service->validity_days,
                            'price' => number_format($service->price, 0, '', ' ') . ' Ar',
                            'pourcent'        => $promotion['pourcent'] ?? null,
                            'price_promo'     => isset($promotion['price_promo']) 
                                                  ? number_format($promotion['price_promo'], 0, '', ' ') . ' Ar' 
                                                  : null,
                            'description' => $service->description,
                            'detail' => $service->detail,
                            'remarque' => $service->remarque,
                            'sessions' =>$service->sessions->map(function ($session) {
                                return [
                                    'title' => $session->title,
                                    'total_session' => $session->pivot->total_session,
                                    'session_per_period' => $session->pivot->session_per_period,
                                    'period_type' => $session->pivot->period_type,
                                ];
                            }, $service->sessions ?? []),
                        ];
                    }),
                ],
            ];
        });

        return response()->json([
        'services' => $result,
        'prestataires' => $prestataires,
        'logo'=> $logo,
        'back'=> $back
    ]);
    }

    /**
     * @OA\Post(
     *     path="/api/checkcreneau",
     *     summary="Vérifier creneau",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="employee_id", type="integer", example=1),
     *             @OA\Property(property="start_times", type="string", format="date-time",example="2025-09-05 10:00:00"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function checkcreneaux(Request $request)
    {
        $alldata = $request->all();
        $employeeId = $request->input('employee_id');
        $startTimes = $request->input('start_times');
        $dateRecherche = Carbon::parse($startTimes)->toDateString(); 

        $prestataires = Employees::where('is_active', 1)
            ->where('id', $employeeId)
            ->get()
            ->map(function($employee) use ($dateRecherche) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'creneaux_det' => $employee->creneauxDisponibles($dateRecherche),
                ];
            });

        return $this->apiResponse(
            true,
            "Vérification creneau disponible",
            ['prestataires' => $prestataires],
            200
        );
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

    private function apiResponse($success, $message, $data = null, $status = 200) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

}
