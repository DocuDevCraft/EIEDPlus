<?php

namespace Modules\FreelancerOffer\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\FreelancerOffer\Entities\FreelancerOfferLog;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\UserAccessHandler\Entities\UserAccessHandler;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;

class FreelancerOfferController extends Controller
{

    public function checkAccess($id)
    {
        $WorkPackage = WorkPackage::select('id', 'offer_time', 'published_at')->findOrFail($id);
        if ($WorkPackage->published_at) {
            $ShowPacket = Carbon::createFromFormat('Y-m-d H:i:s', $WorkPackage->published_at)->addDay($WorkPackage->offer_time)->format('Y-m-d H:i:s') <= Carbon::now()->format('Y-m-d H:i:s');

            if (!$ShowPacket) {
                $AccessStatus = 'not_yet';
            } else {
                if (auth()->user()->role === 'sectionManager') {
                    return redirect()->route('freelancer.offer.list', $id);
                }
                $checkAccess = UserAccessHandler::where('target_id', $id)->where('user_id', auth()->id())->first();
                if ($checkAccess) {
                    if ($checkAccess->expire_at) {
                        if ($checkAccess->expire_at > now()) {
                            return redirect()->route('freelancer.offer.list', $id);
                        } else {
                            $AccessStatus = 'code_not_found';
                        }
                    } else {
                        $AccessStatus = 'submit-otp';
                    }
                } else {
                    $AccessStatus = 'code_not_found';
                }
            }
        } else {
            $AccessStatus = 'not_yet';
        }

        $Data = [
            'AccessStatus',
        ];

        return view('freelanceroffer::checkAccess', compact($Data));
    }

    public function sendOTP(Request $request, $id)
    {
        $workPackage = WorkPackage::select('id', 'title', 'offer_time', 'published_at')
            ->findOrFail($id);

        $canShow = Carbon::parse($workPackage->published_at)
            ->addDays($workPackage->offer_time)
            ->isPast();

        if (!$canShow) {
            return back()->with('notification', [
                'class' => 'danger',
                'message' => 'زمان دسترسی نرسیده است.'
            ]);
        }

        $manager = Users::where('role', 'engineeringManager')
            ->select('id', 'phone')
            ->firstOrFail();

        $code = random_int(10000, 99999);

        $message = "درخواست دسترسی به لیست پیشنهادات بسته کاری «{$workPackage->title}» از طرف "
            . auth()->user()->first_name . ' ' . auth()->user()->last_name . "\n"
            . "کد دسترسی: {$code}";

        if (!SmsHandlerController::Send([$manager->phone], $message)) {
            return back();
        }

        UserAccessHandler::where('target_id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        $access = UserAccessHandler::create([
            'access_type' => 'freelancer offer',
            'access_code' => $code,
        ]);

