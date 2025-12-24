<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerContract;
use Modules\Freelancer\Entities\FreelancerHourlyContract;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\SignatureSystem\Http\Controllers\SignatureSystemController;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Morilog\Jalali\Jalalian;

class WorkPackageManagerAPIController extends Controller
{
    /*
    * Get Summery Package Data
    * Route: /api/work-package/list/{slug}/{count}
    * GET
    * */
    public function workPackageList($slug, $count)
    {
        /* Get Freelancer Speciality */
        $FreelancerDivision = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->whereNotNull('division_id')->whereNotNull('grade')->pluck('division_id')->toArray();
        $FreelancerSubSection = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->whereNull('division_id')->whereNotNull('subsection_id')->whereNotNull('grade')->pluck('subsection_id')->toArray();
        $FreelancerSection = FreelancerSection::where('users_id', auth('sanctum')->user()->id)->whereNull('division_id')->whereNull('subsection_id')->whereNotNull('section_id')->whereNotNull('grade')->pluck('section_id')->toArray();

        /* Check Work Package Offered */
        $FreelancerOffer = FreelancerOffer::where('user_id', auth('sanctum')->user()->id)->pluck('work_package_id')->toArray();

        $Data = [];
//            ->when($FreelancerDivision, function ($q) use ($FreelancerDivision) {
//            /* Check Please */
//            return $q->whereIn('division_id', $FreelancerDivision ?: []);
//        })
        $userId = auth('sanctum')->id();
        $freelancerId = auth('sanctum')->user()?->freelancer?->id;

// همه‌ی user_id‌هایی که قرارداد نفرساعتی امضا کرده‌اند
        $hourlySignedUserIds = FreelancerHourlyContract::where('status', 'freelancer_signed')
            ->pluck('user_id')
            ->toArray();

        $Data['new'] = WorkPackage::query()
            ->whereIn('section_id', $FreelancerSection ?: [])
            ->orWhereIn('subsection_id', $FreelancerSubSection ?: [])
            ->whereNotIn('id', $FreelancerOffer ?: [])
            ->select('id', 'title', 'status', 'package_number', 'package_price_type', 'package_time_type', 'wp_activation_time', 'work_package_type', 'published_at', 'offer_time')
            ->where('status', 'new')
            ->where(function ($q) use ($freelancerId, $userId, $hourlySignedUserIds) {

                // ✅ ۱. بسته‌های عمومی بدون فریلنسر → برای همه
                $q->where(function ($sub) {
                    $sub->where('work_package_type', 'public')
                        ->whereDoesntHave('freelancers');
                });

                // ✅ ۲. بسته‌های مخصوص فریلنسر فعلی
                if ($freelancerId) {
                    $q->orWhereHas('freelancers', function ($sub) use ($freelancerId) {
                        $sub->where('freelancer_id', $freelancerId);
                    });
                }

                // ✅ ۳. بسته‌های نفرساعتی بدون فریلنسر
                // فقط برای کاربران دارای قرارداد نفرساعتی امضا شده
                if (in_array($userId, $hourlySignedUserIds)) {
                    $q->orWhere(function ($sub) {
                        $sub->where('work_package_type', 'hourly_contract')
                            ->whereDoesntHave('freelancers');
                    });
                }
            })
            ->whereRaw('DATE_ADD(published_at, INTERVAL offer_time DAY) > ?', [Carbon::now()])
            ->orderBy('updated_at', 'desc');

        /*
        ->orWhereIn('division_id', $FreelancerDivision ?: [])
         */

        if ($count == -1) {
            $Data['new'] = $Data['new']->paginate(10)->onEachSide(1);
        } else {
            $Data['new'] = $Data['new']->take($count)->get();
        }

        $Data['order_status'] = FreelancerOffer::where('user_id', auth('sanctum')->user()->id)->where('status', '!=', 'awaiting_signature')->orderBy('updated_at', 'desc')->when($count, function ($query) use ($slug, $count) {
            if ($count == -1) {
                return $query->paginate(6)->onEachSide(1);
            } else {
                return $query->take($count)->get();
            }
        });
        $Data['awaiting_signature'] = FreelancerOffer::where('user_id', auth('sanctum')->user()->id)->where('status', 'awaiting_signature')->orderBy('updated_at', 'desc')->when($count, function ($query) use ($slug, $count) {
            if ($count == -1) {
                return $query->paginate(6)->onEachSide(1);
            } else {
                return $query->take($count)->get();
            }
        });

        $query = WorkPackage::where('status', 'activated')
            ->select(['id', 'package_number', 'title', 'wp_activation_time', 'wp_final_time', 'wp_final_price', 'work_package_type'])
            ->whereHas('freelancer_offers', function ($query) {
                $query->where('user_id', auth('sanctum')->id())
                    ->where('status', 'winner');
            })
            ->orderBy('updated_at', 'desc');

        if ($count == -1) {
            $Data['activated'] = $query->paginate(6)->through(function ($workPackage) {
                if ($workPackage->wp_final_time && $workPackage->wp_activation_time) {
                    $targetDate = Jalalian::forge($workPackage->wp_activation_time)
                        ->addDays($workPackage->wp_final_time);

                    $workPackage->time_remaining = str_replace('پیش', 'گذشته', $targetDate->ago());
                    $workPackage->time_remaining_date = $targetDate->format('Y/m/d');
                } else {
                    $workPackage->time_remaining = '-';
                    $workPackage->time_remaining_date = '-';
                }
                return $workPackage;
            });
        } else {
            $Data['activated'] = $query->take($count)
                ->get()
                ->map(function ($workPackage) {
                    if ($workPackage->wp_final_time && $workPackage->wp_activation_time) {
                        $targetDate = Jalalian::forge($workPackage->wp_activation_time)
                            ->addDays($workPackage->wp_final_time);

                        $workPackage->time_remaining = str_replace('پیش', 'گذشته', $targetDate->ago());
                        $workPackage->time_remaining_date = $targetDate->format('Y/m/d');
                    } else {
                        $workPackage->time_remaining = '-';
                        $workPackage->time_remaining_date = '-';
                    }
                    return $workPackage;
                });
        }


        $Data['completed'] = WorkPackage::where('status', 'completed')
            ->select(['id', 'status', 'uid', 'title', 'package_number'])
            ->whereHas('freelancer_offers', function ($query) {
                $query->where('user_id', auth('sanctum')->id());
            })
            ->where(function ($query) {
                $query->whereDoesntHave('wpCategory')
                    ->orWhereHas('wpCategory', function ($q) {
                        $q->whereHas('payments', function ($p) {
                            $p->where('status', 'paid');
                        });
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->when($count, function ($query) use ($count) {
                if ($count == -1) {
                    return $query->paginate(6)->onEachSide(1);
                } else {
                    return $query->take($count)->get();
                }
            });


        /* New */
        foreach ($Data['new'] as $key => $item) {
            $Data['new'][$key]['total_offer'] = FreelancerOffer::where('work_package_id', $item->id)->count();
        }


        /* Ordered */
        foreach ($Data['order_status'] as $key => $item) {
            $Data['order_status'][$key]['work_package'] = ['package_number' => $item->workPackageTable->package_number, 'title' => $item->workPackageTable->title, 'work_package_type' => $item->workPackageTable->work_package_type];
            $Data['order_status'][$key]['status'] = HomeController::OfferConvertStatus($item->status);
        }

        /* Awaiting */
        foreach ($Data['awaiting_signature'] as $key => $item) {
            $Data['awaiting_signature'][$key]['work_package'] = WorkPackage::select('package_number', 'title')->find($item->work_package_id);
            $Data['awaiting_signature'][$key]['time_remaining'] = Jalalian::forge($item->updated_at->addDays(2))->ago();
        }

        if ($slug === 'summery') {
            return response()->json(['getData' => $Data]);
        } elseif ($slug === 'new') {
            return response()->json(['getData' => $Data['new']]);
        } elseif ($slug === 'my-order') {
            return response()->json(['getData' => $Data['order_status']]);
        } elseif ($slug === 'awaiting-signature') {
            return response()->json(['getData' => $Data['awaiting_signature']]);
        } elseif ($slug === 'activated') {
            return response()->json(['getData' => $Data['activated']]);
        }
    }

    /*
    * Get Summery Package Data
    * Route: /api/work-package/details/{id}
    * GET
    * */
    public function workPackageDetails($id)
    {
        $WorkPackage = WorkPackage::select('id', 'title', 'desc', 'package_number', 'package_price_type', 'package_time_type', 'attachment_for_all', 'tag', 'rules', 'status')->find($id);

        if ($WorkPackage) {
            if ($WorkPackage->attachment_for_all) {
                $WorkPackage['attachment_for_all'] = [
                    'file_name' => HomeController::GetFileName($WorkPackage->attachment_for_all),
                    'path' => HomeController::GetFilePath($WorkPackage->attachment_for_all)
                ];
            }

            $WorkPackage->tag = json_decode($WorkPackage->tag, true);
            if ($WorkPackage->tag && count($WorkPackage->tag) > 0) {
                $WorkPackage['tag'] = array_first($WorkPackage->tag);
            }

            if (!in_array($WorkPackage->status, ['new', 'activated', 'completed'])) {
                $WorkPackage = ['status' => 'no_permission'];
            }
        }

        $WorkPackage['pre_contract'] = asset('site/Contract.docx');


        return response()->json(['getData' => $WorkPackage]);
    }

    public function workPackageSignature($id)
    {
        $getData = [];

        $getData['userData'] = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')->select('first_name', 'last_name', 'email', 'phone', 'avatar', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.sarbazi', 'freelancer.sarbazi_file', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.shenasnameh', 'freelancer.mahale_sodoor', 'freelancer.country', 'freelancer.home_phone', 'freelancer.postal_code', 'freelancer.address', 'freelancer.linkedin', 'freelancer.website', 'freelancer.biography')->find(auth('sanctum')->user()->id);
        $workPackage = WorkPackage::select('id', 'title', 'desc', 'package_number', 'wp_final_price', 'wp_final_time', 'status', 'wp_activation_time', 'wp_activation_time', 'signature', 'daily_fine', 'fine_after_day', 'fine_after_price', 'fine_after_negative')->find($id);
        $getData['details'] = $workPackage;
        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'awaiting_signature')->count();
        $getData['details']['time_remaining'] = Jalalian::forge($getData['details']->wp_activation_time)->addDays(7);
        $getData['details']['freelancer'] = Freelancer::where('users_id', auth('sanctum')->user()->id)->with(['users' => fn($query) => $query->select('id', 'first_name', 'last_name')])->first();
        $getData['details']['work_package_activity'] = WorkPackageActivity::where('work_package_id', $id)->get()->map(function ($item) {
            $categories = WorkPackageCategory::where('activity_id', $item->id)->get();

            $tasks = $categories->flatMap(function ($category) {
                return $category->Task;
            });

            return [
                'id' => $item->id,
                'title' => $item->title,
                'work_package_id' => $item->work_package_id,
                'price_percentage' => $item->price_percentage,
                'categories' => $categories,
            ];
        });


        if ($getData['userData']['meli_code'] && $freelancerOffer && $getData['details']->status === 'awaiting_signature') {
            $getData['getUserCertificateAction'] = SignatureSystemController::signature('getUserCertificateAction', [
                'nationalcode' => $getData['userData']['meli_code'],
            ]);
        }

        $Contract = SignatureTextController::SignatureText($getData);

        $getData['contract'] = $Contract;

        if ($freelancerOffer && $getData['details']->status === 'awaiting_signature') {
            return response()->json(['getData' => $getData]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public function workPackageSignatureStore(Request $request, $id)
    {
        $UserData = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')->select('first_name', 'last_name', 'email', 'phone', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.address')->find(auth('sanctum')->user()->id);
        $workPackage = WorkPackage::find($id);
        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'awaiting_signature')->first();

        if ($UserData['first_name'] && $UserData['last_name'] && $UserData['birthday'] && $UserData['birthday_miladi'] && $UserData['meli_code'] && $UserData['cardserialno'] && $UserData['email'] && $UserData['phone'] && $freelancerOffer['status'] === 'awaiting_signature') {
            return SignatureSystemController::signature('signature_request', [
                'nationalcode' => $UserData['meli_code'],
                'mobile' => $UserData['phone']
            ]);
        } else {
            return 'no';
        }
    }

    public function workPackageSignatureAuthStore(Request $request, $id)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $UserData = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')
            ->select('first_name', 'last_name', 'email', 'phone', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.postal_code', 'freelancer.address', 'freelancer.certpass')
            ->find(auth('sanctum')->user()->id);

        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)
            ->where('user_id', auth('sanctum')->user()->id)
            ->where('status', 'awaiting_signature')
            ->first();

        if (
            $UserData['first_name'] && $UserData['last_name'] && $UserData['birthday'] && $UserData['birthday_miladi'] &&
            $UserData['meli_code'] && $UserData['cardserialno'] && $UserData['email'] && $UserData['phone'] &&
            $freelancerOffer['status'] === 'awaiting_signature'
        ) {
            return SignatureSystemController::signature('signature_auth', [
                "nationalcode" => $UserData['meli_code'],
                'mobile' => $UserData['phone'],
                "cardserialno" => $UserData['cardserialno'],
                "certpass" => $UserData['certpass'],
                "birthdate" => $UserData['birthday_miladi'],
                "birthdateshamsi" => $UserData['birthday'],
                "postalcode" => $UserData['postal_code'],
                "callbackurl" => 'https://plus.eied.com/callback?errorcode=@errorcode&errormessage=@errormessage&work_package=' . $id,
                "otp" => $request->otp,
            ]);
        }

        return 'no';
    }

    public function workPackageSignatureRequest(Request $request, $id)
    {
        $UserData = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')->select('first_name', 'last_name', 'email', 'phone', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.postal_code', 'freelancer.address')->find(auth('sanctum')->user()->id);

        $data['signRequest'] = SignatureSystemController::signature('signRequestAction', [
            'nationalcode' => $UserData['meli_code'],
            'subject' => 'امضا قرارداد',
            'validtime' => 1440,
            'signimage' => 'true',
            'hashalg' => 'SHA256',
        ]);

        if ($data['signRequest']['errorCode'] === 0) {
            return $data['signRequest'];
        }
    }

    public function workPackageSignatureDigestAction(Request $request, $id)
    {
        $getData = [];

        $getData['userData'] = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')->select('first_name', 'last_name', 'email', 'phone', 'avatar', 'freelancer.meli_code', 'freelancer.cardserialno', 'freelancer.sarbazi', 'freelancer.sarbazi_file', 'freelancer.birthday', 'freelancer.birthday_miladi', 'freelancer.shenasnameh', 'freelancer.mahale_sodoor', 'freelancer.country', 'freelancer.home_phone', 'freelancer.postal_code', 'freelancer.address', 'freelancer.linkedin', 'freelancer.website', 'freelancer.biography')->find(auth('sanctum')->user()->id);
        $workPackage = WorkPackage::select('id', 'title', 'desc', 'package_number', 'wp_final_price', 'wp_final_time', 'status', 'wp_activation_time', 'wp_activation_time', 'signature', 'daily_fine', 'fine_after_day', 'fine_after_price', 'fine_after_negative')->find($id);
        $getData['details'] = $workPackage;
        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'awaiting_signature')->count();
        $getData['details']['time_remaining'] = Jalalian::forge($getData['details']->wp_activation_time)->addDays(7);
        $getData['details']['freelancer'] = Freelancer::where('users_id', auth('sanctum')->user()->id)->with(['users' => fn($query) => $query->select('id', 'first_name', 'last_name')])->first();
        $getData['details']['work_package_activity'] = WorkPackageActivity::where('work_package_id', $id)->get()->map(function ($item) {
            $categories = WorkPackageCategory::where('activity_id', $item->id)->get();

            $tasks = $categories->flatMap(function ($category) {
                return $category->Task;
            });

            return [
                'id' => $item->id,
                'title' => $item->title,
                'work_package_id' => $item->work_package_id,
                'price_percentage' => $item->price_percentage,
                'categories' => $categories,
            ];
        });


        if ($getData['userData']['meli_code'] && $freelancerOffer && $getData['details']->status === 'awaiting_signature') {
            $ContractCheck = FreelancerContract::where('user_id', auth('sanctum')->user()->id)->where('work_package_id', $id)->first();

            if (!$ContractCheck) {
                $Contract = SignatureTextController::SignatureText($getData);

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
                \File::makeDirectory(storage_path() . '/app/contract/no_sign/', $mode = 0755, true, true);
                $pdfName = $getData['userData']['meli_code'] . '-' . date('Y-m-d_H-i-s') . '.pdf';
                $mpdf->Output(storage_path() . '/app/contract/no_sign/' . $pdfName);

                $ContractFile = new FreelancerContract();
                $ContractFile->fill([
                    'contract_no_signed' => $pdfName,
                ]);

                $ContractFile->forceFill([
                    'user_id' => auth('sanctum')->user()->id,
                    'work_package_id' => $id,
                    'status' => 'no_sign',
                ])->save();

            } else {
                $ContractFile = $ContractCheck;
            }


            if ($request->errorCode == 0) {
                $DigestData = [
                    "pdfData" => base64_encode(file_get_contents(storage_path('app/contract/no_sign/' . $ContractFile['contract_no_signed']))),
                    "signerCertificate" => (string)$request['certificate'],
                    "certificationLevel" => 0,
                    "dateTime" => date('c'),
                    "reason" => "امضای قرارداد فریلنسر",
                    "location" => "",
                    "imageDataUrl" => (string)$request['SignImage'],
                    "page" => 1,
                    "lowerLeftX" => 0,
                    "lowerLeftY" => 100,
                    "upperRightX" => 150,
                    "upperRightY" => 0,
                    "signatureFieldName" => (string)$getData['userData']['meli_code'],
                    "hashAlgorithm" => 1
                ];

                $digest = SignatureSystemController::dss('digest', $DigestData);

                if ($digest['statusCode'] === 200) {
                    return [
                        'digestData' => $DigestData,
                        'digest' => $digest,
                        'status' => 200
                    ];
                }
            } else {
                return response('no');
            }
        }
    }

    public function workPackageSignatureProcessAction(Request $request, $id)
    {
        $getData = [];

        $getData['userData'] = Users::join('freelancer', 'users.id', '=', 'freelancer.users_id')
            ->select(
                'first_name', 'last_name', 'email', 'phone', 'avatar',
                'freelancer.meli_code', 'freelancer.cardserialno',
                'freelancer.sarbazi', 'freelancer.sarbazi_file',
                'freelancer.birthday', 'freelancer.birthday_miladi',
                'freelancer.shenasnameh', 'freelancer.mahale_sodoor',
                'freelancer.country', 'freelancer.home_phone',
                'freelancer.postal_code', 'freelancer.address',
                'freelancer.linkedin', 'freelancer.website',
                'freelancer.biography', 'freelancer.certpass'
            )
            ->find(auth('sanctum')->user()->id);

        $workPackage = WorkPackage::select(
            'id', 'title', 'desc', 'package_number',
            'wp_final_price', 'wp_final_time',
            'status', 'wp_activation_time',
            'signature', 'daily_fine',
            'fine_after_day', 'fine_after_price', 'fine_after_negative'
        )->find($id);

        $getData['details'] = $workPackage;

        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)
            ->where('user_id', auth('sanctum')->user()->id)
            ->where('status', 'awaiting_signature')
            ->count();

        if (
            $getData['userData']['meli_code'] &&
            $freelancerOffer &&
            $getData['details']->status === 'awaiting_signature'
        ) {

            $SignProcess = SignatureSystemController::signature('signProcess', [
                'signId' => $request->signId,
                'dataforsign' => json_decode($request->digest, true)['digest']['result'],
                'password' => $getData['userData']['certpass'],
                'otp' => $request->otp,
                'pkcs1support' => 'true',
            ]);

            if ($SignProcess['errorCode'] === 0) {

                $SignPDFData = json_decode($request->digest, true)['digestData'];
                $SignPDFData['signature'] = $SignProcess['signature'];

                $SignPDF = SignatureSystemController::dss('signPDF', $SignPDFData);

                \File::makeDirectory(storage_path('/app/contract/signed/'), 0755, true, true);

                $pdfName = $getData['userData']['meli_code'] . '-' . date('Y-m-d_H-i-s') . '.pdf';
                file_put_contents(
                    storage_path('app/contract/signed/' . $pdfName),
                    base64_decode($SignPDF['result'])
                );

                $FreelancerContract = FreelancerContract::where('work_package_id', $id)
                    ->where('user_id', auth('sanctum')->user()->id)
                    ->firstOrFail();

                // ✅ فقط فیلد مجاز با update
                $FreelancerContract->update([
                    'contract_freelancer_signed' => $pdfName,
                ]);

                // ✅ فیلدهای سیستمی با forceFill
                $FreelancerContract->forceFill([
                    'status' => 'freelancer_signed',
                ])->save();

                if ($workPackage->status === 'awaiting_signature') {

                    $workPackage->forceFill([
                        'status' => 'pre_activate',
                        'signature' => 'yes',
                    ])->save();

                    FreelancerOffer::where('work_package_id', $id)
                        ->where('user_id', auth('sanctum')->user()->id)
                        ->get()
                        ->each(function ($offer) {
                            $offer->forceFill([
                                'status' => 'winner',
                            ])->save();
                        });

                    return [
                        'SignProcess' => $SignProcess,
                        'SignPDF' => $SignPDF,
                        'status' => 200
                    ];
                }

                return response()->json(['status' => 401]);
            }

            return $SignProcess;
        }
    }
}
