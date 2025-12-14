<?php

namespace Modules\Freelancer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\SectionManager\Entities\Division;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;
use Modules\SectionManager\Http\Controllers\SectionAPIHandlerController;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;

class SpecialityController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/speciality
    * GET
    * */
    public function get()
    {
        $Data = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->get();

        if ($Data) {
            foreach ($Data as $item) {
                $item['section_id'] = SectionAPIHandlerController::getName('section', $item->section_id);
                if ($item->subsection_id) {
                    $item['subsection_id'] = SectionAPIHandlerController::getName('subsection', $item->subsection_id);
                }
                if ($item->division_id) {
                    $item['division_id'] = SectionAPIHandlerController::getName('division', $item->division_id);
                }
            }
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/speciality
    * POST
    * */
    public function store(Request $request)
    {
        $Data = $request->all();
        $Data['users_id'] = auth('sanctum')->user()->id;

        $FreelancerSection = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->where('section_id', $request->section_id)->where('subsection_id', $request->subsection_id)->where('division_id', $request->division_id)->count();

        if ($FreelancerSection) {
            return response()->json(['status' => 'exist']);
        } else {
            if (FreelancerSection::create($Data)) {
                /* Send Notification */
                if ($request->division_id) {
                    $Division = Division::find($request->division_id);
                    foreach ($Division->Appraiser as $item) {
                        SmsHandlerController::Send(["$item->phone"], "سلام، یک فریلنسر در انتظار ارزیابی شما می باشد.");
                    }
                } elseif ($request->subsection_id) {
                    $SubSection = Subsection::find($request->subsection_id);
                    foreach ($SubSection->Appraiser as $item) {
                        SmsHandlerController::Send(["$item->phone"], "سلام، یک فریلنسر در انتظار ارزیابی شما می باشد.");
                    }
                } else {
                    $Section = Section::find($request->section_id);
                    foreach ($Section->ChiefAppraiser as $item) {
                        SmsHandlerController::Send(["$item->phone"], "سلام، یک فریلنسر در انتظار ارزیابی شما می باشد.");
                    }
                }

                return response()->json(['status' => 200, 'data' => $request->all()]);
            } else {
                return response()->json(['status' => 401]);
            }
        }
    }

    /*
* Delete Data
* Route: /api/my-account/language-history
* DELETE
* */
    public function delete(Request $request)
    {
        $HistoryItem = FreelancerSection::find($request->id);

        if (auth('sanctum')->user()->id === $HistoryItem->users_id) {
            $HistoryItem->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    public function getList($count)
    {
        $Specialty = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->where('final_grade', '!=', 'null')->get();

        if ($Specialty) {
            foreach ($Specialty as $item) {
                $item['section_id'] = SectionAPIHandlerController::getName('section', $item->section_id);
                if ($item->subsection_id) {
                    $item['subsection_id'] = SectionAPIHandlerController::getName('subsection', $item->subsection_id);
                }
                if ($item->division_id) {
                    $item['division_id'] = SectionAPIHandlerController::getName('division', $item->division_id);
                }
            }
        }

        return response()->json(['getData' => $Specialty]);
    }
}
