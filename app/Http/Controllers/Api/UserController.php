<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display the specified resource.
    */
    public function show(User $user){
        try {
            $user = Auth::user();
            return response()->json([
                'message' => 'Auth Detail',
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Auth not found',
            ], 404);
        }
    }

    public function sendResetLink(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email', 
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $status = Password::sendResetLink($request->only('email'));
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)], 200);
            }
            return response()->json(['message' => __($status)], 400);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
    */
    public function update(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
            
            if ($validated->fails()) {
                return response()->json([
                    'errors' => $validated->errors() 
                ],422);
            }
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->update();

            return response()->json([
               'message' => 'profile updated successfully!',
                'user' => $user 
            ],200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong', 
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy(User $user)
    {
        //
    }
}
