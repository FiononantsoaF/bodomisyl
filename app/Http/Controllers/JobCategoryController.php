<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Http\Requests\JobCategoryRequest;

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

        return view('job-category.index', compact('jobCategories'))
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
    public function store(JobCategoryRequest $request)
    {
        JobCategory::create($request->validated());

        return redirect()->route('job-categories.index')
            ->with('success', 'JobCategory created successfully.');
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
    public function update(JobCategoryRequest $request, JobCategory $jobCategory)
    {
        $jobCategory->update($request->validated());

        return redirect()->route('job-categories.index')
            ->with('success', 'JobCategory updated successfully');
    }

    public function destroy($id)
    {
        JobCategory::find($id)->delete();

        return redirect()->route('job-categories.index')
            ->with('success', 'JobCategory deleted successfully');
    }
}
