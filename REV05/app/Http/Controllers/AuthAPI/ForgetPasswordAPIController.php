<?php

namespace App\Http\Controllers\AuthAPI;

use App\Http\Controllers\Controller;
use App\Models\MobileVerifyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\Users\Entities\Users;

class ForgetPasswordAPIController extends Controller
{
    public function forgetPassword(Request $request)
    {
        if (!auth('sanctum')->check()) {
            $UserCheck = Users::where('phone', $request->phone)->count();

            if ($UserCheck) {
                $VerifyCode = rand(10000, 99999);
                if (SmsHandlerController::Send([$request->phone], "کد تایید  $VerifyCode")) {
                    $Data = [
                        'mobile_number' => $request->phone,
                        'code' => $VerifyCode
                    ];
                    MobileVerifyCode::where('mobile_number', $request->phone)->delete();
                    MobileVerifyCode::create($Data);
                    return response()->json(['status' => 'reset-password', 'message' => 'success']);
                } else {
                    return response()->json(['status' => 'sms_error']);
                }
            } else {
                return response()->json(['status' => 'Not Found!']);
            }
        } else {
            return response()->json(['status' => 401]);
        }
    }

    public function resetPassword(Request $request)
    {
        if (!auth('sanctum')->check()) {
            $UserCheck = Users::where('phone', $request->phone)->count();
//
            if ($UserCheck) {
                $ValidData = $request->validate([
                    'password' => 'required|confirmed|min:6',
                ], [], [
                    'password' => 'کلمه عبور',
                ]);

                if (Users::where('phone', $request->phone)->first()->update(['password' => Hash::make($request->password)])) {
                    return response()->json(['status' => 'login', 'message' => 'passwordRested']);
                }
            } else {
                return response()->json(['status' => 'Not Found!']);
            }
        } else {
            return response()->json(['status' => 401]);
        }
    }

}
