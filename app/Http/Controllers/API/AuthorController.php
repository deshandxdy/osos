<?php

namespace App\Http\Controllers\API;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use App\Repositories\Author\AuthorRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\AuthorStatusRequest;

class AuthorController extends Controller
{
    public $authorRepository;

    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    public function index()
    {
        try {
            $authors = $this->authorRepository->getAllAuthors();

            return response()->json([
                'authors' => new AuthorResource($authors),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeAuthorStatus(AuthorStatusRequest $authorStatusRequest)
    {
        try {
            $data = $authorStatusRequest->validated();

            $author = $this->authorRepository->changeAuthorStatus($data);

            return response()->json([
                'message' => 'Status updated successfully',
                'author' => new AuthorResource($author),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
