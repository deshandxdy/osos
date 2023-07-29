<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\Author;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public $user;
    public $author;

    public function __construct(User $user, Author $author)
    {
        $this->user = $user;
        $this->author = $author;
    }

    public function userRegister($userData): Array
    {
        try {
            DB::beginTransaction();

            //create user
            $this->user->first_name = $userData['first_name'];
            $this->user->last_name = $userData['last_name'];
            $this->user->email = $userData['email'];
            $this->user->password =  bcrypt($userData['password']);

            $this->user->save();

            //assign author role to the user
            $this->user->syncRoles(['Author']);

            //create author
            $this->author = new Author();
            $this->author->first_name = $userData['first_name'];
            $this->author->last_name = $userData['last_name'];
            $this->author->user_id = $this->user->id;
            $this->author->status = true;

            $this->author->save();

            //create passport token
            $token = $this->user->createToken('api-auth-token')->accessToken;

            DB::commit();

            return [
                'user' => $this->user,
                'author' => $this->author,
                'token' => $token,
            ];

        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userLogin($userData)
    {
        try {
            $user = $this->user->where('email', $userData['email'])->first();
            if ($user) {
                if (Hash::check($userData['password'], $user->password)) {
                    $token = $user->createToken('api-auth-token')->accessToken;
                    $author = $user->author;

                    return [
                        'user' => $user,
                        'author' => $author,
                        'token' => $token,
                        'success' => true
                    ];

                } else {
                    return [
                        'message' => 'Password mismatch',
                        'success' => false
                    ];
                }
            } else {
                return [
                    'message' => 'User does not exist',
                    'success' => false
                ];
                return 'User does not exist';
            }

            return false;

        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}