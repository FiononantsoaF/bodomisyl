<?php

namespace App\Http\Controllers;

use App\Models\Services;
use App\Http\Requests\ServiceRequest;
use App\Models\ServiceCategory;
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
        $services = Services::paginate();
        $activemenuservices = 1;
        return view('service.index', compact('services','activemenuservices'))
            ->with('i', (request()->input('page', 1) - 1) * $services->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service = new Services();
        $categories = ServiceCategory::all();
        $activemenuservices = 1;
        return view('service.create', compact('service','categories','activemenuservices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
//   dd($alldata);
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
        
        return redirect()->route('servicedb')
            ->with('success', 'Service créer avec success.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Services::find($id);
        $activemenuservices = 1;
        return view('service.show', compact('service','activemenuservices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Services::find($id);
         $categories = ServiceCategory::all();
         $activemenuservices = 1;
        return view('service.edit', compact('service','categories','activemenuservices'));
    }

    public function update(Request $request, Services $service)
    {
        $alldata = $request->all();
        // print_r($alldata['description']);die();
        // dd($alldata);
        // die();
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

        return redirect()->route('servicedb')
            ->with('success', 'Service modifier avec succès');
    }


    public function destroy($id)
    {
        Services::find($id)->delete();

        return redirect()->route('servicedb')
            ->with('success', 'Service deleted successfully');
    }
}
