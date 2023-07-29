<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Repositories\Book\BookRepository;

class HomeController extends Controller
{
    public $bookRepository;

    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    public function index(Request $request)
    {
        $query = $request->query('query');

        try {
            $books = $this->bookRepository->getAllBooks($query);

            if($books->isEmpty()){
                return response()->json([
                    'message' => 'No books found'. $query !== null ? 'for '.$query : '',
                    'books' => []
                ], 404);
            }

            return response()->json([
                'books' => new BookResource($books),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
