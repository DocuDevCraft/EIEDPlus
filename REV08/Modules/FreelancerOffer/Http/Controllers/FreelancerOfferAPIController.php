<?php

namespace Modules\FreelancerOffer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\FreelancerOffer\Http\Controllers\OfferContract\OfferContractController;
use Modules\FreelancerOffer\Http\Requests\FreelancerOfferRequest;
use Modules\WorkPackageManager\Entities\WorkPackage;

class FreelancerOfferAPIController extends Controller
{

    public function show($id)
    {
        $getData = [];
        $getData['details'] = WorkPackage::select('id', 'title', 'desc', 'package_number', 'package_price_type', 'package_time_type', 'attachment_for_all', 'rules', 'status', 'recommend_time', 'recommend_price', 'work_package_type')->find($id);

        $allOffer = FreelancerOffer::where('work_package_id', $id)->select('price', 'time')->get();

        if (isset($allOffer) && count($allOffer)) {
            $totalPrice = 0;
            $totalTime = 0;
            foreach ($allOffer as $item) {
                $totalPrice += $item->price;
                $totalTime += $item->time;
            }

            $getData['details']['average'] = ['price' => round($totalPrice / count($allOffer)), 'time' => round($totalTime / count($allOffer))];
        }

        if ($getData['details']) {
            if ($getData['details']->attachment_for_all) {
                $getData['details']['attachment_for_all'] = [
                    'file_name' => HomeController::GetFileName($getData['details']->attachment_for_all),
                    'path' => HomeController::GetFilePath($getData['details']->attachment_for_all)
                ];
            }

            if ($getData['details']->status !== 'new') {
                $getData = 'no_permission';
            }
        }

        return response()->json(['getData' => $getData]);
    }

    public function storeOffer(FreelancerOfferRequest $request, $id)
    {
        $WorkPackage = WorkPackage::select(['id', 'work_package_type', 'published_at', 'offer_time'])->find($id);

        $ShowPacket =
            Carbon::createFromFormat('Y-m-d H:i:s', $WorkPackage->published_at)
                ->addDay($WorkPackage->offer_time)
                ->format('Y-m-d H:i:s')
            < Carbon::now()->format('Y-m-d H:i:s');

        if ($ShowPacket) {
            return response()->json(['status' => 'expired']);
        }

        if ($WorkPackage->work_package_type === 'public') {

            $request->validate([
                'price' => 'required|numeric|min:1000',
            ], [
                'price.required' => 'وارد کردن قیمت برای قراردادهای عمومی الزامی است.',
                'price.numeric' => 'قیمت باید عددی باشد.',
                'price.min' => 'حداقل مبلغ مجاز ۱۰۰۰ است.',
            ]);

            $signatureProcess = OfferContractController::signatureProcess($request, $id);

            if ($signatureProcess) {

                $freelancerOffer = FreelancerOffer::where('work_package_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->where('status', 'pending')
                    ->where('status', '!=', 'rejected')
                    ->first();

                if ($freelancerOffer) {

                    $freelancerOffer->update([
                        'price' => $request->price,
                        'time' => $request->time,
                        'attachment' => $request->file('attachment')
                            ? FileLibraryController::upload($request->file('attachment'), 'file', 'work-package/offer', 'WorkPackage Offer')
                            : null,
                    ]);

                    // فیلد غیر fillable
                    $freelancerOffer->forceFill([
                        'status' => 'pending',
                    ])->save();

                    return response()->json(['getData' => $freelancerOffer]);

                } else {

                    $freelancerOffer = FreelancerOffer::create([
                        'price' => $request->price,
                        'time' => $request->time,
                        'attachment' => $request->file('attachment')
                            ? FileLibraryController::upload($request->file('attachment'), 'file', 'work-package/offer', 'WorkPackage Offer')
                            : null,
                    ]);

                    $freelancerOffer->forceFill([
                        'user_id' => auth('sanctum')->user()->id,
                        'work_package_id' => $id,
                        'status' => 'pending',
                    ])->save();

                    return response()->json(['getData' => $freelancerOffer]);
                }
            }

            return response()->json(['status' => 401, 'data' => $signatureProcess]);

        } else {

            $request->price = ($request->time * 8) * 400000;

            $freelancerOffer = FreelancerOffer::where('work_package_id', $id)
                ->where('user_id', auth()->user()->id)
                ->where('status', 'pending')
                ->where('status', '!=', 'rejected')
                ->first();

            if ($freelancerOffer) {

                $freelancerOffer->update([
                    'price' => $request->price,
                    'time' => $request->time,
                    'attachment' => $request->file('attachment')
                        ? FileLibraryController::upload($request->file('attachment'), 'file', 'work-package/offer', 'WorkPackage Offer')
                        : null,
                ]);

                $freelancerOffer->forceFill([
                    'status' => 'pending',
                ])->save();

                return response()->json(['getData' => $freelancerOffer]);

            } else {

                $freelancerOffer = FreelancerOffer::create([
                    'price' => $request->price,
                    'time' => $request->time,
                    'attachment' => $request->file('attachment')
                        ? FileLibraryController::upload($request->file('attachment'), 'file', 'work-package/offer', 'WorkPackage Offer')
                        : null,
                ]);

                $freelancerOffer->forceFill([
                    'user_id' => auth('sanctum')->user()->id,
                    'work_package_id' => $id,
                    'status' => 'pending',
                ])->save();

                return response()->json(['getData' => $freelancerOffer]);
            }
        }
    }
}
