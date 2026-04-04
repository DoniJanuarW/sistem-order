<?php

namespace App\Services;

use App\Helpers\ResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class AuthService
{
	public function login(array $data, Request $request): JsonResponse
	{
		try {
			if (Auth::attempt($data)) {
				$request->session()->regenerate();

				$user = Auth::user();

				return ResponseFormatter::redirected(
					'Login successful!',
					route("dashboard")
				);
			}

			return ResponseFormatter::error(
				'Credentials not match our records.',
				code: Response::HTTP_UNAUTHORIZED
			);
		} catch (\Exception $e) {
			return ResponseFormatter::handleError($e);
		}
	}

	  /**
     * Handle registration logic
     */
	  public function register(array $data, $request): JsonResponse
	  {
	  	DB::beginTransaction();

	  	try {
	  		$user = User::create([
	  			'name'      => $data['name'],      
	  			'full_name' => $data['full_name'],
	  			'email'     => $data['email'],
	  			'phone'     => $data['phone'],
	  			'password'  => Hash::make($data['password']),
	  			'role'      => 'customer'
	  		]);


            // Auto Login
            // User langsung masuk tanpa harus input email/pass lagi
	  		Auth::login($user);

            // Regenerate Session
	  		$request->session()->regenerate();

	  		DB::commit();

            // Return JSON Response
	  		return ResponseFormatter::redirected(
	  			'Registrasi berhasil! Selamat datang.',
	  			route("dashboard")
	  		);

	  	} catch (Exception $e) {
	  		DB::rollBack();

	  		Log::error($e->getMessage());

	  		return ResponseFormatter::error(
	  			'Terjadi kesalahan saat mendaftar'
	  		);
	  	}
	  }

	  public function logout(Request $request)
	  {
	  	try {
	  		Auth::logout();
	  		$request->session()->invalidate();
	  		$request->session()->regenerateToken();

	  		return ResponseFormatter::redirected('Logout successful, you will be redirected to login page.', route('login'));
	  	} catch (\Exception $e) {
	  		return ResponseFormatter::handleError($e);
	  	}
	  }
	}