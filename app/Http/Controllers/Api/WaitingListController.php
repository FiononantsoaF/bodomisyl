<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WaitingList;
use Illuminate\Http\Request;

class WaitingListController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(WaitingList $waitingList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaitingList $waitingList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaitingList $waitingList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaitingList $waitingList)
    {
        //
    }
}
