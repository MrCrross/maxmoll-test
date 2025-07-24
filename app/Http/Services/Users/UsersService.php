<?php

namespace App\Http\Services\Users;

use App\Http\Repositories\Users\UsersRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersService
{
    public function __construct(
        private UsersRepository $usersRepository = new UsersRepository(),
    )
    {
    }

    public function create(Request $request): User
    {
        return $this->usersRepository->create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password'))
        ]);
    }
}
