<?php

namespace Modules\Freelancer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerRulesContract;
use Modules\Freelancer\Http\Controllers\ContractText\AcceptRulesContractTextController;
use Modules\SignatureSystem\Http\Controllers\SignatureSystemController;

class AcceptRulesController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/accept-rules
    * GET
    * */
    public function get()
    {
        $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->select('accept_rules')->first();
        $Contract = AcceptRulesContractTextController::ContractText();
        return response()->json(['status' => 200, 'getData' => $Freelancer, 'Contract' => $Contract]);
    }

    public function acceptRulesContractSignatureRequest(Request $request)
    {
        $Freelancer = Freelancer::select('id', 'users_id', 'accept_rules', 'meli_code')->where('users_id', auth('sanctum')->user()->id)->first();
        if ($request->accept_rules == 'yes' && $Freelancer->accept_rules != 'yes') {
            $data['signRequest'] = SignatureSystemController::signature('signRequestAction', [
                'nationalcode' => $Freelancer->meli_code,
                'subject' => 'امضا مرامنامه',
                'validtime' => 1440,
                'signimage' => 'true',
                'hashalg' => 'SHA256',
            ]);

            return response()->json($data['signRequest']);
        }
    }

    public function acceptRules(Request $request)
    {
        $ContractCheck = FreelancerRulesContract::where('user_id', auth('sanctum')->user()->id)->first();
        if (!$ContractCheck) {
            $Freelancer = Freelancer::select('id', 'users_id', 'accept_rules', 'meli_code', 'certpass')->where('users_id', auth('sanctum')->user()->id)->first();
            $Contract = AcceptRulesContractTextController::ContractText();
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
            \File::makeDirectory(storage_path() . '/app/rules-contract/no_sign/', $mode = 0755, true, true);
            $pdfName = 'Rules-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
            $mpdf->Output(storage_path() . '/app/rules-contract/no_sign/' . $pdfName);

            $ContractFile = FreelancerRulesContract::create([
                'contract_no_signed' => $pdfName,
            ]);

            $ContractFile->forceFill([
                'user_id' => auth('sanctum')->user()->id,
                'status' => 'no_sign',
            ])->save();
        } else {
            $Freelancer = Freelancer::select('id', 'users_id', 'accept_rules', 'meli_code', 'certpass')->where('users_id', auth('sanctum')->user()->id)->first();
            $ContractFile = $ContractCheck;
        }

        $DigestData = [
            "pdfData" => base64_encode(file_get_contents(storage_path('app/rules-contract/no_sign/' . $ContractFile['contract_no_signed']))),
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

                \File::makeDirectory(storage_path() . '/app/rules-contract/signed/', $mode = 0755, true, true);
                $pdfName = 'Rules-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
                $pdf = fopen(storage_path('app/rules-contract/signed/' . $pdfName), 'w');
                fwrite($pdf, base64_decode($SignPDF['result']));
                fclose($pdf);

                $Freelancer->forceFill([
                    'accept_rules' => 'yes',
                ])->save();

                $ContractFile->update([
                    'contract_freelancer_signed' => $pdfName,
                ]);

                $ContractFile->forceFill([
                    'status' => 'freelancer_signed',
                ])->save();
                
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 200]);
            }
        }
    }
}
