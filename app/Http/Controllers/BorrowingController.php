<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use Illuminate\Validation\Rule;
use App\Http\Resources\BorrowingResource;
use DateTime;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('book_title');
        $status = $request->input('status');
        $page = $request->input('page');
        $limit = $request->input('limit')==null?10:$request->input('limit');

        $query = Borrowing::query()->with('book');

        if ($searchTerm !== null && trim($searchTerm) !== '') {
            $query->whereHas('book', function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%');
            });
        }

         if ($status !== null && trim($status) !== '') {
            $query->where('status',  $status);
        }

        $borrowings = $query->simplePaginate($limit);

        $borrowings->appends(['book_title' => $searchTerm, 'limit' => $limit, 'status' => $status]);

        $borrowings->setPath(url()->current());

        return BorrowingResource::collection($borrowings);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'borrow_date' => 'required|string',
            'duration' => 'required|integer',
            'book_id' => [
                'required',
                'uuid',
                Rule::exists('books', 'id'),
            ],
        ]);

        $dateTime = DateTime::createFromFormat('d-m-Y', $validatedData['borrow_date']);
        $dateTime->modify('+' . $validatedData['duration'] . ' days');
        $newDate = $dateTime->format('d-m-Y');

        $book = Book::findOrFail($validatedData['book_id']);
        if($book->stock > 0 ){
            $borrow = new Borrowing();
            $borrow->borrow_date = $validatedData['borrow_date'];
            $borrow->return_date = $newDate;
            $borrow->status = "borrowed";
            $borrow->book_id = $validatedData['book_id'];
            $borrow->save();

            $book->stock -= 1;
            $book->save();
            return response()->json(['message' => 'Book borrowing created successfully'], 201);
        }
            return response()->json(['message' => 'Book out of stock'], 200);
    }

    public function return($id)
    {
        $borrow = Borrowing::findOrFail($id);
        if($borrow->status == "borrowed"){
            $book = Book::findOrFail($borrow->book_id);
            $book->stock += 1;
            $book->save();
        }
        $return_date = DateTime::createFromFormat('d-m-Y', $borrow->return_date);
        $actual_return_date = DateTime::createFromFormat('d-m-Y', date('d-m-Y'));
        if($actual_return_date > $return_date ){
            $borrow->status = "overdue";
        }else{
            $borrow->status = "returned";
        }
        $borrow->actual_return_date = date('d-m-Y');
        $borrow->save();



        return response()->json(['message' => 'Book returned successfully'], 200);
    }

    public function delete($id)
    {
        $book = Borrowing::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book borrowing deleted successfully'], 200);
    }
}
