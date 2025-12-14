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
        $Data = $request->all();
        $Data['users_id'] = auth('sanctum')->user()->id;

        if ($request->file('education_file')) {
            $Data['education_file'] = FileLibraryController::upload($request->file('education_file'), 'file', 'freelancer/education', 'freelancer');
        }

        if (FreelancerEducation::create($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Update Data
    * Route: /api/my-account/education-information
    * PUT
    * */
    public function update(EducationInformationRequest $request)
    {
        $History = FreelancerEducation::find($request->id);
        $Data = $request->all();

        if ($request->file('education_file')) {
            $Data['education_file'] = FileLibraryController::upload($request->file('education_file'), 'file', 'freelancer/education', 'freelancer');
        }else {
            $Data['education_file'] = $History->education_file;
        }

        if ($History->update($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
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
