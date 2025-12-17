<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\MailController;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CommentSystem\Entities\CommentSystem;
use Modules\ConsultationRequest\Entities\ConsultationRequest;
use Modules\Notification\Entities\Notification;
use Modules\Project\Entities\Project;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard::pages.dashboard.index');
    }
}