        $access->forceFill([
            'user_id' => auth()->id(),
            'target_id' => $id,
            'receive_user_id' => $manager->id,
        ])->save();

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'کد با موفقیت ارسال شد.'
        ]);
    }

    public function submitOTP(Request $request, $id)
    {
        $validated = $request->validate([
            'otp' => 'required|numeric',
        ], [], [
            'otp' => 'کد دسترسی'
        ]);

        $WorkPackage = WorkPackage::select('id', 'offer_time', 'published_at')->findOrFail($id);
        $ShowPacket = Carbon::createFromFormat('Y-m-d H:i:s', $WorkPackage->published_at)->addDay($WorkPackage->offer_time)->format('Y-m-d H:i:s') <= Carbon::now()->format('Y-m-d H:i:s');
        if ($ShowPacket) {
            $checkAccess = UserAccessHandler::where('target_id', $id)->where('user_id', auth()->id())->first();
            if ($checkAccess) {
                if ($checkAccess->expire_at && $checkAccess->expire_at > now()) {
                    return redirect()->route('freelancer.offer.list', $id);
                } else {
                    if ($checkAccess->access_code === $request->otp) {
                        $checkAccess->forceFill([
                            'use_time_at' => now(),
                            'expire_at' => now()->addDay(),
                        ])->save();
                        return redirect()->route('freelancer.offer.list', $id);
                    } else {
                        return redirect()->back()->with('notification', [
                            'class' => 'warning',
                            'message' => 'کد تایید صحیح نمی باشد.'
                        ]);
                    }
                }
            } else {
                return redirect()->route('freelancer.offer.checkAccess', $id);
            }
        }
    }

    public function offerList(Request $request, $id)
    {
        if (Gate::allows('isWorkPackageManagerOnly')) {
            abort(403);
        }

        $ID = $id;
        $WorkPackage = WorkPackage::select(['id', 'offer_time', 'winning_formula', 'work_package_type', 'offer_list_status', 'offer_list_sorting', 'offer_list_file', 'recommend_price', 'recommend_time', 'published_at', 'work_package_scale'])->find($id);
        $ShowPacket = Carbon::createFromFormat('Y-m-d H:i:s', $WorkPackage->published_at)->addDay($WorkPackage->offer_time)->format('Y-m-d H:i:s') <= Carbon::now()->format('Y-m-d H:i:s');


        if ($ShowPacket) {
            $checkAccess = UserAccessHandler::where('target_id', $id)->where('user_id', auth()->id())->where('expire_at', '>', now())->first();

            if (auth()->user()->role === 'sectionManager' || $checkAccess) {
                if (isset($WorkPackage['offer_list_sorting'])) {
                    $WorkPackage['winning_formula'] = $WorkPackage['offer_list_sorting'];
                }
                if ($request->winning_formula) {
                    $WorkPackage['winning_formula'] = $request->winning_formula;
                }
                if ($request->recommend_price) {
                    $WorkPackage['recommend_price'] = $request->recommend_price;
                }
                if ($request->recommend_time) {
                    $WorkPackage['recommend_time'] = $request->recommend_time;
                }


                if ($WorkPackage['winning_formula'] === 'lowest_price') {
                    $orderBy = ['price', 'asc'];
                } elseif ($WorkPackage['winning_formula'] === 'less_time') {
                    $orderBy = ['time', 'asc'];
                } elseif ($WorkPackage['winning_formula'] === 'grade') {
                    $orderBy = ['grade.grade', 'asc'];
                } else {
                    $orderBy = ['id', 'desc'];
                }

                $workPackage = WorkPackage::find($id);
                $sectionId = $workPackage->section_id;
                $subsectionId = $workPackage->subsection_id;
                $divisionId = $workPackage->division_id;

                $FreelancerOffer = FreelancerOffer::where('work_package_id', $id)->orderBy($orderBy[0])->paginate(50);

                foreach ($FreelancerOffer as $item) {
                    $grade = FreelancerSection::where('users_id', $item->user_id)
                        ->where(function ($query) use ($sectionId, $subsectionId, $divisionId) {
                            $query->where('section_id', $sectionId)
                                ->where('subsection_id', $subsectionId)
                                ->where('division_id', $divisionId);
                        })->first();

                    $item['gradeScore'] = is_null($grade) ? 0 : $grade->final_grade;
                }

                if (auth()->user()->role !== 'sectionManager') {
                    $log = FreelancerOfferLog::make();

                    $log->forceFill([
                        'user_id' => auth()->id(),
                        'work_package_id' => $id,
                    ])->save();
                }

                $viewCount = FreelancerOfferLog::where('work_package_id', $id)->count();

                $Data = [
                    'ID',
                    'WorkPackage',
                    'FreelancerOffer',
                    'ShowPacket',
                    'viewCount'
                ];
                if (auth()->user()->role !== 'sectionManager') {
                    $checkAccess->forceFill([
                        'use_time_at' => now(),
                        'expire_at' => now(),
                    ])->save();
                }
                return view('freelanceroffer::index', compact($Data));
            } else {
                return redirect()->route('freelancer.offer.checkAccess', $id);
            }
        } else {
            return redirect()->route('freelancer.offer.checkAccess', $id);
        }
    }

    public function show($id)
    {
        if (!Gate::allows('isAdmin')) {
            abort(403);
        }
        $FreelancerOffer = FreelancerOffer::find($id);
        $WorkPackage = WorkPackage::find($FreelancerOffer->work_package_id);

        if (($WorkPackage->offer_list_file === '' || $WorkPackage->offer_list_file === null) || $WorkPackage->offer_list_status !== 'accepted' && $WorkPackage->offer_list_status !== 'final') {
            abort(403);
        }

        $FreelancerSection = FreelancerSection::where('users_id', $FreelancerOffer->user_id)->get();

        $Data = [
            'FreelancerOffer',
            'FreelancerSection',
        ];

        return view('freelanceroffer::show', compact($Data));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('isAdmin')) {
            abort(403);
        }

        $FreelancerOffer = FreelancerOffer::find($id);
        $WorkPackage = WorkPackage::find($FreelancerOffer->work_package_id);
        if (($WorkPackage->offer_list_file === '' || $WorkPackage->offer_list_file === null) || $WorkPackage->offer_list_status !== 'accepted' && $WorkPackage->offer_list_status !== 'final') {
            abort(403);
        }

        if ($FreelancerOffer->workPackageTable->work_package_type === 'public') {
            if ($request->status === 'awaiting_signature') {
                $mobile = $FreelancerOffer->users->phone;
                SmsHandlerController::Send(["$mobile"], "کاربر گرامی پیشنهاد شما برنده منقاصه شده است جهت امضا در اسرع وقت اقدام نمایید.");
            }

            if ($request->status === 'winner' || $request->status === 'awaiting_signature') {
                FreelancerOffer::where('work_package_id', $FreelancerOffer->work_package_id)
                    ->whereNotIn('id', [$id])
                    ->get()
                    ->each(function ($offer) {
                        $offer->forceFill(['status' => 'rejected'])->save();
                    });
                $FreelancerOffer->forceFill([
                    'status' => $request->status,
                ])->save();
                $WorkPackage->forceFill([
                    'status' => $request->status === 'winner' ? 'pre_activate' : $request->status,
                ])->save();
            } else {
                $FreelancerOffer->forceFill([
                    'status' => $request->status,
                ])->save();
            }

//        if ($request->status === 'winner' || $request->status === 'awaiting_signature') {
//            FreelancerOffer::where('work_package_id', $FreelancerOffer->work_package_id)->whereNotIn('id', [$id])->update(['status' => 'rejected']);
//            $FreelancerOffer->update(['status' => $request->status]);
//            WorkPackage::find($FreelancerOffer->work_package_id)->update([
//                'status' => $request->status === 'winner' ? 'activated' : $request->status,
//                'wp_final_time' => $FreelancerOffer->time,
//                'wp_final_price' => $FreelancerOffer->price,
//                'wp_activation_time' => Carbon::now(),
//            ]);
//        } else {
//            $FreelancerOffer->update(['status' => $request->status]);
//        }

            return back()->with('notification', ['class' => 'success', 'message' => 'وضعیت ثبت شد.']);
        } else {
            if ($request->status == 'winner') {
                $FreelancerOffer->forceFill(['status' => $request->status])->save();
                if ($FreelancerOffer->workPackageTable->status != 'activated') {
                    $FreelancerOffer->workPackageTable->forceFill([
                        'status' => 'activated',
                        'wp_final_time' => $FreelancerOffer->time,
                        'wp_final_price' => $FreelancerOffer->price,
                        'wp_activation_time' => Carbon::now(),
                    ])->save();
                }
                SmsHandlerController::Send([$FreelancerOffer->users->phone], "تبریک، قرارداد بسته کاری «{$FreelancerOffer->workPackageTable->title}» توسط کارفرما امضا شد. بسته کاری هم اکنون فعال و آماده اجرا می باشد.");
                return back()->with('notification', ['class' => 'success', 'message' => 'وضعیت ثبت شد.']);
            } elseif ($request->status == 'awaiting_signature') {
                return back()->with('notification', ['class' => 'success', 'message' => 'وضعیت صحیح نیست.']);
            } else {
                $FreelancerOffer->forceFill(['status' => $request->status])->save();
                return back()->with('notification', ['class' => 'success', 'message' => 'وضعیت ثبت شد.']);
            }
        }
    }

    public function sortUpdate(Request $request, $id)
    {
        if (Gate::allows('isWorkPackageManagerOnly')) {
            abort(403);
        }
        $WorkPackage = WorkPackage::select('id', 'offer_list_sorting', 'offer_list_status')->find($id);
        if ($WorkPackage->offer_list_status == null) {
            $WorkPackage->forceFill([
                'offer_list_sorting' => $request->winning_formula,
            ])->save();
            return redirect('dashboard/freelancer-offer/' . $id)->with('notification', [
                'class' => 'success',
                'message' => 'ترتیب لیست بروزرسانی شد.'
            ]);
        } else {
//            return redirect('dashboard/freelancer-offer/' . $id)->with('notification', [
//                'class' => 'danger',
//                'message' => 'زمان تغییر گذشته است.'
//            ]);
        }
    }

    public function listStatus($id)
    {
        if (Gate::allows('isWorkPackageManagerOnly')) {
            abort(403);
        }
        $WorkPackage = WorkPackage::select('id', 'offer_list_status')->find($id);

        if (auth()->user()->role === 'sectionManager' && $WorkPackage->offer_list_status == null) {
            $WorkPackage->forceFill([
                'offer_list_status' => 'manager_suggest',
            ])->save();
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'لیست شما برای تایید ارسال شد.'
            ]);
        } elseif (auth()->user()->role === 'admin' && $WorkPackage->offer_list_status === 'manager_suggest') {
            $WorkPackage->forceFill([
                'offer_list_status' => 'accepted',
            ])->save();
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'تایید لیست اعلام شد.'
            ]);
        } elseif (auth()->user()->role === 'sectionManager' && $WorkPackage->offer_list_status === 'accepted') {
            $WorkPackage->forceFill([
                'offer_list_status' => 'final',
            ])->save();
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'لیست تایید شد.'
            ]);
        }

        return redirect()->back()->with('notification', [
            'class' => 'danger',
            'message' => 'مشکلی پیش آماده.'
        ]);
    }

    public function offerExportPDF($id)
    {
        $ID = $id;
        $WorkPackage = WorkPackage::select('id', 'title', 'unique_id', 'work_package_type', 'offer_time', 'winning_formula', 'offer_list_status', 'offer_list_sorting', 'recommend_price', 'recommend_time', 'published_at', 'work_package_scale')->find($id);
        if (isset($WorkPackage['offer_list_sorting'])) {
            $WorkPackage['winning_formula'] = $WorkPackage['offer_list_sorting'];
        }

        if ($WorkPackage['winning_formula'] === 'lowest_price') {
            $orderBy = ['price', 'asc'];
        } elseif ($WorkPackage['winning_formula'] === 'less_time') {
            $orderBy = ['time', 'asc'];
        } elseif ($WorkPackage['winning_formula'] === 'grade') {
            $orderBy = ['grade.grade', 'asc'];
        } else {
            $orderBy = ['id', 'desc'];
        }

        $workPackage = WorkPackage::find($id);
        $sectionId = $workPackage->section_id;
        $subsectionId = $workPackage->subsection_id;
        $divisionId = $workPackage->division_id;

        $FreelancerOffer = FreelancerOffer::where('work_package_id', $id)->orderBy($orderBy[0])->paginate(50);

        foreach ($FreelancerOffer as $item) {
            $grade = FreelancerSection::where('users_id', $item->user_id)
                ->where(function ($query) use ($sectionId, $subsectionId, $divisionId) {
                    $query->where('section_id', $sectionId)
                        ->where('subsection_id', $subsectionId)
                        ->where('division_id', $divisionId);
                })->first();

            $item['gradeScore'] = is_null($grade) ? 0 : $grade->final_grade;
        }


        $Contract = OfferListPDFContentController::ContractText($WorkPackage, $FreelancerOffer);
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_bottom' => 20,
            'fontDir' => array_merge($fontDirs, [
                module_path('Dashboard', '/Resources/assets/admin/pdf-fonts'),
            ]),
            'fontdata' => $fontData + [
                    'yekan' => [
                        'R' => 'YekanBakhFaNum-Regular.ttf',
                        'B' => 'YekanBakhFaNum-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ]
                ],
            'default_font' => 'yekan',
            'tempDir' => storage_path('temp'),
        ]);
        $stylesheet = file_get_contents(module_path('Dashboard', '/Resources/assets/admin/css/pdf-style.min.css'));
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($Contract);
        \File::makeDirectory(storage_path() . '/app/offer-list/', $mode = 0755, true, true);
        $pdfName = 'Offer-List-' . $id . '-' . date('Y-m-d_H-i-s') . '.pdf';
        $mpdf->Output(storage_path() . '/app/offer-list/' . $pdfName);

        return response()->download(storage_path('app/offer-list' . '/') . $pdfName);
    }

    public function offerExportPDFUpload(Request $request, $id)
    {
        $request->validate([
            'offer_list' => 'required|file|mimes:pdf',
        ], [], [
            'offer_list' => 'لیست پیشنهادات'
        ]);

        if ($request->hasFile('offer_list')) {
            $file = $request->file('offer_list');
            $fileName = 'offer-' . $id . '-' . date('Y-m-d_H-i-s') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('offer-list/signed', $fileName);
            WorkPackage::find($id)->forceFill([
                'offer_list_status' => 'accepted',
                'offer_list_file' => $fileName,
            ])->save();
            return redirect()
                ->route('work-package-offer-upload-completed', $id)
                ->with('notification', [
                    'class' => 'success',
                    'message' => 'فایل لیست پیشنهادات فریلنسرها با موفقیت آپلود شد.',
                ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'danger',
                'message' => 'فایل آپلود نشد.'
            ]);
        }
    }

    public function offerListUploadCompleted($id)
    {
        return view('freelanceroffer::uploadCompleted');
    }

    public function offerPDFDownload($id)
    {
        $WorkPackageFile = WorkPackage::find($id)->offer_list_file;
        $path = storage_path('app/offer-list/signed/' . $WorkPackageFile);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);

    }
}
