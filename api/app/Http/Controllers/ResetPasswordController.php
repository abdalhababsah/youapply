<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ResetPasswordController extends Controller
{
    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);

        $user = User::where('phone', $request->phone)->firstOrFail();

        $smsCode = 123;
        $user->sms_code = $smsCode;
        $user->save();

        $this->sendSms($user->phone, "Your password reset code is: $smsCode");

        return response()->json(['message' =>  "Your password reset code is: $smsCode"]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'sms_code' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('phone', $request->phone)->where('sms_code', $request->sms_code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid phone number or SMS code.'], 422);
        }


        $user->password = Hash::make($request->password);
        $user->sms_code = null;
        $user->save();

        return response()->json(['message' => 'Password has been reset successfully.']);
    }
    protected function sendSms($to, $message)
    {

    }

}
