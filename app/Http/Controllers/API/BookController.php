<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Repositories\Book\BookRepository;
use App\Http\Requests\API\BookCreateRequest;
use App\Http\Requests\API\UpdateBookRequest;

class BookController extends Controller
{
    public $bookRepository;

    public function __construct(BookRepository $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    //get all books with query
    public function index(Request $request)
    {
        $query = $request->query('query');

        try {
            $books = $this->bookRepository->getAllBooks($query);

            if($books->isEmpty()){
                return response()->json([
                    'message' => $query !== null ? 'No books found for '.$query : 'No books found.',
                    'books' => BookResource::collection($books)
                ], 200);
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

    //get books owned to author
    public function getAuthorBooks()
    {
        try {
            $books = $this->bookRepository->getAuthorBooks();

            if($books->isEmpty()){
                return response()->json([
                    'message' => 'No books found.',
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

    //store a book
    public function store(BookCreateRequest $bookCreateRequest)
    {
        $data = $bookCreateRequest->validated();

        //save image to local storage
        if ($bookCreateRequest->hasFile('cover_image')) {
            $file_name = $bookCreateRequest->file('cover_image')->hashName();
            $bookCreateRequest->file('cover_image')->storePubliclyAs('public/cover_images', $file_name);
            $data['cover_image'] = $file_name;
        }

        $book = $this->bookRepository->createBook($data);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => new BookResource($book),
        ], 201);
    }

    //update a book
    public function update(UpdateBookRequest $updateBookRequest)
    {
        $data = $updateBookRequest->validated();

        //save image to local storage
        if ($updateBookRequest->hasFile('cover_image')) {
            $file_name = $updateBookRequest->file('cover_image')->hashName();
            $updateBookRequest->file('cover_image')->storePubliclyAs('public/cover_images', $file_name);
            $data['cover_image'] = $file_name;
        }

        $book = $this->bookRepository->updateBook($data);

        return response()->json([
            'message' => 'Book update successfully',
            'book' => new BookResource($book),
        ], 201);
    }
}
