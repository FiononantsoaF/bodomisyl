<?php

namespace App\Http\Controllers;

use App\Models\ServiceSession;
use App\Http\Requests\ServiceSessionRequest;
use App\Models\Services;
use App\Models\Session;
use DB;
/**
 * Class ServiceSessionController
 * @package App\Http\Controllers
 */
class ServiceSessiondbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $serviceSessions = ServiceSession::paginate();
        $activemenusession = 1;
        
        $serviceSessions = DB::table('service_session as ss')
        ->select(
            "ss.id",
            "ss.total_session",
            "ss.session_per_period",
            "ss.period_type",
            "s.title as nomservice",
            "ses.title as nomsession"
        )
        ->join('services as s', 's.id','=', 'ss.services_id')
        ->join('sessions as ses', 'ses.id', '=','ss.session_id')
        ->orderBy('ss.id','desc')->paginate(20)
        ;
        /*SELECT
        ss.id as idss, 
        ss.total_session, 
        ss.session_per_period,
        ss.period_type,
        s.title as nomservice,
        ses.title as nomsession
        FROM
        `service_session` ss 
        left join services s on s.id = ss.services_id
        left join sessions ses on ses.id = ss.session_id*/
        return view('service-session.index', compact('serviceSessions','activemenusession'))
            ->with('i', (request()->input('page', 1) - 1) * $serviceSessions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceSession = new ServiceSession();
        $services = Services::all();
        $sessions = Session::all();
        $activemenusession = 1;
        // return view('service.create', compact('service','categories'));
        return view('service-session.create', compact('serviceSession','services','sessions','activemenusession'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceSessionRequest $request)
    {
        ServiceSession::create($request->validated());

        return redirect()->route('service-session')
            ->with('success', 'Association effectué avec sucès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $serviceSession = ServiceSession::find($id);

        return view('service-session.show', compact('serviceSession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $serviceSession = ServiceSession::find($id);
        $services = Services::all();
        $sessions = Session::all();
        return view('service-session.edit', compact('serviceSession','services','sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceSessionRequest $request, ServiceSession $serviceSession)
    {
        $serviceSession->update($request->validated());

        return redirect()->route('service_session.index')
            ->with('success', 'ServiceSession updated successfully');
    }

    public function destroy($id)
    {
        ServiceSession::find($id)->delete();

        return redirect()->route('service_session.index')
            ->with('success', 'ServiceSession deleted successfully');
    }
}
