<?php

namespace Modules\Freelancer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerHourlyContract;
use Modules\Freelancer\Http\Controllers\ContractText\HourlyContractTextController;
use Modules\SignatureSystem\Http\Controllers\SignatureSystemController;

class HourlyContractController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/hourly-contract
    * GET
    * */
    public function get()
    {
        $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->select(['hourly_contract', 'users_id', 'meli_code', 'shenasnameh', 'mahale_sodoor', 'address', 'postal_code', 'shaba'])->first();

        $Freelancer['hourly_contract'] = $Freelancer['hourly_contract'] == 'yes' ? 'yes' : 'no';
        $Contract = HourlyContractTextController::ContractText($Freelancer, 400000);

        return response()->json(['status' => 200, 'getData' => $Freelancer, 'Contract' => $Contract]);
    }

    public function HourlyContractSignatureRequest(Request $request)
    {
        $Freelancer = Freelancer::select('id', 'users_id', 'hourly_contract', 'meli_code')->where('users_id', auth('sanctum')->user()->id)->first();
        if ($request->hourly_contract == 'yes' && $Freelancer->hourly_contract != 'yes') {
            $data['signRequest'] = SignatureSystemController::signature('signRequestAction', [
                'nationalcode' => $Freelancer->meli_code,
                'subject' => 'امضا قرارداد نفر/ساعت',
                'validtime' => 1440,
                'signimage' => 'true',
                'hashalg' => 'SHA256',
            ]);

            return response()->json($data['signRequest']);
        }
    }

    public function HourlyContract(Request $request)
    {
        $ContractCheck = FreelancerHourlyContract::where('user_id', auth('sanctum')->user()->id)->first();
        if (!$ContractCheck) {
            $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->select(['hourly_contract', 'users_id', 'meli_code', 'shenasnameh', 'mahale_sodoor', 'address', 'postal_code', 'shaba'])->first();
            $Contract = HourlyContractTextController::ContractText($Freelancer, 400000);
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_bottom' => 45,
                'fontDir' => array_merge($fontDirs, [
                    module_path('Dashboard', '/Resources/assets/admin/pdf-fonts'),
                ]),
                'fontdata' => $fontData + [
                        'mitra' => [
                            'R' => 'IRMitra.ttf',
                            'B' => 'IRMitra-Bold.ttf',
                            'useOTL' => 0xFF,
                            'useKashida' => 75,
                        ],
                        'titr' => [
                            'B' => 'IRTitr.ttf',
                            'useOTL' => 0xFF,
                            'useKashida' => 75,
                        ],
                    ],
                'default_font' => 'mitra',
                'tempDir' => storage_path('temp'),
            ]);
            $footerHTML = '
            <div dir="rtl" style="font-size: 11pt;">
                <table width="100%" cellpadding="4" cellspacing="0" style="border:1px solid #000; border-collapse: collapse; text-align:right;">
                    <tr>
                        <td colspan="3" style="font-weight:bold;">قرارداد ارائه خدمات کارشناسی</td>
                        <td style="width: 25%; border-right:1px solid #000; font-weight:bold;">
                            <div>شماره قرارداد:</div>
                            <div>EIED-CON-GEN-04-SE-....</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid #000;border-left:1px solid #000;">کارفرما: <div>شرکت طراحی و مهندسی صنایع انرژی</div></td>
                        <td style="border-top:1px solid #000;border-left:1px solid #000;text-align: center">واحد مرتبط: <div>فناوری اطلاعات و ارتباطات</div></td>
                        <td style="border-top:1px solid #000;border-left:1px solid #000;text-align: center">شماره صفحه: <div>{PAGENO}</div></td>
                        <td style="border-top:1px solid #000;width: 30%">کارشناس: <div>........................</div></td>
                    </tr>
                </table>

                <div style="padding: 0 20px">
                    <table style="margin-top:5px; text-align:right; font-size:10pt; width: 100%">
                        <tr>
                            <td>کارفرما : </td>
                            <td style="text-align: left">امضاء و اثر انگشت کارشناس:</td>
                        </tr>
                    </table>
                </div>
            </div>
            ';
//        $stylesheet = file_get_contents(module_path('Dashboard', '/Resources/assets/admin/css/pdf-style.min.css'));
//        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->SetHTMLFooter($footerHTML);
            $mpdf->WriteHTML($Contract);
            \File::makeDirectory(storage_path() . '/app/freelancer-hourly-contract/no_sign/', $mode = 0755, true, true);
            $pdfName = 'HourlyContract-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
            $mpdf->Output(storage_path() . '/app/freelancer-hourly-contract/no_sign/' . $pdfName);

            $ContractFile = FreelancerHourlyContract::create([
                'user_id' => auth('sanctum')->user()->id,
                'contract_no_signed' => $pdfName,
                'status' => 'no_sign',
            ]);
        } else {
            $Freelancer = Freelancer::select('id', 'users_id', 'hourly_contract', 'meli_code', 'certpass')->where('users_id', auth('sanctum')->user()->id)->first();
            $ContractFile = $ContractCheck;
        }

        $DigestData = [
            "pdfData" => base64_encode(file_get_contents(storage_path('app/freelancer-hourly-contract/no_sign/' . $ContractFile['contract_no_signed']))),
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

                \File::makeDirectory(storage_path() . '/app/freelancer-hourly-contract/signed/', $mode = 0755, true, true);
                $pdfName = 'HourlyContract-' . $Freelancer->meli_code . '-' . date('Y-m-d_H-i-s') . '.pdf';
                $pdf = fopen(storage_path('app/freelancer-hourly-contract/signed/' . $pdfName), 'w');
                fwrite($pdf, base64_decode($SignPDF['result']));
                fclose($pdf);

                $Freelancer->update(['hourly_contract' => 'yes']);
                $ContractFile->update([
                    'contract_freelancer_signed' => $pdfName,
                    'status' => 'freelancer_signed',
                ]);

                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 200]);
            }
        }
    }
}
