<?php

namespace Modules\FreelancerOffer\Http\Controllers\OfferContract;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\Freelancer;
use Modules\FreelancerOffer\Entities\FreelancerOfferContract;
use Modules\FreelancerOffer\Http\Controllers\OfferContract\ContractText\OfferContractTextController;
use Modules\FreelancerOffer\Http\Requests\FreelancerOfferRequest;
use Modules\SignatureSystem\Http\Controllers\SignatureSystemController;
use Modules\WorkPackageManager\Entities\WorkPackage;

class OfferContractController extends Controller
{
    public function offerContractSignatureRequest(FreelancerOfferRequest $request)
    {
        $WorkPackage = WorkPackage::select(['id', 'title', 'unique_id', 'work_package_type', 'published_at', 'offer_time'])->find($request->workPackageID);
        $ShowPacket = Carbon::createFromFormat('Y-m-d H:i:s', $WorkPackage->published_at)->addDay($WorkPackage->offer_time)->format('Y-m-d H:i:s') < Carbon::now()->format('Y-m-d H:i:s');
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
        } else {
            $request->price = ($request->time * 8) * 400000;
        }
        if ($WorkPackage->work_package_type == 'public') {
            $Freelancer = Freelancer::with('users:id,first_name,last_name')->select('id', 'users_id', 'meli_code')->where('users_id', auth('sanctum')->user()->id)->first();
            $data['signRequest'] = SignatureSystemController::signature('signRequestAction', [
                'nationalcode' => $Freelancer->meli_code,
                'subject' => 'امضا پیشنهاد قیمت',
                'validtime' => 1440,
                'signimage' => 'true',
                'hashalg' => 'SHA256',
            ]);

            $price = [
                'number' => number_format($request->price . 0),
                'numberString' => $request->priceString,
            ];

            $Contract = OfferContractTextController::ContractText($WorkPackage, $Freelancer, $price, $request->time);

            return response()->json(['signRequest' => $data['signRequest'], 'contract' => $Contract, 'freelancer' => $Freelancer, 'work_package_type' => $WorkPackage->work_package_type]);
        } else {
            return response()->json(['work_package_type' => $WorkPackage->work_package_type]);
        }
    }

    public static function signatureProcess($request, $id)
    {
        $Freelancer = Freelancer::with('users:id,first_name,last_name')->select('id', 'users_id', 'meli_code', 'certpass')->where('users_id', auth('sanctum')->user()->id)->first();
        $WorkPackage = WorkPackage::select('id', 'title', 'unique_id')->find($id)->first();

        $price = [
            'number' => number_format($request->price . 0),
            'numberString' => $request->priceString,
        ];

        $Contract = OfferContractTextController::ContractText($WorkPackage, $Freelancer, $price, $request->time);
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
        \File::makeDirectory(storage_path() . '/app/offer-contract/no_sign/', $mode = 0755, true, true);
        $pdfName = 'Offer-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
        $mpdf->Output(storage_path() . '/app/offer-contract/no_sign/' . $pdfName);

        $ContractFile = FreelancerOfferContract::create([
            'contract_no_signed' => $pdfName,
        ]);

        $ContractFile->forceFill([
            'user_id' => auth('sanctum')->user()->id,
            'work_package_id' => $id,
            'status' => 'no_sign',
        ])->save();

        $DigestData = [
            "pdfData" => base64_encode(file_get_contents(storage_path('app/offer-contract/no_sign/' . $ContractFile['contract_no_signed']))),
            "signerCertificate" => (string)$request->certificate,
            "certificationLevel" => 0,
            "dateTime" => date('c'),
            "reason" => "امضای مرامنامه",
            "location" => "",
            "imageDataUrl" => (string)$request->SignImage,
            "page" => 1,
            "lowerLeftX" => 0,
            "lowerLeftY" => 100,
            "upperRightX" => 150,
            "upperRightY" => 0,
            "signatureFieldName" => (string)$Freelancer->meli_code,
            "hashAlgorithm" => 1
        ];

        $digest = SignatureSystemController::dss('digest', $DigestData);
        if ($digest['statusCode'] === 200) {
            $SignProcess = SignatureSystemController::signature('signProcess', [
                'signId' => $request->signId,
                'dataforsign' => $digest['result'],
                'password' => $Freelancer->certpass,
                'otp' => $request->otp,
                'pkcs1support' => 'true',
            ]);

            if ($SignProcess['errorCode'] === 0) {
                $SignPDFData = $DigestData;
                $SignPDFData['signature'] = $SignProcess['signature'];
                $SignPDF = SignatureSystemController::dss('signPDF', $SignPDFData);

                \File::makeDirectory(storage_path() . '/app/offer-contract/signed/', $mode = 0755, true, true);
                $pdfName = 'Offer-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
                $pdf = fopen(storage_path('app/offer-contract/signed/' . $pdfName), 'w');
                fwrite($pdf, base64_decode($SignPDF['result']));
                fclose($pdf);

                $ContractFile->update([
                    'contract_freelancer_signed' => $pdfName,
                ]);

                $ContractFile->forceFill([
                    'status' => 'freelancer_signed',
                ])->save();
                return true;

            } else {
                return response()->json([$SignProcess]);
            }

            return response()->json([$digest]);
        }
    }
}
