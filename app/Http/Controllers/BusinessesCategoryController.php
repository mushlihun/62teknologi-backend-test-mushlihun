<?php

namespace App\Http\Controllers;

use App\Models\BusinessesCategory;
use App\Http\Requests\StoreBusinessesCategoryRequest;
use App\Http\Requests\UpdateBusinessesCategoryRequest;

class BusinessesCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBusinessesCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBusinessesCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BusinessesCategory  $businessesCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessesCategory $businessesCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BusinessesCategory  $businessesCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessesCategory $businessesCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBusinessesCategoryRequest  $request
     * @param  \App\Models\BusinessesCategory  $businessesCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBusinessesCategoryRequest $request, BusinessesCategory $businessesCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BusinessesCategory  $businessesCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessesCategory $businessesCategory)
    {
        //
    }
}
