<?php

namespace Modules\SupportSystem\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FileLibrary\Entities\FileLibrary;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Notification\Http\Controllers\NotificationController;
use Modules\SupportSystem\Entities\SupportDepartments;
use Modules\SupportSystem\Entities\SupportSystem;
use Modules\SupportSystem\Entities\Ticket;
use Modules\SupportSystem\Http\Requests\SupportSystemPanelRequest;
use Modules\SupportSystem\Http\Requests\SupportSystemRequest;
use Modules\SupportSystem\Http\Requests\TicketRequest;
use Modules\Users\Entities\Users;

class SupportSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (isset($_GET['search']) && $_GET['search']) {
            $Support = SupportSystem::where('id', 'like', '%' . $_GET['search'] . '%')->orderBy('updated_at', 'desc')->paginate(20);
        } else {
            $Support = SupportSystem::orderBy('updated_at', 'desc')->paginate(20);
        }

        return view('supportsystem::tickets.index', compact('Support'));
    }

    /* Get List API */
    public function getTaxonomy()
    {
        $Departments = SupportDepartments::orderBy('title', 'desc')->get()->all();

        $Data = [
            'Departments' => $Departments,
        ];

        return response()->json($Data);
    }

    public function ShowOwnerTickets(Request $request)
    {
        $Support = SupportSystem::where('uid', \auth('sanctum')->user()->id)->where('status', 'like', "%{$request->status}%")->orderBy('updated_at', 'desc')->get()->all();

        if (isset($Support)) {
            foreach ($Support as $key => $item) {
                $Support[$key]['status'] = HomeController::ConvertSupportStatus($item->status);
                $Support[$key]['priority'] = HomeController::ConvertSupportPriority($item->priority);
                $Departeman = SupportDepartments::find($item->department);
                $Support[$key]['departeman_name'] = $Departeman->title;

                if ($item->attachments) {
                    $AttachmentsItems = [];
                    foreach (json_decode($item->attachments, true) as $key2 => $itemAttachment) {
                        $AttachmentsData = FileLibrary::find($itemAttachment);
                        array_push($AttachmentsItems, $AttachmentsData);
                        $Support[$key]['attachments_data'] = $AttachmentsItems;
                    }
                }
            }
        }
        return response()->json($Support);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $SupportDepartments = SupportDepartments::orderBy('title', 'desc')->get()->all();
        $Users = Users::get()->all();

        $Data = [
            'SupportDepartments',
            'Users'
        ];

        return view('supportsystem::tickets.create', compact($Data));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SupportSystemRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('attachments')) {
            $files = [];
            foreach ($request->file('attachments') as $file) {
                $files[] = FileLibraryController::upload(
                    $file,
                    'file',
                    'support/files',
                    'support'
                );
            }
            $data['attachments'] = json_encode($files);
        }

        $support = SupportSystem::create($data);

        // uid چون fillable نیست
        $support->forceFill([
            'uid' => $request->uid,
        ])->save();

        return redirect('dashboard/support')->with('notification', [
            'class' => 'success',
            'message' => 'تیکت با موفقیت ایجاد شد.'
        ]);
    }

    public function store_support(SupportSystemPanelRequest $request)
    {
        $userId = Auth::user()->parent_id ?? Auth::id();

        $data = $request->validated();

        if ($request->hasFile('attachments')) {
            $data['attachments'] = json_encode([
                FileLibraryController::upload(
                    $request->file('attachments'),
                    'file',
                    'support/files',
                    'support'
                )
            ]);
        }

        $support = SupportSystem::create($data);

        $support->forceFill([
            'uid' => $userId,
            'created_by' => $userId,
            'status' => 'pending',
        ])->save();

        return response()->json([
            'status' => $support ? 200 : 500,
            'data' => $support
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $Support = SupportSystem::where('id', $id)
            ->select('id', 'uid', 'title', 'ticket_content', 'department', 'priority', 'status', 'created_at', 'updated_at')->with('department_tbl')->first();

        $Tickets = Ticket::where('support_id', $Support->id)->orderBy('created_at', 'desc')->get();

        $Support['status'] = HomeController::ConvertSupportStatus($Support->status);
        $Support['department'] = $Support->department_tbl->title;
        $Support['priority'] = HomeController::ConvertSupportPriority($Support->priority);
        $Support['tickets'] = $Tickets;

        $TicketsComplete = [];
        $TicketsExperts = [];
        /* Fetch Tickets */
        foreach ($Support['tickets'] as $key => $item) {
            $attachmentPath = [];
            if ($item->attachments && json_decode($item->attachments)) {
                foreach (json_decode($item->attachments) as $attachmentKey => $attachmentItem) {
                    array_push($attachmentPath, HomeController::GetFilePath($attachmentItem));
                }
            }
            $TicketsComplete[$key] = [
                'id' => $item->id,
                'uid' => $item->uid,
                'name' => HomeController::GetUserData($item->uid),
                'avatar' => HomeController::GetAvatar('35', '70', HomeController::GetUserData($item->uid, 'avatar')),
                'replay_text' => $item->replay_text,
                'attachments' => $attachmentPath,
                'created_at' => $item->created_at,
            ];

            $Role = HomeController::GetUserData($item->uid, 'role');

            if ($Role !== 'employer' && $Role !== 'job_seeker' && $Role !== 'freelancer' && $Role !== 'user') {
                array_push($TicketsExperts, $item->uid);
            }
        }

        $Support['tickets'] = $TicketsComplete;
        $Support['experts'] = array_unique($TicketsExperts);
        $Support['experts'] = array_values($Support['experts']);

        /* Fetch Experts */
        $TicketsExperts = [];
        foreach ($Support['experts'] as $key => $item) {
            $TicketsExperts[$key] = [
                'id' => $item,
                'name' => HomeController::GetUserData($item),
                'avatar' => HomeController::GetAvatar('35', '70', HomeController::GetUserData($item, 'avatar')),
            ];
        }

        $Support['experts'] = $TicketsExperts;

        unset($Support['department_tbl']);

        return response()->json($Support);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Support = SupportSystem::find($id);
        $SupportDepartments = SupportDepartments::get()->all();
        $Users = Users::get()->all();

        $Attachments = [];

        if ($Support->attachments) {
            if (count(json_decode($Support->attachments))) {
                foreach (json_decode($Support->attachments) as $item) {
                    $Attachment = FileLibrary::find($item);

                    array_push($Attachments,
                        [
                            'id' => $Attachment->id,
                            'name' => $Attachment->org_name,
                            'path' => 'storage/' . $Attachment->path . $Attachment->file_name,
                        ]);
                }
            }
        }

        $Data = [
            'Support',
            'SupportDepartments',
            'Users',
            'Attachments',
        ];

        return view('supportsystem::tickets.edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SupportSystemRequest $request, $id)
    {
        $support = SupportSystem::findOrFail($id);

        $data = $request->validated();

        // attachments (old + new)
        $attachments = $request->current_attachment ?? [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = FileLibraryController::upload(
                    $file,
                    'file',
                    'support/files',
                    'support'
                );
            }
        }

        $data['attachments'] = json_encode($attachments);

        // فقط فیلدهای مجاز
        $support->update($data);

        // فیلد سیستمی
        $support->forceFill([
            'uid' => $request->uid,
        ])->save();

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'اطلاعات بروزرسانی شد'
        ]);
    }

    public function tickets($id)
    {
        $Support = SupportSystem::find($id);
        $Tickets = Ticket::where('support_id', $id)->orderBy('updated_at', 'desc')->get()->all();
        $Attachments = [];

        if ($Support->attachments) {
            if (count(json_decode($Support->attachments))) {
                foreach (json_decode($Support->attachments) as $item) {
                    $Attachment = FileLibrary::find($item);

                    array_push($Attachments,
                        [
                            'id' => $Attachment->id,
                            'name' => $Attachment->org_name,
                            'path' => 'storage/' . $Attachment->path . $Attachment->file_name,
                        ]);
                }
            }
        }

        $Data = [
            'Support',
            'Tickets',
            'Attachments',
        ];

        return view('supportsystem::tickets.tickets', compact($Data));
    }

    public function tickets_store(TicketRequest $request, $id)
    {
        $support = SupportSystem::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('attachments')) {
            $files = [];
            foreach ($request->file('attachments') as $file) {
                $files[] = FileLibraryController::upload(
                    $file,
                    'file',
                    'support/files',
                    'support'
                );
            }
            $data['attachments'] = json_encode($files);
        }

        $ticket = Ticket::create($data);

        // فیلدهای سیستمی Ticket
        $ticket->forceFill([
            'support_id' => $id,
            'uid' => auth()->id(),
        ])->save();

        // وضعیت تیکت
        $support->forceFill([
            'status' => 'replied',
        ])->save();

        NotificationController::store(
            'تیکت شما پاسخ داده شد',
            HomeController::TruncateString($request->replay_text, 50, 50),
            $support->uid
        );

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'پاسخ ارسال شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        foreach ($request->delete_item as $key => $item) {
            $Ticket = Ticket::where('support_id', $key)->get()->all();
            foreach ($Ticket as $item_ticket) {
                Ticket::find($item_ticket->id)->delete();
            }
            SupportSystem::where('id', $key)->delete();
        }

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'تیکت های مورد نظر حذف شد'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy_ticket($id)
    {
        Ticket::find($id)->delete();
        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'تیکت مورد نظر حذف شد'
        ]);
    }
}
