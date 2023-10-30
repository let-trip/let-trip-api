<?php

namespace App\Http\Controllers;

use App\Mail\OTPEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function verifyOtp(Request $request)
    {
        $user = User::where('email', $request->user()->email)->first();

        if ($user && $user->otp == $request->otp) {
            $user->markEmailAsVerified();
            return redirect('/welcome'); // Redirect to the home page upon successful verification
        }

        return back()->with('error', 'Invalid OTP');
    }

    /**
     * Create User
     * @param Request $request
     * @return User|\Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            $otp = rand(1000, 9999); // Generate a random 4-digit OTP
            // Validate user input
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => $otp, // Store the OTP in the database
            ]);

            $token = $user->createToken("API TOKEN")->plainTextToken;

            // Save the user to the database to get an ID
            $user->save();

            // Set the remember_token attribute to the generated token
            $user->remember_token = $token;

            // Save the user again to update the remember_token
            $user->save();
            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'User Created Successfully',
                'token' => $user->remember_token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User|\Illuminate\Http\JsonResponse
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->remember_token,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //login with token
    public function loginWithToken(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'token' => 'required'
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('remember_token', $request->token)->first();

            if(!$user){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Token',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->remember_token,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
