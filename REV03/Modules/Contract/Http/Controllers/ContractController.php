<?php

namespace Modules\Contract\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\FreelancerContract;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Entities\WorkPackage;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $Contract = FreelancerContract::select('freelancer_contract.*', 'users.first_name', 'users.last_name')
            ->join('users', 'freelancer_contract.user_id', '=', 'users.id')
            ->where('users.first_name', 'like', "%{$request->search}%")
            ->orWhere('users.last_name', 'like', "%{$request->search}%")
            ->orderBy('freelancer_contract.updated_at', 'desc')->paginate(20);
        return view('contract::index', compact('Contract'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Contract = FreelancerContract::find($id);

        return view('contract::edit', compact('Contract'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $FreelancerContract = FreelancerContract::find($id);

        $WorkPackage = WorkPackage::find($FreelancerContract->work_package_id);
        $FreelancerOffer = FreelancerOffer::where('user_id', $FreelancerContract->user_id)->where('work_package_id', $FreelancerContract->work_package_id)->where('status', 'winner')->first();
        $data = [];

        foreach ($WorkPackage->wpCategory->where('stage', 1) as $item) {
            $item->update([
                'activation_at' => Carbon::now()
            ]);
        }
        
        if ($request->hasFile('contract_employer_signed')) {
            \File::makeDirectory(storage_path() . '/contract/employer-signed/', $mode = 0755, true, true);
            $path = $request->file('contract_employer_signed')->store('contract/employer-signed');
            $data['status'] = 'employer_signed';
            $data['contract_employer_signed'] = basename($path);
        }

        if ($FreelancerContract->update($data)) {
            if ($WorkPackage->status != 'activated') {
                $WorkPackage->update([
                    'status' => $FreelancerOffer->status === 'winner' ? 'activated' : $WorkPackage->status,
                    'wp_final_time' => $FreelancerOffer->time,
                    'wp_final_price' => $FreelancerOffer->price,
                    'wp_activation_time' => Carbon::now(),
                ]);

                if ($WorkPackage->wp_activation_time) {

                }
            }

            if ($FreelancerContract->status === "employer_signed") {
                SmsHandlerController::Send([$FreelancerContract->users->phone], "تبریک، قرارداد بسته کاری «{$WorkPackage->title}» توسط کارفرما امضا شد. بسته کاری هم اکنون فعال و آماده اجرا می باشد.");
            }
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'قرارداد بروزرسانی شد.'
            ]);
        }
    }


    public function download($path, $file)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $filePath = storage_path('app/contract/' . $path . '/' . $file);


        if (!file_exists($filePath)) {
            abort(404);
        }


        return response()->download($filePath, $file, [
            'Content-Type' => 'application/pdf', // تنظیم نوع فایل
        ]);
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
}
