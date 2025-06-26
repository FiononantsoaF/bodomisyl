<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\appointments;
use App\Models\Clients;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use DB;
use Response;

class AppointmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }
            

    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="creer rendez vous",
    *     @OA\RequestBody(
    *          @OA\MediaType(
    *               mediaType="application/json",
    *               @OA\Schema(
    *                   @OA\Property(
    *                       property="clients",
    *                       type="array",
    *                       @OA\Items(
    *                           @OA\Property(property="name", type="string"),
    *                           @OA\Property(property="email", type="string"),
    *                           @OA\Property(property="phone", type="string"),
    *                           @OA\Property(property="address", type="string"),
    *                           @OA\Property(property="password", type="string"),
    *                       )
    *                   ),
    *                   @OA\Property(property="employee_id", type="integer"),
    *                   @OA\Property(property="service_id", type="integer"),
    *                   @OA\Property(property="start_times", type="string", format ="date"),
    *                   @OA\Property(property="end_times", type="string", format ="date"),
    *                   @OA\Property(property="status", type="string"),
    *                   @OA\Property(property="comment", type="string"),
    *               )
    *           )
    *      ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
   
    public function create(Request $request)
    {
        $param = $request->all();
        $clientinfo = $param['clients'];
        // verification par mail et phone
        $checkclient = Clients::orWhere(["email"=>$clientinfo['email'],"phone"=>$clientinfo['phone']])->first();
        
        if($checkclient){
            // print_r($checkclient->id);
            // $appoint->end_times = $param['end_times'];
            // $appoint->status = ($param['comment']?$param['comment']:"");
            $ifclientthaveappoint = appointments::where([
                "start_times"=>$param['start_times'],
                "client_id"=> $checkclient->id, 
                //"service_id"=>$param['service_id']
                ])->first();

            if($ifclientthaveappoint){
                return Response::json([
                    'message' => "Ce client a déjà un rendez-vous pour ce date et heure",
                    "success" => false
                ], 400);
            }else{

                $appoint = new appointments();
                $appoint->client_id = $checkclient->id;
                $appoint->employee_id = $param['employee_id'];
                $appoint->service_id = $param['service_id'];
                $appoint->start_times = $param['start_times'];
                $appoint->comment = ($param['comment']?$param['comment']:"");
                $appoint->save();

                $ifclientawait = appointments::where([
                    "start_times"=>$param['start_times'],
                    "service_id"=>$param['service_id']
                ])->where('client_id', '!=', $checkclient->id)->first();
                
                if($ifclientawait){
                    $waitlist = new WaitingList();
                    $waitlist->appointment_id = $appoint->id;
                    $waitlist->save();
                }

                return Response::json(array('success' => true,'id'=> $appoint->id), 200);
            }
            
        }else{
            $dataclient = new Clients();
            $dataclient->name = $clientinfo['name'];
            $dataclient->email = $clientinfo['email'];
            $dataclient->phone = $clientinfo['phone'];
            $dataclient->address = $clientinfo['address'];
            $dataclient->password = $clientinfo['password'];
            if ($dataclient->save()) {
                
                $appoint = new appointments();
                $appoint->client_id = $dataclient->id;
                $appoint->employee_id = $param['employee_id'];
                $appoint->service_id = $param['service_id'];
                $appoint->start_times = $param['start_times'];
                $appoint->comment = ($param['comment']?$param['comment']:"");
                $appoint->save();

                $ifclientawait = appointments::where([
                    "start_times"=>$param['start_times'],
                    "service_id"=>$param['service_id']
                ])->where('client_id', '!=', $dataclient->id)->first();
                
                if($ifclientawait){
                    $waitlist = new WaitingList();
                    $waitlist->appointment_id = $appoint->id;
                    $waitlist->save();
                }

                // return array('success' => true,'id'=> $dataclient->id);
                return Response::json(array('success' => true,'id'=> $appoint->id), 200);
            }
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(appointments $appointments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(appointments $appointments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, appointments $appointments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(appointments $appointments)
    {
        //
    }
}
