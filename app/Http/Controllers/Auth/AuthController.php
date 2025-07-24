<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\JsonErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @param AuthService $authService
     * @return JsonResource
     */
    public function register(
        RegisterRequest $request,
        AuthService $authService
    ): JsonResource {
        return $authService->register($request);
    }

    /**
     * @param LoginRequest $request
     * @param AuthService $authService
     * @return JsonResource
     * @throws JsonErrorException
     */
    public function login(
        LoginRequest $request,
        AuthService $authService
    ): JsonResource {
        return$authService->login($request);
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }

    /**
     * @param AuthService $authService
     * @return JsonResource
     */
    public function refresh(
        AuthService $authService
    ): JsonResource {
        return $authService->authTokenRefresh();
    }

    /**
     * @param AuthService $authService
     * @return void
     */
    public function logout(
        AuthService $authService
    ): void {
        $authService->logout();
    }
}
