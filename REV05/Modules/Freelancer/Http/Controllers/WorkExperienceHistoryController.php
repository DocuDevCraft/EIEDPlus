<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\FreelancerEducation;
use Modules\Freelancer\Entities\FreelancerWorkExperience;
use Modules\Freelancer\Http\Requests\EducationInformationRequest;
use Modules\Freelancer\Http\Requests\WorkExperienceHistoryRequest;

class WorkExperienceHistoryController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/work-experience-history
    * GET
    * */
    public function get()
    {
        $Data = FreelancerWorkExperience::where('users_id', auth('sanctum')->user()->id)->get();

        foreach ($Data as $item) {
            if ($item->work_experience_file) {
                $item['file_name'] = HomeController::GetFileName($item->work_experience_file);
            }
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/work-experience-history
    * POST
    * */
    public function store(WorkExperienceHistoryRequest $request)
    {
        $Data = $request->all();
        $Data['users_id'] = auth('sanctum')->user()->id;

        if ($request->file('work_experience_file')) {
            $Data['work_experience_file'] = FileLibraryController::upload($request->file('work_experience_file'), 'file', 'freelancer/work-experience-history', 'freelancer');
        }

        if (FreelancerWorkExperience::create($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Update Data
    * Route: /api/my-account/work-experience-history
    * PUT
    * */
    public function update(WorkExperienceHistoryRequest $request)
    {


        $History = FreelancerWorkExperience::find($request->id);
        $Data = $request->all();

        if ($request->file('work_experience_file')) {
            $Data['work_experience_file'] = FileLibraryController::upload($request->file('work_experience_file'), 'file', 'freelancer/work-experience-history', 'freelancer');
        }else {
            $Data['work_experience_file'] = $History->work_experience_file;
        }

        if ($History->update($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Delete Data
    * Route: /api/my-account/work-experience-history
    * DELETE
    * */
    public function delete(Request $request)
    {
        $HistoryItem = FreelancerWorkExperience::find($request->id);

        if (auth('sanctum')->user()->id === $HistoryItem->users_id) {
            $HistoryItem->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 401]);
        }
    }
}
