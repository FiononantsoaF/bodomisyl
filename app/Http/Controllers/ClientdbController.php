<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\appointments;
use App\Models\Subscription;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use DB;
use DateTime;


/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientdbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Clients::paginate();
        $param=$request->all();

        $phone = (isset($param['phone']) && !isset($param['reset'])) ?$param['phone']:'';
        $email = (isset($param['email']) && !isset($param['reset']))?$param['email']:'';
        $clients = DB::table('clients as cl')
        ->select("cl.id",
        "cl.name",
        "cl.phone",
        "cl.email",
        "cl.address")
        ->when($phone , function ($query, string $phone) {
            $query->where('cl.phone', $phone);
        })
        ->when($email , function ($query, string $email) {
            $query->where('cl.email', $email);
        })
        ->orderBy('cl.id','desc')->paginate(10);
        if($phone){
            $clients->where(['cl.phone' => $phone]);
        }
        if($email){
            $clients->where(['cl.email' => $email]);
        }
        $appointments = appointments::getallappointmentsbyclient();
        $subscriptions= Subscription::getallsubscriptionsbyclient();
        $menuclient = 1;
        return view('client.index', compact('clients','menuclient','appointments','subscriptions','phone','email'))
            ->with('i', (request()->input('page', 1) - 1) * $clients->perPage());
    }

    public function changepassword(Request $request)
    {
        $param = $request->all();
        if (isset($param['id'])) {
            $client = new Clients();
            $client->updateClientInformation(
                $param['id'], 
                $param['name'], 
                $param['phone'], 
                $param['email'], 
                $param['adress']
            );
            if (isset($param['liste']) && $param['liste'] == 1) {
                return redirect()->route('clientdb')
                    ->with('success', 'Mise à jour effectuée');
            } else {
                return redirect()->route('fichedb', ['id' => $param['id']])
                    ->with('success', 'Mise à jour effectuée');
            }
        } else {
            return redirect()->route('clientdb')
                ->with('erreur', 'Mise à jour non effectuée');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $client = new Clients();
        return view('client.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        Client::create($request->validated());

        return redirect()->route('clientdb')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::find($id);

        return view('client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $client = Client::find($id);

        return view('client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return redirect()->route('clientdb')
            ->with('success', 'Client updated successfully');
    }

    public function destroy($id)
    {
        Client::find($id)->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }
}
