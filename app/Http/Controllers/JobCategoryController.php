<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Http\Requests\JobCategoryRequest;
use Illuminate\Http\Request;
/**
 * Class JobCategoryController
 * @package App\Http\Controllers
 */
class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobCategories = JobCategory::paginate();
        $menuemployee = 1;
        return view('job-category.index', compact('jobCategories','menuemployee'))
            ->with('i', (request()->input('page', 1) - 1) * $jobCategories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobCategory = new JobCategory();
        return view('job-category.create', compact('jobCategory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $alldata = $request->all();
        JobCategory::create([
            "name"=>$alldata['name']
        ]);
        return redirect()->route('jobdb')
            ->with('success', 'Emplois créé avec succèss');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jobCategory = JobCategory::find($id);

        return view('job-category.show', compact('jobCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jobCategory = JobCategory::find($id);
        return view('job-category.edit', compact('jobCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
 
    public function update(Request $request, $id)
    {
        $alldata = $request->all();

        $job = JobCategory::findOrFail($id);
        $job->update([
            "name" => $alldata['name']
        ]);

        return redirect()->route('jobdb')
            ->with('success', 'Mise à jour avec succès');
    }



    public function destroy($id)
    {
        JobCategory::find($id)->delete();

        return redirect()->route('jobdb')
            ->with('success', 'Emplois supprimé');
    }
}
