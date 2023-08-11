<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Repositories\Book\BookRepository;
use App\Http\Requests\API\BookCreateRequest;

class BookController extends Controller
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
                    'message' => $query !== null ? 'No books found for '.$query : 'No books found.',
                    'books' => []
                ], 404);
            }

            return response()->json([
                'books' => BookResource::collection($books),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(BookCreateRequest $bookCreateRequest)
    {
        $data = $bookCreateRequest->validated();

        if ($bookCreateRequest->hasFile('cover_image')) {
            $file_name = $bookCreateRequest->file('cover_image')->hashName();
            $bookCreateRequest->file('cover_image')->storePubliclyAs('public/cover_images', $file_name);
            //$image = "data:image/png;base64,".base64_encode(file_get_contents($bookCreateRequest->file('cover_image')->path()));
            $data['cover_image'] = $file_name;
        }

        $book = $this->bookRepository->createBook($data);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => new BookResource($book),
        ], 201);
    }
}
