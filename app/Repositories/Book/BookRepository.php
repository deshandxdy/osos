<?php

namespace App\Repositories\Book;

use App\Models\Book;

class BookRepository
{
    public $book;

    public function __construct(Book $book) {
        $this->book = $book;
    }

    public function getAllBooks($search)
    {
        try {
            return $this->book->with('author')
                ->whereHas('author', function($q) {
                    $q->where('status', true);
                })
                ->when($search !== null, function($query) use($search) {
                    $query->where('title', 'like', "%{$search}%")
                    ->orWhereHas('author', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%");
                        $q->orWhere('last_name', 'like', "%{$search}%");
                    });
                })
                ->paginate();

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createBook($bookData)
    {
        try {
            $author = auth()->user()->author;
            $book = $author->books()->create($bookData);

            return $book;

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}