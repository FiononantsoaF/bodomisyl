<?php

namespace App\Http\Controllers;

use App\Models\CarteCadeauService;
use App\Http\Requests\CarteCadeauServiceRequest;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CarteCadeauServiceController
 * @package App\Http\Controllers
 */
class CarteCadeauServicedbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CarteCadeauService::with('service');
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        

        $services = Services::all();
        $carteCadeauServices = $query->paginate(20);
        $activemenuccs = 1;
        return view('carte-cadeau-service.index', compact('carteCadeauServices','activemenuccs','services'))
            ->with('i', (request()->input('page', 1) - 1) * $carteCadeauServices->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $carteCadeauService = new CarteCadeauService();
            $servicesNonInclus = Services::whereNotIn('id', CarteCadeauService::pluck('service_id'))->get();

            return response()->json([
                'carteCadeauService' => $carteCadeauService,
                'servicesNonInclus' => $servicesNonInclus
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Recherche de tous les services pour le filtre (Select2)
     */
    public function searchAll(Request $request)
    {
        try {
            $query = $request->input('q', '');
            $page = (int) $request->input('page', 1);
            $perPage = 15;

            $servicesQuery = Services::where('title', 'LIKE', "%{$query}%")
                ->orderBy('title', 'asc');

            $total = $servicesQuery->count();

            $services = $servicesQuery
                ->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get(['id', 'title']);

            // Format pour Select2
            $results = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'text' => $service->title
                ];
            });

            return response()->json([
                'results' => $results,
                'pagination' => [
                    'more' => ($page * $perPage) < $total
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur recherche tous services: ' . $e->getMessage());
            return response()->json([
                'results' => [],
                'pagination' => ['more' => false]
            ], 500);
        }
    }

    /**
     * Recherche pour filtrer les cartes cadeaux dans le tableau
    */
    public function search(Request $request)
    {
        try {
            $query = CarteCadeauService::with('service');
            if ($request->filled('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            if ($request->filled('q')) {
                $query->whereHas('service', function($q) use ($request) {
                    $q->where('title', 'LIKE', '%'.$request->q.'%');
                });
            }

            $carteCadeauServices = $query->get();

            // Retour JSON simplifié pour le tableau
            $data = $carteCadeauServices->map(function($carte) {
                return [
                    'id' => $carte->id,
                    'service' => [
                        'id' => $carte->service->id ?? null,
                        'title' => $carte->service->title ?? '—',
                    ],
                    'reduction_percent' => $carte->reduction_percent,
                    'amount' => $carte->amount,
                    'is_active' => $carte->is_active,
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erreur recherche cartes cadeaux: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'services' => 'required|array|min:1',
                'services.*.service_id' => 'required|exists:services,id',
                'services.*.reduction_percent' => 'nullable|numeric|min:0|max:100',
                'services.*.amount' => 'nullable|numeric|min:0',
            ], [
                'services.required' => 'Veuillez sélectionner au moins un service.',
                'services.*.service_id.exists' => 'Le service sélectionné n\'existe pas.',
                'services.*.reduction_percent.max' => 'La réduction ne peut pas dépasser 100%.',
                'services.*.amount.min' => 'Le montant doit être positif.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $services = $request->input('services');
            $savedCount = 0;

            DB::beginTransaction();

            try {
                foreach ($services as $serviceData) {
                    if (empty($serviceData['reduction_percent']) && empty($serviceData['amount'])) {
                        continue; 
                    }

                    // Vérifier que les deux valeurs ne sont pas saisies en même temps
                    if (!empty($serviceData['reduction_percent']) && !empty($serviceData['amount'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Vous ne pouvez pas saisir à la fois une réduction et un montant fixe pour le même service.'
                        ], 422);
                    }

                    // Vérifier que le service n'existe pas déjà
                    $exists = CarteCadeauService::where('service_id', $serviceData['service_id'])->exists();
                    if ($exists) {
                        continue; // Ignorer si déjà existant
                    }

                    // Créer l'enregistrement
                    CarteCadeauService::create([
                        'service_id' => $serviceData['service_id'],
                        'reduction_percent' => $serviceData['reduction_percent'] ?? null,
                        'amount' => $serviceData['amount'] ?? null,
                        'is_active' => true, // Par défaut actif
                    ]);

                    $savedCount++;
                }

                DB::commit();

                if ($savedCount === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun service n\'a été ajouté. Veuillez vérifier vos données.'
                    ], 400);
                }

                return response()->json([
                    'success' => true,
                    'message' => $savedCount . ' service(s) ajouté(s) avec succès à la carte cadeau.'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout des services carte cadeau: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carteCadeauService = CarteCadeauService::find($id);

        return view('carte-cadeau-service.show', compact('carteCadeauService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $carteCadeauService = CarteCadeauService::find($id);
        return view('carte-cadeau-service.edit', compact('carteCadeauService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'reduction_percent' => 'nullable|numeric|min:0|max:100',
            'amount' => 'nullable|numeric|min:0',
        ]);

        $carteCadeauService = CarteCadeauService::findOrFail($id);
        $carteCadeauService->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Mise à jour réussie',
            'data' => $carteCadeauService
        ]);
    }



    public function destroy($id)
    {
        $carte = CarteCadeauService::findOrFail($id);
        $carte->delete();

        return redirect()->route('cartecadeauservicedb')
            ->with('success', 'Carte cadeau supprimée avec succès');
    }

}
