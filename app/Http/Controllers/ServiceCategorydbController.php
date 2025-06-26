<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Http\Requests\ServiceCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
/**
 * Class ServiceCategoryController
 * @package App\Http\Controllers
 */
class ServiceCategorydbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serviceCategories = ServiceCategory::paginate();
        $activemenuservices = 1;
        return view('service-category.index', compact('serviceCategories','activemenuservices'))
            ->with('i', (request()->input('page', 1) - 1) * $serviceCategories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceCategory = new ServiceCategory();
        $activemenuservices = 1;
        return view('service-category.create', compact('serviceCategory','activemenuservices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceCategoryRequest $request)
    {
        ServiceCategory::create($request->validated());

        return redirect()->route('service-categorydb')
            ->with('success', 'Création effectué avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $serviceCategory = ServiceCategory::find($id);
        $activemenuservices = 1;
        return view('service-category.show', compact('serviceCategory','activemenuservices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $serviceCategory = ServiceCategory::find($id);
        $activemenuservices = 1;
        return view('service-category.edit', compact('serviceCategory','activemenuservices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        // $serviceCategory->update($request->validated());
        // $serviceCategory->update(["name"=>"test","description"=>"test"]);
        $alldata = $request->all();


        ServiceCategory::where('id', $alldata['id'])->first()->update(["name"=>$alldata['name'],"description"=>$alldata['description']]);

        return redirect()->route('service-categorydb')
            ->with('success', 'Modification effectué avec succès');
    }

    public function destroy($id)
    {
        ServiceCategory::find($id)->delete();

        return redirect()->route('service-categories.index')
            ->with('success', 'ServiceCategory deleted successfully');
    }
}
