<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Borrowing;
use Illuminate\Validation\Rule;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCategoryResource;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $searchTermCat = $request->input('category');
        $searchTermTitle = $request->input('title');
        $page = $request->input('page');
        $limit = $request->input('limit')==null?10:$request->input('limit');

        $query = Book::query()->with('category');

        if ($searchTermCat !== null && trim($searchTermCat) !== '') {
            $query->whereHas('category', function ($query) use ($searchTermCat) {
                $query->where('category_name', 'like', '%' . $searchTermCat . '%');
            });
        }

        if ($searchTermTitle !== null && trim($searchTermTitle) !== '') {
            $query->where('title', 'like', '%' . $searchTermTitle . '%');
        }

        $books = $query->simplePaginate($limit);
        $books->appends(['category' => $searchTermCat, 'limit' => $limit]);
        $books->setPath(url()->current());

        return BookResource::collection($books);
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'publisher' => 'required|string',
                'publication_date' => 'required|date',
                'stock' => 'required|integer',
                'category_id' => [
                    'required',
                    'uuid',
                    Rule::exists('book_categories', 'id'),
                ],
            ]);

            $book = new Book();
            $book->title = $validatedData['title'];
            $book->author = $validatedData['author'];
            $book->publisher = $validatedData['publisher'];
            $book->publication_date = $validatedData['publication_date'];
            $book->stock = $validatedData['stock'];
            $book->category_id = $validatedData['category_id'];
            $book->save();

            return response()->json(['message' => 'Book created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'publisher' => 'required|string',
                'publication_date' => 'required|date',
                'stock' => 'required|integer',
                'category_id' => [
                    'required',
                    'uuid',
                    Rule::exists('book_categories', 'id'),
                ],
            ]);

            $book = Book::findOrFail($id);
            $book->title = $validatedData['title'];
            $book->author = $validatedData['author'];
            $book->publisher = $validatedData['publisher'];
            $book->publication_date = $validatedData['publication_date'];
            $book->stock = $validatedData['stock'];
            $book->category_id = $validatedData['category_id'];
            $book->save();

            return response()->json(['message' => 'Book updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        try {
            $book = Book::findOrFail($id);
            $borrow = Borrowing::where("book_id",$id)->where("status","borrowed")->get();
            if (count($borrow) > 0){
                return response()->json(['error' => 'Book cannot be deleted because they are still being borrowed.'], 400);
            }
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
         } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

