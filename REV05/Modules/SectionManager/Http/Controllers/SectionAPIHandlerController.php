<?php

namespace Modules\SectionManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SectionManager\Entities\Division;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;

class SectionAPIHandlerController extends Controller
{
    public function getList($level, $id)
    {
        switch ($level) {
            case 'section' :
                return response()->json(['status' => 200, 'data' => Section::get()]);
            case 'subsection' :
                return response()->json(['status' => 200, 'data' => Subsection::where('section_id', $id)->get()]);
            case 'division' :
                return response()->json(['status' => 200, 'data' => Division::where('subsection_id', $id)->get()]);
        }
    }

    public static function getName($level, $id)
    {
        switch ($level) {
            case 'section' :
                return Section::find($id)->title;
            case 'subsection' :
                return Subsection::find($id)->title;
            case 'division' :
                return Division::find($id)->title;
        }
    }
}
