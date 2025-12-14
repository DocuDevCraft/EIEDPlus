<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Http\Requests\AdditionalInformationRequest;
use Modules\Freelancer\Http\Requests\IdentityInformailtionRequest;
use Modules\Users\Entities\Users;

class IdentityInformationController extends Controller
{
    /* Get Data */
    public function get()
    {
        $Data = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')->select('first_name', 'last_name', 'email', 'phone', 'avatar', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.sarbazi', 'freelancer.sarbazi_file', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.shenasnameh', 'freelancer.mahale_sodoor', 'freelancer.country', 'freelancer.home_phone', 'freelancer.postal_code', 'freelancer.address', 'freelancer.linkedin', 'freelancer.website', 'freelancer.biography')->find(auth('sanctum')->user()->id);

        if ($Data->avatar) {
            $Data['avatarPreview'] = HomeController::GetAvatar('93', '186', $Data->avatar);
        }

        if ($Data->sarbazi_file) {
            $Data['sarbaziFileName'] = HomeController::GetFileName($Data->sarbazi_file);
        }

        unset($Data['sarbazi_file']);
        unset($Data['avatar']);

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /* Store Data */
    public function identity(IdentityInformailtionRequest $request)
    {
        $userId = auth('sanctum')->id();

        $data = collect($request->all())->map(function ($value) {
            return ($value === '' || $value === 'null') ? null : $value;
        })->toArray();

        if ($request->hasFile('avatar_file')) {
            $data['avatar'] = FileLibraryController::upload(
                $request->file('avatar_file'),
                'image',
                'users/avatar',
                'users',
                [
                    [54, 54, 'fit'],
                    [108, 108, 'fit'],
                    [93, 93, 'fit'],
                    [186, 186, 'fit']
                ]
            );
        }

        $User = Users::findOrFail($userId);
        $Freelancer = Freelancer::where('users_id', $userId)->firstOrFail();

        $User->update($data);
        $Freelancer->update($data);

        return response()->json([$User, $Freelancer]);
    }

    public function additional_information(AdditionalInformationRequest $request)
    {
        $User = Users::find(auth('sanctum')->user()->id);
        $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->first();
        $Data = json_decode($request->data, true);

        if ($request->file('sarbazi_file')) {
            $Data['sarbazi_file'] = FileLibraryController::upload($request->file('sarbazi_file'), 'file', 'freelancer/sarbazi', 'freelancer');
        }

        $User->update($Data);
        $Freelancer->update($Data);

        return response()->json($request->all());
    }
}
