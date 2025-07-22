<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Category;
use App\Http\Requests\SessionRequest;
use Illuminate\Http\Request;
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
        $category = Category::all();
        return view('session.create', compact('session', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
        // dd($alldata);
        Session::create([
            "title"=>$alldata['title'],
            "category_id"=>$alldata['category_id']
        ]);

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
        $category = Category::all();
        return view('session.edit', compact('session','category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        $alldata = $request->all();
        $session->where('id', $alldata['id'])->first()
                                            ->update(["title"=>$alldata['title'],
                                            "category_id"=>$alldata['category_id'],
                                            ]);
        return redirect()->route('sessiondb')
            ->with('success', 'Session modifiée avec succès');
    }


    public function destroy($id)
    {
        Session::find($id)->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Session deleted successfully');
    }
}
