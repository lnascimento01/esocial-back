<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function __construct(
        private AuthenticationService $autheticationService
    ) {
    }
    /**
     * Registrar o usuário
     * @param Request $request
     * @return User
     */
    public function register(Request $request): JsonResponse
    {
        $auth = $this->autheticationService->register($request);

        return response()->json($auth, 200);
    }

    /**
     * Login do usuário
     * @param Request $request
     * @return User
     */
    public function login(Request $request): JsonResponse
    {
        $auth = $this->autheticationService->login($request);

        return response()->json($auth, 200);
    }
}
