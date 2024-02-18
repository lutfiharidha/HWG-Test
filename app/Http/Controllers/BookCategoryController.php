<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookCategory;
use App\Http\Resources\BookCategoryResource;

class BookCategoryController extends Controller
{
    public function index()
    {
        $categories = BookCategory::all();
        return response()->json(['data' => $categories]);
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = new BookCategory();
        $category->category_name = $validatedData['category_name'];
        $category->save();

        return response()->json(['message' => 'Category created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $category = BookCategory::findOrFail($id);

        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category->category_name = $validatedData['category_name'];

        $category->save();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function delete($id)
    {
        $category = BookCategory::findOrFail($id);

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}

