<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;


/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $users = User::paginate();
        $menuuser = 1;
        return view('user.index', compact('users','menuuser'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();
        $menuuser = 1;
        $roles = ['admin'=>'admin','prestataire'=>'prestataire'];
        return view('user.create', compact('user','menuuser','roles'));
    }

    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $param = $request->all();
        // User::create($request->validated());
        $user = User::create([
            'name'=>$param['name'],
            'email' => $param['email'],
            'password' =>bcrypt($param['password']),
            'role' => $param['role'] ?? 'null'
        ]);

        return redirect()->route('userdb')
            ->with('success', 'Utilisateur ajouté avec succès');
    }

    /**
     * Display the specified resource.
    */
    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        $menuuser = 1;
        $roles = ['admin'=>'admin','prestataire'=>'prestataire'];
        return view('user.edit', compact('user','menuuser','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $param = $request->all();
        // print_r($id);die();

        User::where('id', $id)->first()
        ->update([
            'name'=>$param['name'],
            'email' => $param['email'],
            'role' => $param['role'] ?? 'NULL'
        ]);

        if($param['password'])
        {
            User::where('id', $id)->first()
            ->update(['password' =>bcrypt($param['password'])]);
        }

        /*$user->update([
            'name'=>$param['name'],
            'email' => $param['email'],
            'password' =>bcrypt($param['password'])
        ]);*/

        return redirect()->route('userdb')
            ->with('success', 'Utilisateur modifier avec succès');
    }

    public function destroy($id)
    {
        User::find($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
