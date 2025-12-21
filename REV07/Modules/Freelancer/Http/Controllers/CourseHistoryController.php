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
        $data = $request->validated();
        $data['users_id'] = auth('sanctum')->user()->id;

        if ($request->hasFile('course_file')) {
            $data['course_file'] = FileLibraryController::upload(
                $request->file('course_file'),
                'file',
                'freelancer/course-history',
                'freelancer'
            );
        }

        $course = FreelancerCourses::create($data);

        return response()->json([
            'status' => $course ? 200 : 401,
            'data' => $course
        ]);
    }

    /*
    * Update Data
    * Route: /api/my-account/course-history
    * PUT
    * */
    public function update(CourseHistoryRequest $request)
    {
        $history = FreelancerCourses::findOrFail($request->id);
        $data = $request->validated();

        if ($request->hasFile('course_file')) {
            $data['course_file'] = FileLibraryController::upload(
                $request->file('course_file'),
                'file',
                'freelancer/course-history',
                'freelancer'
            );
        }

        $history->update($data);

        return response()->json([
            'status' => 200,
            'data' => $history
        ]);
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
