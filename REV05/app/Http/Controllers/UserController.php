<?php
//
namespace App\Http\Controllers;

use App\Models\MobileVerifyCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Notification\Http\Controllers\NotificationController;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\Users\Entities\Users;

class UserController extends Controller
{
    /* Logout */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        \Cookie::queue(\Cookie::forget('userData'));
        \Cookie::queue(\Cookie::forget('eied_session'));
        \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
        \Cookie::queue(\Cookie::forget('auth_token'));

        return response()->json([
            'status' => 200,
        ]);
    }

    /* Login */
    public function login(Request $request)
    {
        \Cookie::queue(\Cookie::forget('userData'));
        \Cookie::queue(\Cookie::forget('eied_session'));
        \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
        \Cookie::queue(\Cookie::forget('auth_token'));

        if (!auth('sanctum')->check()) {
            $UserCheck = Users::where('phone', $request->phone)->count();

            if (!$UserCheck) {
                $VerifyCode = rand(10000, 99999);
                if (SmsHandlerController::Send([$request->phone], "کد تایید  $VerifyCode")) {
                    $Data = [
                        'mobile_number' => $request->phone,
                        'code' => $VerifyCode
                    ];
                    MobileVerifyCode::where('mobile_number', $request->phone)->delete();
                    MobileVerifyCode::create($Data);
                    return response()->json(['status' => 'register']);
                } else {
                    return response()->json(['status' => 'sms_error']);
                }
            } else {
                return response()->json(['status' => 'login']);
            }
        } else {
            auth('sanctum')->user()->tokens()->delete();
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
            \Cookie::queue(\Cookie::forget('userData'));
            \Cookie::queue(\Cookie::forget('eied_session'));
            \Cookie::queue(\Cookie::forget('XSRF-TOKEN'));
            \Cookie::queue(\Cookie::forget('auth_token'));
            return response()->json(['status' => 'login']);
        }
    }

    /* Login */
    public function loginVerify(Request $request)
    {
        if (!auth('sanctum')->check()) {
            $UserCheck = Users::where('phone', $request->phone)->count();

            if ($UserCheck) {
                $ValidData = $request->validate([
                    'phone' => 'required|exists:users',
                    'password' => 'required'
                ], [], [
                    'phone' => 'شماره موبایل',
                    'password' => 'کلمه عبور'
                ]);
                if (!Auth::attempt($ValidData)) {
                    return response()->json([
                        'status' => 401,
                        'message' => 'اعتبار نامعتبر'
                    ]);
                } else {
                    auth()->user()->tokens()->delete();
                    $token = auth()->user()->createToken('EIED app browser')->plainTextToken;

                    return response()->json(
                        [
                            'status' => 200,
                            'token' => $token,
                        ]
                    );
                }
            } else {
                return response()->json(['status' => 'Not Found!']);
            }
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /* Verify Code */
    public function verify(Request $request)
    {
        if (MobileVerifyCode::where('mobile_number', $request->phone)->where('code', $request->verifyCode)->count()) {
            return response()->json(['status' => 'ok']);
        } else {
            return response()->json(['status' => 'nok']);
        }
    }

    /* Register */
    public function register(Request $request)
    {
        $User = Users::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'role' => 'freelancer',
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        Freelancer::create(['users_id' => $User->id]);
        NotificationController::store('حساب کاربری شما ایجاد شد', 'لطفا جهت تایید و ادامه فعالیت، حساب کاربری خود را تکمیل نمایید.', $User->id);

        event(new Registered($User));
        Auth::loginUsingId($User->id);
        $token = auth()->user()->createToken('API for app browser')->plainTextToken;

        return response()->json(
            [
                'action' => 'register_done',
                'token' => $token,
            ]
        );
    }

    /* get User Data */
    public function userData()
    {
        if (auth('sanctum')->check()) {
            $User = Users::with(['freelancer:users_id,auth_verify,accept_rules,hourly_contract'])->select('id', 'first_name', 'last_name', 'role', 'avatar')->find(auth('sanctum')->user()->id);

            if ($User->avatar) {
                $User['avatar'] = HomeController::GetAvatar('54', '108', $User->avatar);
            }

            return response()->json(['status' => 200, 'userData' => $User]);
        } else {
            return response()->json(['status' => 401]);
        }
    }
}
