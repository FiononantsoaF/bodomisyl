<?php

namespace App\Http\Controllers;

use App\Models\CarteCadeau;
use App\Http\Requests\CarteCadeauRequest;

/**
 * Class CarteCadeauController
 * @package App\Http\Controllers
 */
class CarteCadeauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carteCadeaus = CarteCadeau::paginate();

        return view('carte-cadeau.index', compact('carteCadeaus'))
            ->with('i', (request()->input('page', 1) - 1) * $carteCadeaus->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $carteCadeau = new CarteCadeau();
        return view('carte-cadeau.create', compact('carteCadeau'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarteCadeauRequest $request)
    {
        CarteCadeau::create($request->validated());

        return redirect()->route('carte-cadeaus.index')
            ->with('success', 'CarteCadeau created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carteCadeau = CarteCadeau::find($id);

        return view('carte-cadeau.show', compact('carteCadeau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $carteCadeau = CarteCadeau::find($id);

        return view('carte-cadeau.edit', compact('carteCadeau'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarteCadeauRequest $request, CarteCadeau $carteCadeau)
    {
        $carteCadeau->update($request->validated());

        return redirect()->route('carte-cadeaus.index')
            ->with('success', 'CarteCadeau updated successfully');
    }

    public function destroy($id)
    {
        CarteCadeau::find($id)->delete();

        return redirect()->route('carte-cadeaus.index')
            ->with('success', 'CarteCadeau deleted successfully');
    }
}
