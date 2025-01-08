<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
    */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
        ]);
        
        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()],422);
        }

        try {
            if (Auth::attempt($validated->validated())) {
                $user = Auth::user();
                $token = $user->createToken('')->plainTextToken;
    
                return response()->json([
                    'message' => 'Login successfully',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
        } catch (AuthenticationException $e) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    /**
     * Destroy an authenticated session.
    */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
