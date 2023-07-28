<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class AuthRepository
{
    public $user;
    public $author;

    public function __construct(User $user, Author $author)
    {
        $this->user = $user;
        $this->author = $author;
    }

    public function userRegister($userData)
    {
        try {
            DB::beginTransaction();

            $this->user->name = $userData['first_name'] . ' ' . $userData['last_name'];
            $this->user->email = $userData['email'];
            $this->user->password =  bcrypt($userData['password']);

            $this->user->save();

            $this->user->syncRoles(['author']);

            $user_id = $this->user->id;

            $this->author = new Author();
            $this->author->first_name = $userData['first_name'];
            $this->author->last_name = $userData['last_name'];
            $this->author->user_id = $user_id;
            $this->author->status = 1;

            $this->author->save();

            $token = $this->user->createToken('api-auth-token')->accessToken;

            DB::commit();

            return compact('user', 'author', 'token');

        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}