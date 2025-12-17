<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;

class FinancialController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/financial-information
    * GET
    * */
    public function get()
    {
        $data = Freelancer::where('users_id', auth('sanctum')->user()->id)
            ->select('tax', 'tax_value', 'tax_file', 'shaba')
            ->first();

        if ($data && $data->tax_file) {
            $data->taxFileName = HomeController::GetFileName($data->tax_file);
        }

        return response()->json([
            'status' => 200,
            'getData' => $data
        ]);
    }

    /*
    * Store Data
    * Route: /api/my-account/financial-information
    * POST
    * */
    public function update(Request $request)
    {
        $data = $request->validate([
            'tax_file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        $freelancer = Freelancer::where(
            'users_id',
            auth('sanctum')->user()->id
        )->firstOrFail();

        if ($request->hasFile('tax_file')) {
            $data['tax_file'] = FileLibraryController::upload(
                $request->file('tax_file'),
                'file',
                'freelancer/tax',
                'freelancer'
            );
        }

        $freelancer->update($data);

        return response()->json($freelancer);
    }
}
