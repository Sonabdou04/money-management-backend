<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Category::all(),
            'message' => 'Categories retrieved successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'type' => 'required|in:income,expense',
        ]);

        $category = Category::create($validated);
        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:categories,name,' . $category->id,
            'type' => 'sometimes|in:income,expense',
        ]);

        $category->update($validated);
        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'Category deleted successfully.'
        ], 204);
    }
}
