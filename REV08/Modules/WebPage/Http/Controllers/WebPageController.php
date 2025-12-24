<?php

namespace Modules\WebPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\WorkPackageManager\Entities\WorkPackage;

class WebPageController extends Controller
{
    public function homePage()
    {
        return response()->json([
            'workPackage' => WorkPackage::get()->take(4),
            'workPackageCount' => WorkPackage::get()->count(),
            'workPackageNewCount' => WorkPackage::where('status', 'new')->get()->count(),
            'workPackageSignatureCount' => WorkPackage::where('status', 'awaiting_signature')->get()->count(),
            'workPackageActiveCount' => WorkPackage::where('status', 'activated')->get()->count(),
            'workPackageClosedCount' => WorkPackage::where('status', 'completed')->get()->count(),
        ]);
    }
}
