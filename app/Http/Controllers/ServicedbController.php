<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Models\Employees;
use App\Http\Requests\ServiceRequest;
use App\Models\ServiceCategory;
use App\Models\ServiceSession;
use Illuminate\Http\Request;

/**
 * Class ServicedbController
 * @package App\Http\Controllers
 */
class ServicedbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Services::with('employees')->paginate();
        $employees = Employees::all();
        $activemenuservices = 1;
        return view('service.index', compact('services','activemenuservices','employees'))
            ->with('i', (request()->input('page', 1) - 1) * $services->perPage());
    }

    public function updateEmployees(Request $request, $id)
    {
        $service = Services::findOrFail($id);
        $employeeIds = $request->input('employee_ids', []); 
        $service->employees()->sync($employeeIds);
        return redirect()->back()->with('success', 'Les prestataires ont été mis à jour avec succès.');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service = new Services();
        $categories = ServiceCategory::all();
        $total_session=0;
        $activemenuservices = 1;
        return view('service.create', compact('service','categories','activemenuservices','total_session'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
        if(!isset($alldata['service_category_id'])){
                return redirect()->back()
                ->withErrors(['erreur' => "Echec de la mise à jour"])
                ->withInput();
        }
        if($alldata['session']<0){
            return redirect()->route('servicedb')
            ->with('success', 'Service créer avec success. Mais vous devez ajouter un nombre de séance valide');
        }
        Services::create([
            "title"=>$alldata['title'],
            "description"=>$alldata['description'],
            "detail"=>$alldata['detail'],
            "remarque"=>$alldata['remarque'],
            "service_category_id"=>$alldata['service_category_id'],
            "price"=>$alldata['price'],
            "duration_minutes"=>$alldata['duration_minutes'],
            "validity_days"=>$alldata['validity_days']
        ]);

        if($alldata['session']>1){
            $lastservice=Services::latest()->first();
            ServiceSession::create([
                            "services_id"=>$lastservice->id,
                            "session_id"=>1,
                            "total_session"=>$alldata['session'],
                            "session_per_period"=>1,
                            "period_type"=>"week"
                        ]);
        }
      
        return redirect()->route('servicedb')
            ->with('success', 'Service créer avec success.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Services::find($id);
        $sessions = ServiceSession::where('services_id',$id)->get();
        $total_session=$sessions->first()->total_session ?? 0;
        $activemenuservices = 1;
        return view('service.show', compact('service','activemenuservices','total_session'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Services::find($id);
         $categories = ServiceCategory::all();
         $activemenuservices = 1;
        $sessions = ServiceSession::where('services_id',$id)->get();
        $total_session=$sessions->first()->total_session ?? 0;
        return view('service.edit', compact('service','categories','activemenuservices','total_session'));
    }

    public function update(Request $request, Services $service)
    {
        $alldata = $request->all();
        if(!isset($alldata['service_category_id'])){
                return redirect()->back()
                ->withErrors(['erreur' => "Echec de la mise à jour"])
                ->withInput();
        }
        Services::where('id', $alldata['id'])->first()
                                            ->update(["title"=>$alldata['title'],
                                                    "description"=>$alldata['description'],
                                                    "detail"=>$alldata['detail'],
                                                    "remarque"=>$alldata['remarque'],
                                                    "duration_minutes"=>$alldata['duration_minutes'],
                                                    "service_category_id"=>$alldata['service_category_id'],
                                                    "price"=>$alldata['price'],
                                                    "validity_days"=>$alldata['validity_days']
                                                    ]);
        if($alldata['session']>1){
            $lastservice=Services::where('id', $alldata['id'])->first();
            $sessionexist=ServiceSession::where('services_id',$lastservice->id)->first();
            if($sessionexist){
                $sessionexist->update([
                    "total_session"=>$alldata['session']
                ]);
            }
        }

        return redirect()->route('servicedb')
            ->with('success', 'Service modifier avec succès');
    }

    public function destroy($id)
    {
        $service=Services::find($id);
        $service->changeactive();
        return redirect()->route('servicedb')
            ->with('success', 'Mise à jours effectuée');
    }

}
