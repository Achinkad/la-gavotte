<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {
        $new_user = DB::transaction(function () use ($request) : User {
            $user = new User;
            $user->fill($request->validated());
            $password_hashed = Hash::make($request->input('password'));
            $user->password = $password_hashed;
            $user->save();
            return $user;
        });
        return new UserResource($new_user);
    }

    public function showUserLoggedIn(Request $request)
    {
        return new UserResource($request->user());
    }
}
