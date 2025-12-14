<?php

namespace Modules\Freelancer\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\FreelancerLanguage;
use Modules\Freelancer\Http\Requests\LanguageHistoryRequest;

class LanguageHistoryController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/language-history
    * GET
    * */
    public function get()
    {
        $Data = FreelancerLanguage::where('users_id', auth('sanctum')->user()->id)->get();

        foreach ($Data as $item) {
            if ($item->language_file) {
                $item['file_name'] = HomeController::GetFileName($item->language_file);
            }
        }

        return response()->json(['status' => 200, 'getData' => $Data]);
    }

    /*
    * Store Data
    * Route: /api/my-account/language-history
    * POST
    * */
    public function store(LanguageHistoryRequest $request)
    {
        $Data = $request->all();
        $Data['users_id'] = auth('sanctum')->user()->id;

        if ($request->file('language_file')) {
            $Data['language_file'] = FileLibraryController::upload($request->file('language_file'), 'file', 'freelancer/language-history', 'freelancer');
        }

        if (FreelancerLanguage::create($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Update Data
    * Route: /api/my-account/language-history
    * PUT
    * */
    public function update(LanguageHistoryRequest $request)
    {


        $History = FreelancerLanguage::find($request->id);
        $Data = $request->all();

        if ($request->file('language_file')) {
            $Data['language_file'] = FileLibraryController::upload($request->file('language_file'), 'file', 'freelancer/language-history', 'freelancer');
        } else {
            $Data['language_file'] = $History->language_file;
        }

        if ($History->update($Data)) {
            return response()->json(['status' => 200, 'data' => $request->all()]);
        } else {
            return response()->json(['status' => 401]);
        }
    }

    /*
    * Delete Data
    * Route: /api/my-account/language-history
    * DELETE
    * */
    public function delete(Request $request)
    {
        $HistoryItem = FreelancerLanguage::find($request->id);

        if (auth('sanctum')->user()->id === $HistoryItem->users_id) {
            $HistoryItem->delete();
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 401]);
        }
    }
}
