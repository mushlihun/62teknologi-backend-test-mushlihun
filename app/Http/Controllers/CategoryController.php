<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data'    => $categories  
        ], 200);
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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $restaurant = Category::create([
            'name'     => $request->name,
        ]);

        if($restaurant) {

            return response()->json([
                'success' => true,
                'message' => 'Category Created',
                'data'    => $restaurant  
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category Failed to Save',
        ], 409);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::findOrfail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Category',
            'data'    => $category 
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::findOrFail($category->id);

        if($category) {

            $category->update([
            'name'     => $request->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $category  
            ], 200);

        }

        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrfail($id);

        if($category) {


            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category Deleted',
            ], 200);

        }

        return response()->json([
            'success' => false,
            'message' => 'Category Not Found',
        ], 404);
    }
}
