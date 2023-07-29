<?php
namespace App\Repositories\Author;

use App\Models\Author;

class AuthorRepository
{
    public $author;

    public function __construct(Author $author) {
        $this->author = $author;
    }

    public function getAllAuthors()
    {
        try {
            return $this->author->with('user', 'books')->paginate();

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeAuthorStatus($data)
    {
        try {
            $author = $this->author->findOrFail($data['author_id']);
            $author->status = $data['status'];
            $author->save();

            return $author;

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}