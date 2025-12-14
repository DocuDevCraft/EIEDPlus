<?php

namespace Modules\FreelancerGrade\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerCourses;
use Modules\Freelancer\Entities\FreelancerEducation;
use Modules\Freelancer\Entities\FreelancerLanguage;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\Freelancer\Entities\FreelancerWorkExperience;
use Modules\FreelancerGrade\Entities\FreelancerGradeChat;
use Modules\FreelancerGrade\Entities\FreelancerGradeLog;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;

class FreelancerGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $FreelancerGrade = [];


        if (Gate::allows('isChiefAppraiser')) {
            $OwnerType = 'section_id';
            $Owner = Section::with('ChiefAppraiser')->whereHas('ChiefAppraiser', function ($query) {
                $query->where('users_id', '=', auth()->user()->id);
            })->pluck('id')->toArray();
        } elseif (Gate::allows('isAppraiser')) {
            $OwnerType = 'subsection_id';
            $Owner = Subsection::with('Appraiser')->whereHas('Appraiser', function ($query) {
                $query->where('users_id', '=', auth()->user()->id);
            })->pluck('id')->toArray();
        } else {
            $OwnerType = '';
            $Owner = null;
        }


        $FreelancerGrade = FreelancerSection::select('freelancer_section.*', 'users.created_at')
            ->where(function ($query) use ($OwnerType, $Owner) {
                if ($Owner) {
                    $query->whereIn($OwnerType, $Owner ?: []);
                }
            })
            ->join('users', 'freelancer_section.users_id', '=', 'users.id')
            ->where(function ($query) use ($request) {
                $query->where('users.first_name', 'like', "%{$request->search}%");
                $query->orWhere('users.last_name', 'like', "%{$request->search}%");
                $query->orWhere('users.email', 'like', "%{$request->search}%");
            })
            ->orderBy('grade', 'asc')
            ->paginate(20);

        $Data = [
            'FreelancerGrade'
        ];

        return view('freelancergrade::index', compact($Data));
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Gate::allows('isChiefAppraiser') || Gate::allows('isAppraiser')) {
            $FreelancerGrade = FreelancerSection::find($id);
            $Freelancer = Freelancer::where('users_id', $FreelancerGrade->users_id)->first();
            $FreelancerEducation = FreelancerEducation::where('users_id', $FreelancerGrade->users_id)->get();
            $FreelancerWorkExperience = FreelancerWorkExperience::where('users_id', $FreelancerGrade->users_id)->get();
            $FreelancerCourses = FreelancerCourses::where('users_id', $FreelancerGrade->users_id)->get();
            $FreelancerLanguage = FreelancerLanguage::where('users_id', $FreelancerGrade->users_id)->get();
            $chatList = FreelancerGradeChat::where('freelancer_section_id', $id)->get();
            $FreelancerGradeLog = FreelancerGradeLog::where('freelancer_section_id', $id)->get();
            $userRole = auth()->user()->role;
            $WorkPackageFreelancerGradeMessage = '';

            if (in_array($userRole, ['chiefAppraiser', 'admin'])) {
                $OldGrades = json_decode($FreelancerGrade->grade_data ?? '{}', true);
                $OldGradesMessage = json_decode($FreelancerGrade->grade_message ?? '{}', true);
            } elseif ($userRole === 'appraiser') {
                $OldGrades = json_decode($FreelancerGrade->suggest_grade_data ?? '{}', true);
                $OldGradesMessage = json_decode($FreelancerGrade->suggest_grade_message ?? '{}', true);
            }

            $Labels = [
                1 => 'کیفیت کار',
                2 => 'مدیریت وظائف و مسئولیت‌ها',
                3 => 'ارتباطات و ارائه بازخورد',
                4 => 'دانش کاربردی و موضوعات مرتبط',
                5 => 'چابکی و پویایی یادگیری',
                6 => 'توانایی تحلیل و حل مسئله',
                7 => 'همسویی با ارزش‌ها',
                8 => 'همکاری',
                9 => 'خدمت به مشتری/همکاران واحدهای دیگر',
                10 => 'خلاقیت و نوآوری',
            ];

            $Data = [
                'FreelancerGrade',
                'Freelancer',
                'FreelancerEducation',
                'FreelancerWorkExperience',
                'FreelancerCourses',
                'FreelancerLanguage',
                'chatList',
                'FreelancerGradeLog',
                'Labels',
                'OldGrades',
                'OldGradesMessage',
            ];
            return view('freelancergrade::edit', compact($Data));
        } else {
            return redirect('/dashboard/no-permissions');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $grades = $request->input('grade_type', []);
        $gradesMessage = $request->input('grade_message_text', []);

        $weights = [
            1 => 1,
            2 => 0.8,
            3 => 0.9,
            4 => 1,
            5 => 0.8,
            6 => 0.9,
            7 => 1,
            8 => 1,
            9 => 0.7,
            10 => 1
        ];

        $totalWeighted = 0;
        $totalWeights = 0;

        foreach ($grades as $key => $value) {
            $value = floatval($value);
            $weight = $weights[$key] ?? 1;

            $totalWeighted += $value * $weight;
            $totalWeights += $weight;
        }

        $average = $totalWeights > 0 ? round($totalWeighted / $totalWeights, 2) : 0;
        $gradesJson = json_encode($grades);
        $gradesMessageJson = json_encode($gradesMessage);

        if (Gate::allows('isChiefAppraiser') || Gate::allows('isAppraiser')) {
            $FreelancerGradeData = $request->all();

            if (auth()->user()->role === 'appraiser') {
                $FreelancerGradeData['suggest_grade_message'] = $gradesMessageJson;
                $FreelancerGradeData['suggest_grade_data'] = $gradesJson;
                $FreelancerGradeData['suggest_grade'] = $average;
            } elseif (auth()->user()->role === 'chiefAppraiser') {
                $FreelancerGradeData['grade_message'] = $gradesMessageJson;
                $FreelancerGradeData['grade_data'] = $gradesJson;
                $FreelancerGradeData['grade'] = $average;
            }
            
            $FreelancerGrade = FreelancerSection::find($id);

            FreelancerGradeLog::create([
                'user_id' => auth()->user()->id,
                'freelancer_id' => $FreelancerGrade->users_id,
                'freelancer_section_id' => $id,
                'grade_data' => $gradesJson,
                'grade_message' => $gradesMessageJson,
                'from_grade' => Gate::allows('isChiefAppraiser') ? $FreelancerGrade->grade : $FreelancerGrade->suggest_grade,
                'to_grade' => $average,
            ]);

            if ($FreelancerGrade->update($FreelancerGradeData)) {
                return back()->with('notification', ['class' => 'success', 'message' => 'سطح با موفقیت ثبت شد.']);
            } else {
                return redirect()->back()->with('notification', [
                    'status' => 'danger',
                    'message' => 'سطح ثبت نشد!',
                ]);
            }
        } else {
            return redirect('/dashboard/no-permissions');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        foreach ($request->delete_item as $key => $item) {
            /* Resume Delete */
            FreelancerSection::where('id', $key)->delete();
        }

        return redirect('/dashboard/freelancer-grade')->with('notification', [
            'class' => 'success',
            'message' => 'شناسه های مورد نظر حذف شد'
        ]);
    }

    public function historyDetails($id)
    {
        $Log = FreelancerGradeLog::find($id);
        $OldGrades = json_decode($Log->grade_data ?? '{}', true);
        $OldGradesMessage = json_decode($Log->grade_message ?? '{}', true);
        $Labels = [
            1 => 'کیفیت کار',
            2 => 'مدیریت وظائف و مسئولیت‌ها',
            3 => 'ارتباطات و ارائه بازخورد',
            4 => 'دانش کاربردی و موضوعات مرتبط',
            5 => 'چابکی و پویایی یادگیری',
            6 => 'توانایی تحلیل و حل مسئله',
            7 => 'همسویی با ارزش‌ها',
            8 => 'همکاری',
            9 => 'خدمت به مشتری/همکاران واحدهای دیگر',
            10 => 'خلاقیت و نوآوری',
        ];


        $Data = [
            'OldGrades',
            'OldGradesMessage',
            'Labels',
            'Log',
        ];

        return view('freelancergrade::history-details', compact($Data));
    }
}
