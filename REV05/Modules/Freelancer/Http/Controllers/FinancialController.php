<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\SectionManager\Http\Controllers\SectionAPIHandlerController;

class FinancialController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/financial-information
    * GET
    * */
    public function get()
    {
        $Data = Freelancer::where('users_id', auth('sanctum')->user()->id)->select('tax', 'tax_value', 'tax_file', 'shaba')->first();
        if ($Data->tax_file) {
            $Data['taxFileName'] = HomeController::GetFileName($Data->tax_file);
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/financial-information
    * POST
    * */
    public function update(Request $request)
    {
        $Data = $request->all();
        $Freelancer = Freelancer::where('users_id', auth('sanctum')->user()->id)->first();

        if ($request->file('tax_file')) {
            $Data['tax_file'] = FileLibraryController::upload($request->file('tax_file'), 'file', 'freelancer/tax', 'freelancer');
        }

        $Freelancer->update($Data);

        return response()->json($request->all());
    }
}
