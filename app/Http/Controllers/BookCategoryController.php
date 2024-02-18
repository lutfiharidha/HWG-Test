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
        try {
            $validatedData = $request->validate([
                'category_name' => 'required|string|max:255',
            ]);

            $category = new BookCategory();
            $category->category_name = $validatedData['category_name'];
            $category->save();

            return response()->json(['message' => 'Category created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'category_name' => 'required|string|max:255',
            ]);

            $category = BookCategory::findOrFail($id);
            $category->category_name = $validatedData['category_name'];
            $category->save();

            return response()->json(['message' => 'Category updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        try {
            $category = BookCategory::findOrFail($id);
            $category->delete();

            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

