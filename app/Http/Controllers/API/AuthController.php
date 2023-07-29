<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\AuthorResource;
use App\Repositories\Auth\AuthRepository;
use App\Http\Requests\API\RegisterRequest;

class AuthController extends Controller
{
    public $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request) {
        try {
            $userData = $request->validated();

            $result = $this->authRepository->userRegister($userData);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => new UserResource($result['user']),
                'author' => new AuthorResource($result['author']),
                'token' => $result['token'],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request) {
        try {
            $userData = $request->validated();

            $result = $this->authRepository->userLogin($userData);

            if ($result['success']) {
                return response()->json([
                    'message' => 'User logged in successfully',
                    'user' => new UserResource($result['user']),
                    'author' => new AuthorResource($result['author']),
                    'token' => $result['token'],
                ], 200);
            }

            return response()->json([
                $result
            ], 402);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }
}
