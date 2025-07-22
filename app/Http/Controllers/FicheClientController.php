<?php

namespace App\Http\Controllers;

use App\Models\FicheClient;
use App\Http\Requests\FicheClientRequest;

/**
 * Class FicheClientController
 * @package App\Http\Controllers
 */
class FicheClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ficheClients = FicheClient::paginate();

        return view('fiche-client.index', compact('ficheClients'))
            ->with('i', (request()->input('page', 1) - 1) * $ficheClients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ficheClient = new FicheClient();
        return view('fiche-client.create', compact('ficheClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FicheClientRequest $request)
    {
        FicheClient::create($request->validated());

        return redirect()->route('fiche-clients.index')
            ->with('success', 'FicheClient created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ficheClient = FicheClient::find($id);

        return view('fiche-client.show', compact('ficheClient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ficheClient = FicheClient::find($id);

        return view('fiche-client.edit', compact('ficheClient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FicheClientRequest $request, FicheClient $ficheClient)
    {
        $ficheClient->update($request->validated());

        return redirect()->route('fiche-clients.index')
            ->with('success', 'FicheClient updated successfully');
    }

    public function destroy($id)
    {
        FicheClient::find($id)->delete();

        return redirect()->route('fiche-clients.index')
            ->with('success', 'FicheClient deleted successfully');
    }
}
