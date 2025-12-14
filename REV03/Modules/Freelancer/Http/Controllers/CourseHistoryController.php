<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\FreelancerCourses;
use Modules\Freelancer\Http\Requests\CourseHistoryRequest;

class CourseHistoryController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/course-history
    * GET
    * */
    public function get()
    {
        $Data = FreelancerCourses::where('users_id', auth('sanctum')->user()->id)->get();

        foreach ($Data as $item) {
            if ($item->course_file) {
                $item['file_name'] = HomeController::GetFileName($item->course_file);
            }
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/course-history
    * POST
    * */
    public function store(CourseHistoryRequest $request)
    {
        $Data = $request->all();
        $Data['users_id'] = auth('sanctum')->user()->id;

        if ($request->file('course_file')) {
            $Data['course_file'] = FileLibraryController::upload($request->file('course_file'), 'file', 'freelancer/course-history', 'freelancer');
        }

        if (FreelancerCourses::create($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Update Data
    * Route: /api/my-account/course-history
    * PUT
    * */
    public function update(CourseHistoryRequest $request)
    {


        $History = FreelancerCourses::find($request->id);
        $Data = $request->all();

        if ($request->file('course_file')) {
            $Data['course_file'] = FileLibraryController::upload($request->file('course_file'), 'file', 'freelancer/course-history', 'freelancer');
        } else {
            $Data['course_file'] = $History->course_file;
        }

        if ($History->update($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Delete Data
    * Route: /api/my-account/course-history
    * DELETE
    * */
    public function delete(Request $request)
    {
        $HistoryItem = FreelancerCourses::find($request->id);

        if (auth('sanctum')->user()->id === $HistoryItem->users_id) {
            $HistoryItem->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 401]);
        }
    }
}
