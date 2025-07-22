<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Employees;
use App\Models\Services;
use App\Models\Clients;
use App\Models\Creneau;

use App\Http\Requests\SubscriptionRequest;
use Illuminate\Http\Request;
use DB;
use Response;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptiondbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $param = $request->all();
        $phone = (isset($param['phone']) && !isset($param['reset'])) ?$param['phone']:'';
        $email = (isset($param['email']) && !isset($param['reset']))?$param['email']:'';

        $subscriptions = DB::table('subscriptions as ab')
        ->select("ab.id as idab",
        "ab.status",
        "c.name as nomclient",
        "c.email",
        "sc.name as nomservice",
        "s.title as typeprestation",
        "ab.total_session as total",
        "ab.used_session as session_achevee",
        "s.price as prixservice",
        DB::raw("date_format(ab.period_start,'%d-%m-%Y %H:%i%:%s') as period_start"),
        DB::raw("date_format(ab.period_end,'%d-%m-%Y %H:%i%:%s') as period_end"),
        DB::raw("date_format(ab.created_at,'%d-%m-%Y %H:%i%:%s') as date_creation"),
        )
        ->when($phone , function ($query, string $phone) {
            $query->where('c.phone', $phone);
        })
        ->when($email , function ($query, string $email) {
            $query->where('c.email', $email);
        })
        ->join('clients as c', 'c.id','=', 'ab.client_id')
        ->join('services as s', 's.id' ,'=', 'ab.services_id')
        ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
        
        ->orderBy('ab.id','desc')->paginate(10);
        if($phone){
            $subscriptions->where(['c.phone' => $phone]);
        }
        if($email){
            $subscriptions ->where(['c.email' => $email]);
        }

        $activemenuappoint = 1;
        return view('subscription.index', compact('subscriptions','activemenuappoint','phone','email'))
            ->with('i', (request()->input('page', 1) - 1) * $subscriptions->perPage());
        // $subscriptions = Subscription::paginate();

        // return view('subscription.index', compact('subscriptions'))
        //     ->with('i', (request()->input('page', 1) - 1) * $subscriptions->perPage());

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subscription = new Subscription();
        return view('subscription.create', compact('subscription'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request)
    {
        Subscription::create($request->validated());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subscription = Subscription::find($id);

        return view('subscription.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscription = Subscription::find($id);

        return view('subscription.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully');
    }

    public function destroy($id)
    {
        Subscription::find($id)->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully');
    }

    
    public function continue(Request $request)
    {
        $id = $request->input('subscription_id');
        $subscription = Subscription::find($id);
        $client = Clients::find($subscription->client_id);
        $service= Services::find($subscription->services_id);
        $creneau = Creneau::orderBy('creneau', 'asc')->get();
        $employee = Employees::all();
        return view('appointment.createrdv', compact('subscription', 'employee','service','client','creneau'));
    }

}
