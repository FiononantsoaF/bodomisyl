<?php

namespace App\Http\Controllers;

use App\Models\CarteCadeauClient;
use App\Http\Requests\CarteCadeauClientRequest;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * Class CarteCadeauClientController
 * @package App\Http\Controllers
 */
class CarteCadeauClientdbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carteCadeauClients = CarteCadeauClient::with('carteCadeauService.service.serviceCategory')->paginate(20);
        $activemenuccs = 1;
        return view('carte-cadeau-client.index', compact('carteCadeauClients','activemenuccs'))
            ->with('i', (request()->input('page', 1) - 1) * $carteCadeauClients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carteCadeauClient = new CarteCadeauClient();
        return view('carte-cadeau-client.create', compact('carteCadeauClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarteCadeauClientRequest $request)
    {
        CarteCadeauClient::create($request->validated());

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carteCadeauClient = CarteCadeauClient::find($id);

        return view('carte-cadeau-client.show', compact('carteCadeauClient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $carteCadeauClient = CarteCadeauClient::find($id);

        return view('carte-cadeau-client.edit', compact('carteCadeauClient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarteCadeauClientRequest $request, CarteCadeauClient $carteCadeauClient)
    {
        $carteCadeauClient->update($request->validated());

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient updated successfully');
    }

    public function destroy($id)
    {
        CarteCadeauClient::find($id)->delete();

        return redirect()->route('carte-cadeau-clients.index')
            ->with('success', 'CarteCadeauClient deleted successfully');
    }
}
