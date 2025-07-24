<?php

namespace App\Http\Services\Auth;

use App\Exceptions\Auth\UnauthorizedException;
use App\Http\Repositories\Users\UsersRepository;
use App\Http\Resources\Auth\AuthTokenResource;
use App\Http\Services\Users\UsersService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param Request $request
     * @return JsonResource
     * @throws UnauthorizedException
     */
    public function login(Request $request): JsonResource
    {
        $user = new UsersRepository()->getByEmail($request->post('email'));

        if (
            is_null($user)
            || !Hash::check($request->post('password'), $user->getAttribute('password'))
        ) {
            throw new UnauthorizedException();
        }
        $token = Auth::login($user);

        return new AuthTokenResource((object)['token' => $token]);
    }

    /**
     * @param Request $request
     * @param UsersService $usersService
     * @return JsonResource
     */
    public function register(
        Request $request,
        UsersService $usersService = new UsersService()
    ): JsonResource {
        $user = $usersService->create($request);
        $token = Auth::login($user);

        return new AuthTokenResource((object)['token' => $token]);
    }

    public function authTokenRefresh(): JsonResource
    {
        return new AuthTokenResource((object)['token' => Auth::refresh()]);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        auth()->logout();
    }
}
