<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function authenticate(LoginRequest $request)
    {
        return $this->authService->login($request->validated(), $request);
    }

    public function store(RegisterRequest $request)
    {
        return $this->authService->register($request->validated(), $request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}
