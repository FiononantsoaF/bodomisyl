<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Http\Requests\SessionRequest;

/**
 * Class SessionController
 * @package App\Http\Controllers
 */
class SessiondbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Session::paginate();
        $activemenusession = 1;
        return view('session.index', compact('sessions','activemenusession'))
            ->with('i', (request()->input('page', 1) - 1) * $sessions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $session = new Session();
        return view('session.create', compact('session'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SessionRequest $request)
    {
        Session::create($request->validated());

        return redirect()->route('sessiondb')
            ->with('success', 'Session créer avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $session = Session::find($id);

        return view('session.show', compact('session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $session = Session::find($id);

        return view('session.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SessionRequest $request, $id)
    {
        $session = Session::find($id);
        $session->update($request->validated());

        return redirect()->route('sessiondb')
            ->with('success', 'Session modifié avec succès');
    }

    public function destroy($id)
    {
        Session::find($id)->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Session deleted successfully');
    }
}
