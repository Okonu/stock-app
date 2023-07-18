<?php

namespace App\Http\Controllers;

use App\Models\Seeker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Services\SendSms; 

class PasswordResetController extends Controller
{
    public function requestReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $phone = $request->input('phone');
        $seeker = Seeker::where('phone', $phone)->first();

        if (!$seeker) {
            return response()->json(['error' => 'Seeker not found'], 404);
        }

        $otp = $this->generateOTP();
        $this->sendOTP($seeker->phone, $otp);

        $seeker->otp = $otp;
        $seeker->save();

        return response()->json(['message' => 'OTP sent successfully', $otp]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|phone',
            'otp' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $phone = $request->input('phone');
        $otp = $request->input('otp');
        $password = $request->input('password');

        $seeker = Seeker::where('phone', $phone)->first();

        if (!$seeker) {
            return response()->json(['error' => 'Seeker not found'], 404);
        }

        if ($seeker->otp !== $otp) {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }

        $seeker->password = Hash::make($password);
        $seeker->otp = null; 
        $seeker->save();

        return response()->json(['message' => 'Password reset successful']);
    }

    private function generateOTP(): string
    {
        $otp = strval(rand(100000, 999999));
        
        $cacheKey = 'password_reset_otp_' . $otp;
        $expiresInMinutes = 5; 
        Cache::put($cacheKey, true, $expiresInMinutes);
        
        return $otp;
    }

    private function sendOTP($phoneNumber, $otp): void
    {
        $formattedNumber = '';

        if (strpos($phoneNumber, '254') === 0) {

            $formattedNumber = $phoneNumber;
        } elseif (strpos($phoneNumber, '07') === 0) {

            $formattedNumber = '254' . substr($phoneNumber, 1);
        } elseif (strpos($phoneNumber, '01') === 0) {

            $formattedNumber = '254' . substr($phoneNumber, 1);
        }

        if (!empty($formattedNumber)) {
            $message = "Your OTP for password reset is: " . $otp;

            $smsService = new SendSms();
            $smsService->sendSms($formattedNumber, $message);
        } else {

            throw new \InvalidArgumentException('Invalid phone number format');
        }
    }

}
