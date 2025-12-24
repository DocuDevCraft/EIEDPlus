<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\FreelancerEducation;
use Modules\Freelancer\Http\Requests\EducationInformationRequest;

class EducationInformationController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/education-information
    * GET
    * */
    public function get()
    {
        $Data = FreelancerEducation::where('users_id', auth('sanctum')->user()->id)->get();

        foreach ($Data as $item) {
            if ($item->education_file) {
                $item['education_file_name'] = HomeController::GetFileName($item->education_file);
            }
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/education-information
    * POST
    * */
    public function store(EducationInformationRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('education_file')) {
            $data['education_file'] = FileLibraryController::upload(
                $request->file('education_file'),
                'file',
                'freelancer/education',
                'freelancer'
            );
        }

        $education = FreelancerEducation::create($data);

        // users_id چون fillable نیست
        $education->forceFill([
            'users_id' => auth('sanctum')->user()->id,
        ])->save();

        return response()->json([
            'status' => $education ? 200 : 401,
            'data' => $education
        ]);
    }

    /*
    * Update Data
    * Route: /api/my-account/education-information
    * PUT
    * */
    public function update(EducationInformationRequest $request)
    {
        $education = FreelancerEducation::findOrFail($request->id);
        $data = $request->validated();

        if ($request->hasFile('education_file')) {
            $data['education_file'] = FileLibraryController::upload(
                $request->file('education_file'),
                'file',
                'freelancer/education',
                'freelancer'
            );
        }

        $education->update($data);

        return response()->json([
            'status' => 200,
            'data' => $education
        ]);
    }

    /*
    * Delete Data
    * Route: /api/my-account/education-information
    * DELETE
    * */
    public function delete(Request $request)
    {
        $HistoryItem = FreelancerEducation::find($request->id);

        if (auth('sanctum')->user()->id === $HistoryItem->users_id) {
            $HistoryItem->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 401]);
        }
    }
}
