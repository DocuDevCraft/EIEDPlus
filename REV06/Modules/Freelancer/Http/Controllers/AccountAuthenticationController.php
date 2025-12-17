<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Http\Requests\AccountAuthenticationRequest;
use Modules\Freelancer\Http\Requests\IdentityInformailtionRequest;
use Modules\SignatureSystem\Http\Controllers\SignatureSystemController;
use Modules\Users\Entities\Users;

class AccountAuthenticationController extends Controller
{
    /* User Check */
    public function userCheck()
    {
        $UserData = Users::with(['freelancer:users_id,meli_code,cardserialno,birthday,birthday_miladi'])->select('id', 'first_name', 'last_name', 'email', 'phone')->find(auth('sanctum')->user()->id);

        return response()->json($UserData->isProfileComplete);
    }

    public function checkCertificate()
    {
        $UserData = Users::with(['freelancer:id,users_id,meli_code,certpass,auth_verify'])->select('id', 'phone')->find(auth('sanctum')->user()->id);
        $Certificate = SignatureSystemController::signature('getUserCertificateAction', [
            'nationalcode' => $UserData->freelancer->meli_code,
        ]);
        if (isset($Certificate['errorCode']) && $Certificate['errorCode'] === 0 && $UserData->freelancer->certpass && $UserData->freelancer->auth_verify === 1) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function setAuth(AccountAuthenticationRequest $request)
    {
        $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->first();

        $Certificate = SignatureSystemController::signature('getUserCertificateAction', [
            'nationalcode' => $Freelancer->meli_code,
        ]);
        if (isset($Certificate['errorCode']) && $Certificate['errorCode'] === 0) {
            $Freelancer->update(['certpass' => $request->certpass, 'auth_verify' => 1]);
            return response()->json(true);
        } else {
            $Freelancer->update(['certpass' => null, 'auth_verify' => 0]);
            return response()->json(false);
        }
    }

    /* Check Auth [1005] */
//    public function AuthCheck()
//    {
//        $UserData = Users::with(['freelancer:users_id,meli_code'])->select('id', 'phone')->find(auth('sanctum')->user()->id);
//
//        return SignatureSystemController::signature('signature_request', [
//            'nationalcode' => $UserData->freelancer->meli_code,
//            'mobile' => $UserData->phone
//        ]);
//    }
//
//    /* Create Account */
//    public function createAccount(AccountAuthenticationRequest $request)
//    {
//        $UserData = Users::with(['freelancer:users_id,meli_code,cardserialno,birthday,birthday_miladi'])->select('id', 'first_name', 'last_name', 'email', 'phone')->find(auth('sanctum')->user()->id);
//        $UserData->freelancer->update(['pending_certpass' => $request->certpass]);
//        $Auth =  SignatureSystemController::signature('signature_auth', [
//            "nationalcode" => $UserData->freelancer->meli_code,
//            'mobile' => $UserData->phone,
//            "cardserialno" => $UserData->freelancer->cardserialno,
//            "certpass" => $request->certpass,
//            "birthdate" => $UserData->freelancer->birthday_miladi,
//            "birthdateshamsi" => $UserData->freelancer->birthday,
//            "postalcode" => $UserData->freelancer->postal_code,
//            "callbackurl" => 'http://localhost:8000/callback-auth?errorcode=@errorcode&errormessage=@errormessage&id=' . $UserData->freelancer->id,
//            "otp" => $request->otp,
//        ]);
//
//        if ($Auth['errorCode'] == 0){
//            return $Auth;
//        }else {
//            return response()->json($Auth);
//        }
//    }
}
