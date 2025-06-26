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
        return view('service.index', compact('services'))
            ->with('i', (request()->input('page', 1) - 1) * $services->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $service = new Services();
        $categories = ServiceCategory::all();
        return view('service.create', compact('service','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        Services::create($request->validated());

        return redirect()->route('servicedb')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = Services::find($id);
       
        return view('service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = Services::find($id);
         $categories = ServiceCategory::all();
        return view('service.edit', compact('service','categories'));
    }

    public function update(Request $request, Services $service)
    {
        $alldata = $request->all();
        // dd($alldata);
        Services::where('id', $alldata['id'])->first()
                                            ->update(["title"=>$alldata['title'],
                                                    "description"=>$alldata['description'],
                                                    "duration_minutes"=>$alldata['duration_minutes'],
                                                    "service_category_id"=>$alldata['service_category_id'],
                                                    "price"=>$alldata['price'],
                                                    "validity_days"=>$alldata['validity_days']
                                                    ]);

        return redirect()->route('servicedb')
            ->with('success', 'Service updated successfully');
    }


    public function destroy($id)
    {
        Services::find($id)->delete();

        return redirect()->route('servicedb')
            ->with('success', 'Service deleted successfully');
    }
}
