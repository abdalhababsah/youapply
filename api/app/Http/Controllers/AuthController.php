<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\VerifySmsRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();


        $smsCode = 123;

        $user = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
            'sms_code' => $smsCode,
        ]);

        $this->sendSms($user->phone, $user->sms_code);

        return response()->json([
            'message' => 'User registered successfully! Please verify your phone number.',
            'phone' => $validatedData['phone'],
            'sms_code' => $smsCode, // For testing purposes;
        ], 201);
    }

    public function verifySmsCode(VerifySmsRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->sms_code == $request->sms_code) {
            $user->update([
                'sms_code' => null,
                'phone_verified_at' => now(),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Phone number verified successfully.',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } else {
            return response()->json(['message' => 'Invalid verification code.'], 422);
        }
    }


    protected function sendSms($to, $code)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = env('TWILIO_FROM');

        try {
            $client = new Client($sid, $token);

            $message = $client->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => "Your verification code is: $code"
                ]
            );

            return true;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
            \Log::error('SMS Sending Error: ' . $e->getMessage());
            return false;
        }
    }

    public function login(LoginUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::where('phone', $validatedData['phone'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (empty($user->phone_verified_at)) {
            return response()->json(['message' => 'Your phone number is not verified. Please verify your phone number to log in.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
}



}
