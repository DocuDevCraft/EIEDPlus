<?php

namespace Modules\Contract\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Modules\Freelancer\Entities\FreelancerContract;

class ContractAPIController extends Controller
{
    public function ContractList()
    {
        $Payment = FreelancerContract::where('user_id', auth('sanctum')->user()->id)->where('status', '!=', 'no_sign')->orderBy('created_at', 'desc')->with('workPackage')->paginate(20)
            ->through(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->workpackage->title,
                    'status' => $item->status,
                    'status_handel' => $this->statusTranslate($item->status),
                    'contract_file' => $item->contract_employer_signed,
                    'created_at' => $item->created_at,
                ];
            });
        return response()->json(['getData' => $Payment]);
    }


    public function getSignedDownloadUrl($file)
    {
        $url = URL::temporarySignedRoute(
            'freelancer.contract.download',
            now()->addMinutes(5),
            ['file' => $file]
        );
        return response()->json(['url' => $url]);
    }

    public function download(Request $request, $file)
    {
//        if (!$request->hasValidSignature()) {
//            abort(403, 'لینک منقضی یا نامعتبر است.');
//        }

        $filePath = storage_path("app/contract/employer-signed/{$file}");
        if (!file_exists($filePath)) {
            abort(404, 'فایل یافت نشد.');
        }

        return response()->download($filePath, $file, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function statusTranslate($status)
    {
        switch ($status) {
            case 'freelancer_signed':
                return [
                    'title' => 'در انتظار امضا کارفرما',
                    'color' => '#e68e19'
                ];
            case 'employer_signed':
                return [
                    'title' => 'امضا شده',
                    'color' => '#069c5a'
                ];
        }
    }
}
