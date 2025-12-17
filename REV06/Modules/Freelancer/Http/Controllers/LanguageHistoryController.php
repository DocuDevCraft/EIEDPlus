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
        $data = $request->validated();
        $data['users_id'] = auth('sanctum')->id();

        if ($request->hasFile('language_file')) {
            $data['language_file'] = FileLibraryController::upload(
                $request->file('language_file'),
                'file',
                'freelancer/language-history',
                'freelancer'
            );
        }

        $history = FreelancerLanguage::create($data);

        return response()->json([
            'status' => 200,
            'data' => $history
        ]);
    }
    

    /*
    * Update Data
    * Route: /api/my-account/language-history
    * PUT
    * */
    public function update(LanguageHistoryRequest $request)
    {
        $history = FreelancerLanguage::findOrFail($request->id);
        $data = $request->validated();

        if ($request->hasFile('language_file')) {
            $data['language_file'] = FileLibraryController::upload(
                $request->file('language_file'),
                'file',
                'freelancer/language-history',
                'freelancer'
            );
        }

        $history->update($data);

        return response()->json([
            'status' => 200,
            'data' => $history
        ]);
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
