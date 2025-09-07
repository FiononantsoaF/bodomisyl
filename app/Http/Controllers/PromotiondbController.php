<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Services;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use App\Http\Requests\PromotionRequest;

/**
 * Class PromotionController
 * @package App\Http\Controllers
 */
class PromotiondbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $promotions = Promotion::paginate();
        $promo =1;
        $codepromo="test";
        $start="";
        $end="";
        return view('promotion.index', compact('promotions','promo','codepromo','start','end'))
            ->with('i', (request()->input('page', 1) - 1) * $promotions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $promotion = new Promotion();
        $souscategories = ServiceCategory::with('services')->get();
        return view('promotion.create', compact('promotion','souscategories'));
    }

    public function store(Request $request)
    {
        // $all =$request->all();
        // dd($all);
            $validatedData = $request->validate([
                'code_promo' => 'required|string|max:255|unique:promotions',
                'discount_type' => 'required|in:percentage,amount',
                'pourcent' => 'nullable|numeric|min:0|max:100',
                'amount' => 'nullable|numeric|min:0',
                'start_promo' => 'required|date',
                'end_promo' => 'required|date|after:start_promo',
                'apply_to' => 'required|in:service,services,subcategory',
                'subcategory_id' => 'required_if:apply_to,subcategory|exists:service_category,id',
                'services' => 'nullable|array',
                'services.*' => 'exists:services,id',
                'valeur_specifiques' => 'nullable|array',
            ]);
            
            $existingPromotion = Promotion::where(function($query) use ($validatedData) {
                    $query->whereBetween('start_promo', [$validatedData['start_promo'], $validatedData['end_promo']])
                        ->orWhereBetween('end_promo', [$validatedData['start_promo'], $validatedData['end_promo']])
                        ->orWhere(function($q) use ($validatedData) {
                            $q->where('start_promo', '<=', $validatedData['start_promo'])
                                ->where('end_promo', '>=', $validatedData['end_promo']);
                        });
                })
                ->first();
            if ($existingPromotion) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Une promotion existe déjà pour ces dates. Choisissez d\'autres dates.');
            }

            $servicesData = null;
            if ($request->apply_to === 'services' && $request->services) {
                $servicesData = [];
                foreach ($request->services as $serviceId) {
                    $servicesData[$serviceId] = [
                        'selected' => true,
                        'custom_value' => $request->valeur_specifiques[$serviceId] ?? null
                    ];
                }
                $servicesData = serialize($servicesData);

            } elseif ($request->apply_to === 'subcategory' && $request->subcategory_id) {
                    $services = Services::where('service_category_id', $request->subcategory_id)->get();

                    if ($services->count()) {
                        $servicesData = [];
                        foreach ($services as $service) {
                            $servicesData[$service->id] = [
                                'selected' => true,
                                'custom_value' => $request->valeur_specifiques[$service->id] ?? null
                            ];
                        }
                        $servicesData = serialize($servicesData);
                    }
                }
            $promotion = Promotion::create([
                'code_promo' => $validatedData['code_promo'],
                'discount_type' => $validatedData['discount_type'],
                'pourcent' => $validatedData['pourcent'],
                'amount' => $validatedData['amount'],
                'start_promo' => $validatedData['start_promo'],
                'end_promo' => $validatedData['end_promo'],
                'apply_to' => $validatedData['apply_to'],
                'subcategory_id' => $validatedData['subcategory_id'],
                'services' => $servicesData,
            ]);

            return redirect()->route('promotiondb')->with('success', 'Promotion créée avec succès');
    }


    public function show($id)
    {
        $promotion = Promotion::find($id);

        return view('promotion.show', compact('promotion'));
    }
    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $souscategories = ServiceCategory::with('services')->get();
        $selectedServices = [];
        $serviceValues = [];
        
        if ($promotion->services) {
            $servicesData = unserialize($promotion->services);
            if ($servicesData && is_array($servicesData)) {
                foreach ($servicesData as $serviceId => $data) {
                    if ($data['selected']) {
                        $selectedServices[] = $serviceId;
                        if ($data['custom_value'] !== null) {
                            $serviceValues[$serviceId] = $data['custom_value'];
                        }
                    }
                }
            }
        }
        return view('promotion.edit', compact('promotion','souscategories','selectedServices','serviceValues'));
    }


public function update(Request $request, $id)
{
    try {
        $promotion = Promotion::findOrFail($id);

        // Validation
        $validatedData = $request->validate([
            'code_promo' => 'required|string|max:255|unique:promotions,code_promo,' . $id,
            'discount_type' => 'required|in:percentage,amount',
            'pourcent' => 'nullable|numeric|min:0|max:100',
            'amount' => 'nullable|numeric|min:0',
            'start_promo' => 'required|date',
            'end_promo' => 'required|date|after:start_promo',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'service_values' => 'nullable|array',
        ]);
        $existingPromotion = Promotion::where('id', '!=', $id)
            ->where(function($query) use ($validatedData) {
                $query->whereBetween('start_promo', [$validatedData['start_promo'], $validatedData['end_promo']])
                    ->orWhereBetween('end_promo', [$validatedData['start_promo'], $validatedData['end_promo']])
                    ->orWhere(function($q) use ($validatedData) {
                        $q->where('start_promo', '<=', $validatedData['start_promo'])
                            ->where('end_promo', '>=', $validatedData['end_promo']);
                    });
            })
            ->first();

        if ($existingPromotion) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une promotion existe déjà pour ces dates. Choisissez d\'autres dates.');
        }
        $servicesData = null;
        if ($request->services) { 
            $servicesData = [];
            foreach ($request->services as $serviceId) {
                $servicesData[$serviceId] = [
                    'selected' => true,
                    'custom_value' => $request->service_values[$serviceId] ?? null,
                ];
            }
            $servicesData = serialize($servicesData);
        }

        $promotion->update([
            'code_promo' => $validatedData['code_promo'],
            'discount_type' => $validatedData['discount_type'],
            'pourcent' => $validatedData['discount_type'] === 'percentage' ? $validatedData['pourcent'] : null,
            'amount' => $validatedData['discount_type'] === 'amount' ? $validatedData['amount'] : null,
            'start_promo' => $validatedData['start_promo'],
            'end_promo' => $validatedData['end_promo'],
            'services' => $servicesData,
        ]);
        return redirect()->route('promotiondb')
            ->with('success', 'Promotion mise à jour avec succès');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}


    // public function update(Request $request, Promotion $promotion)
    // {
    //     $request->validate([
    //         'id'=> 'required|numeric',
    //         'code_promo' => 'required|string|max:255',
    //         'pourcent' => 'nullable|numeric|min:0|max:100',
    //         'amount' => 'nullable|numeric|min:0',
    //         'start_promo' => 'required|date',
    //         'end_promo' => 'required|date|after_or_equal:start_promo',
    //         'services' => 'nullable|array',
    //     ]);
    //     $alldata=$request->all();
    //     $promotionModel = new Promotion();
    //     $result = $promotionModel->updatePromotion($alldata);
       
    //     if (!$result) {
    //         return back()->with('error', 'Un service a déjà une promotion active sur cette période');
    //     }

    //     return redirect()->route('promotiondb')
    //         ->with('success', 'Promotion mise à jour avec succès');
    // }


    public function destroy($id)
    {
        $promotion = Promotion::find($id);

        if ($promotion) {
            $promotion->delete();

            return redirect()->route('promotiondb')
                ->with('success', 'Promotion supprimée avec succès');
        }

        return redirect()->route('promotiondb')
            ->with('error', 'Promotion introuvable');
    }

}
