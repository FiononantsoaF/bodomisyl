<?php

namespace App\Http\Controllers;

use App\Models\Creneau;
use App\Http\Requests\CreneauRequest;
use Illuminate\Http\Request;

/**
 * Class CreneauController
 * @package App\Http\Controllers
 */
class CreneaudbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creneaus = Creneau::orderByRaw("STR_TO_DATE(creneau, '%H:%i') asc")->paginate();
        $menuemployee = 1;
        return view('creneau.index', compact('creneaus', 'menuemployee'))
            ->with('i', (request()->input('page', 1) - 1) * $creneaus->perPage());
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $creneau = new Creneau();
        return view('creneau.create', compact('creneau'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'creneau' => 'required|date_format:H:i',
        ]);
        $existe = Creneau::where('creneau', $request->input('creneau'))->exists();
        if ($existe) {
            return redirect()->back()
                ->withErrors(['creneau' => 'Ce créneau existe déjà.'])
                ->withInput();
        }
        Creneau::create([
            'creneau' => $request->input('creneau'),
        ]);

        return redirect()->route('creneaudb')
            ->with('success', 'Créneau créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $creneau = Creneau::find($id);

        return view('creneau.show', compact('creneau'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $creneau = Creneau::find($id);

        return view('creneau.edit', compact('creneau'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreneauRequest $request, Creneau $creneau)
    {
        $creneau->update($request->validated());

        return redirect()->route('creneaudb')
            ->with('success', 'Creneau updated successfully');
    }

    public function updatecreneau(Request $request)
    {
        $alldata=$request->all();
        $creneau = Creneau::findOrFail($alldata['id']);
        $creneau->changeactive();
        return redirect()->route('creneaudb')
            ->with('success', 'Mise à jour créneau réussie');
    }
 
    public function destroy($id)
    {
        Creneau::find($id)->delete();

        return redirect()->route('creneaudb')
            ->with('success', 'Creneau deleted successfully');
    }
}
