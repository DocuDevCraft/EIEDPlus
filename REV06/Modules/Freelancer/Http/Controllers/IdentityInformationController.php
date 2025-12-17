<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
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

        $data = collect($request->validated())
            ->map(fn($v) => ($v === '' || $v === 'null') ? null : $v)
            ->toArray();

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

        Users::where('id', $userId)->update($data);
        Freelancer::where('users_id', $userId)->update($data);

        return response()->json(['status' => 200]);
    }

    public function additional_information(AdditionalInformationRequest $request)
    {
        $userId = auth('sanctum')->id();

        $user = Users::findOrFail($userId);
        $freelancer = Freelancer::where('users_id', $userId)->firstOrFail();

        $data = json_decode($request->data, true);

        if ($request->hasFile('sarbazi_file')) {
            $data['sarbazi_file'] = FileLibraryController::upload(
                $request->file('sarbazi_file'),
                'file',
                'freelancer/sarbazi',
                'freelancer'
            );
        }

        $user->update($data);
        $freelancer->update($data);

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }
}
