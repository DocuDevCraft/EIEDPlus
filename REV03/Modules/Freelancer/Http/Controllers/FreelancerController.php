<?php

namespace Modules\Freelancer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\Freelancer;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;

class FreelancerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $sectionIds = auth()->user()->Section->pluck('id')->toArray();

        $sectionIds = auth()->user()->Section->pluck('id')->toArray();

        $Freelancer = Freelancer::select('freelancer.*', 'users.created_at')
            ->join('users', 'freelancer.users_id', '=', 'users.id')
            ->where(function ($query) use ($request) {
                $query->where('users.first_name', 'like', "%{$request->search}%")
                    ->orWhere('users.last_name', 'like', "%{$request->search}%")
                    ->orWhere('users.email', 'like', "%{$request->search}%");
            })
            ->whereHas('FreelancerSection', function ($q) use ($sectionIds) {
                $q->whereIn('section_id', $sectionIds);
            })
            ->with(['FreelancerSection.sectionTable' => function ($q) use ($sectionIds) {
                $q->whereIn('id', $sectionIds)->select('id', 'title');
            }])
            ->orderBy('users.created_at', 'desc')
            ->paginate(20);


        return view('freelancer::index', compact('Freelancer'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('freelancer::create');
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Freelancer = Freelancer::find($id);

        return view('freelancer::edit', compact('Freelancer'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function HourlyContractSubmit(Request $request)
    {
        foreach ($request->item as $key => $item) {
            $Freelancer = Freelancer::find($key);
            if ($Freelancer->hourly_contract === 'no') {
                $Freelancer->update([
                    'hourly_contract' => 'pending',
                    'hourly_rate' => '400000'
                ]);
                SmsHandlerController::Send(["$Freelancer->users->phone"], "قرارداد نفرساعت از طرف مدیر بخش در سامانه EIED+ برای شما ثبت شده است، لطفا با مراجعه به بخش حساب کاربری اقدام به امضا این قراداد نمایید.");
            }
        }

        return redirect('/dashboard/freelancer')->with('notification', [
            'class' => 'success',
            'message' => 'قراردادها ارسال شدند'
        ]);
    }
}
