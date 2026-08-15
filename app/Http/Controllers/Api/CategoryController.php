<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Data retrivied successfully',
            'data' => CategoryResource::collection($categories),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);
        $category = Category::create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data retrivied successfully',
            'data' => new CategoryResource($category),
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);
        $category->update($validated);
        return response()->json([
            'status' => true,
            'message' => 'Data updated successfully',
            'data' => new CategoryResource($category),
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data delete successfully'
        ],200);
    }
}
