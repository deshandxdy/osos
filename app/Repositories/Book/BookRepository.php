<?php

namespace App\Repositories\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class BookRepository
{
    public $book;
    public $author;

    public function __construct(Book $book, Author $author) {
        $this->book = $book;
        $this->author = $author;
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
                ->latest()->get();

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAuthorBooks()
    {
        try {
            if (auth()->user()->hasRole('Admin')) {
                return $this->book->all();
            }

            $author = auth()->user()->author;
            return $author->books;

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

    public function updateBook($bookData)
    {
        try {
            Log::info($bookData);
            $book = $this->book->find($bookData['book_id']);

            $book->isbn = $bookData['isbn'];
            $book->title = $bookData['title'];
            $book->description = $bookData['description'];
            $book->price = $bookData['price'];
            if (!is_null($bookData['cover_image'])) {
                $book->cover_image = $bookData['cover_image'];
            }
            $book->save();

            Log::info($book);

            return $book;

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}