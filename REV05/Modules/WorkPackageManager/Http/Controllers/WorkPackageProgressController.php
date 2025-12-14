<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\Payment\Entities\Payment;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageTaskManager\Entities\TaskChat;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageProgress;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class WorkPackageProgressController extends Controller
{
    public function show($id)
    {
        $Activity = WorkPackageActivity::where('work_package_id', $id)->get();

        /* Get Category */
        if ($Activity) {
            foreach ($Activity as $keyActivity => $itemActivity) {
                $category = null;
                $category = WorkPackageCategory::where('activity_id', $itemActivity->id)->get();
                if (isset($category) && count($category)) {
                    $Activity[$keyActivity]['category'] = $category;

                    foreach ($Activity[$keyActivity]['category'] as $keyCategory => $itemCategory) {
                        $task = null;
                        $task = WorkPackageTask::where('category_id', $itemCategory->id)->get();
                        if (isset($task) && count($task)) {
                            $Activity[$keyActivity]['category'][$keyCategory]['task'] = $task;
                            foreach ($task as $keyTask => $itemTask) {
                                $itemTask['chat'] = [
                                    'all' => TaskChat::where('task_id', $itemTask->id)->where('users_id', '!=', auth()->user()->id)->count(),
                                    'new' => TaskChat::where('task_id', $itemTask->id)->where('status', 'new')->where('users_id', '!=', auth()->user()->id)->count()
                                ];
                                $itemTask['progress'] = WorkPackageProgress::where('task_id', $itemTask->id)->where('status', 'new')->count();
                            }
                        }
                    }
                }
            }
        }

        $Data = [
            'Activity'
        ];

        return view('workpackagemanager::progress.index', compact($Data));
    }

    public function taskShow($id)
    {
        $Task = WorkPackageTask::find($id);
        $TaskProgress = WorkPackageProgress::where('task_id', $id)->get();

        foreach ($TaskProgress as $itemTaskProgress) {
            $itemTaskProgress['attachment'] = [
                'name' => HomeController::GetFileName($itemTaskProgress->attachment),
                'path' => HomeController::GetFilePath($itemTaskProgress->attachment)
            ];
        }

        $ID = $id;
        $chatList = TaskChat::where('task_id', $id)->get();

        if ($chatList) {
            foreach ($chatList as $key => $item) {
                $item->update(['status' => 'viewed']);
                if ($item->attachment) {
                    $item['attachment'] = [
                        'file_name' => HomeController::GetFileName($item->attachment),
                        'path' => HomeController::GetFilePath($item->attachment)
                    ];
                }
            }
        }

        $Data = [
            'Task',
            'TaskProgress',
            'ID',
            'chatList'
        ];

        return view('workpackagemanager::progress.show', compact($Data));
    }

    public function taskUpdate(Request $request, $id)
    {
        $WorkPackageTask = WorkPackageTask::find($id);

        if ($WorkPackageTask->update(['status' => $request->status])) {
            $TaskCount = WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->count();
            $TaskCompleted = WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->where(function ($query) {
                $query->where('status', 'completed')
                    ->orWhere('status', 'stop');
            })->count();
            $WorkPackageCategory = WorkPackageCategory::find($WorkPackageTask->category_id);
            $Freelancer = FreelancerOffer::where('work_package_id', $WorkPackageTask->work_package_id)->where('status', 'winner')->first();

            if ($WorkPackageTask->status === 'stop' || $WorkPackageTask->status === 'completed') {
                if ($Freelancer) {
                    if (($TaskCount === $TaskCompleted)) {
                        $WorkPackagePrice = WorkPackage::select('wp_final_price')->find($WorkPackageTask->work_package_id)->wp_final_price;
                        $ActivityPercentage = WorkPackageActivity::select('price_percentage')->find($WorkPackageCategory->activity_id)->price_percentage;
                        $CategoryPercentage = $WorkPackageCategory->price_percentage;
                        $Amount = 0;
                        foreach (WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->where('status', 'completed')->get() as $item) {
                            $Amount += (($WorkPackagePrice * ($ActivityPercentage / 100)) * ($CategoryPercentage / 100)) * ($item->price_percentage / 100);
                        }

                        $WorkPackageCategory->update(['status' => 'completed']);
                        if ($WorkPackageCategory->price_percentage > 0) {
                            $Payment = Payment::where('users_id', $Freelancer->user_id)->where('category_id', $WorkPackageTask->category_id)->first();
                            if ($Payment) {
                                $Payment->update(['status' => 'pending']);
                            } else {
                                if ($Amount > 0) {
                                    Payment::create([
                                        'users_id' => $Freelancer->user_id,
                                        'work_package_id' => $WorkPackageTask->work_package_id,
                                        'activity_id' => $WorkPackageCategory->activity_id,
                                        'category_id' => $WorkPackageTask->category_id,
                                        'amount' => $Amount,
                                        'status' => 'pending',
                                    ]);
                                }
                            }
                        }
                    }
                }
            } else {
                if ($Freelancer) {
                    $WorkPackageCategory->update(['status' => null]);
                    $Payment = Payment::where('users_id', $Freelancer->user_id)->where('category_id', $WorkPackageTask->category_id)->first();
                    if ($Payment) {
                        $Payment->update(['status' => 'stop']);
                    }
                }
            }

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'اطلاعات بروزرسانی شد'
            ]);
        }
    }

    public function progressUpdate(Request $request, $id)
    {
        $Progress = WorkPackageProgress::find($id);

        if ($Progress->update(['status' => $request->status])) {
            if (WorkPackageProgress::where('task_id', $Progress->task_id)->where('status', 'completed')->count()) {
                $WorkPackageTask = WorkPackageTask::find($Progress->task_id);
                $WorkPackageTask->update(['status' => 'completed']);


                $WorkPackageCategory = WorkPackageCategory::find($WorkPackageTask->category_id);

                $TaskCount = WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->count();
                $TaskCompleted = WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->where(function ($query) {
                    $query->where('status', 'completed')
                        ->orWhere('status', 'stop');
                })->count();

                if (($TaskCount === $TaskCompleted)) {
                    $WorkPackageCategory->update([
                        'status' => 'completed',
                        'completed_at' => Carbon::now(),
                    ]);
                    $WorkPackage = WorkPackage::select(['wp_final_price', 'title', 'uid'])->find($WorkPackageTask->work_package_id);
                    $Payment = Payment::where('users_id', $Progress->user_id)->where('category_id', $WorkPackageTask->category_id)->first();

                    /* Active Next Stage */
                    $NextStage = WorkPackageCategory::where('activity_id', $WorkPackageCategory->activity_id)->where('stage', $WorkPackageCategory->stage + 1)->first();
                    if ($NextStage) {
                        $NextStage->update(['activation_at' => Carbon::now()]);
                    }

                    if ($WorkPackageCategory->price_percentage > 0) {
                        $WorkPackagePrice = $WorkPackage->wp_final_price;
                        $ActivityPercentage = WorkPackageActivity::select('price_percentage')->find($WorkPackageCategory->activity_id)->price_percentage;
                        $CategoryPercentage = $WorkPackageCategory->price_percentage;

                        $Amount = 0;
                        foreach (WorkPackageTask::where('category_id', $WorkPackageTask->category_id)->where('status', 'completed')->get() as $item) {
                            $Amount += (($WorkPackagePrice * ($ActivityPercentage / 100)) * ($CategoryPercentage / 100)) * ($item->price_percentage / 100);
                        }

                        if ($Payment) {
                            $Payment->update(['status' => 'pending']);
                        } else {
                            if ($Amount > 0) {
                                $Payment = Payment::create([
                                    'users_id' => $Progress->user_id,
                                    'work_package_id' => $WorkPackageTask->work_package_id,
                                    'activity_id' => $WorkPackageCategory->activity_id,
                                    'category_id' => $WorkPackageTask->category_id,
                                    'amount' => $Amount,
                                    'status' => 'pending',
                                ]);
                            }
                        }

                        SmsHandlerController::Send([$Payment->Users->phone], "تبریک، مرحله «{$WorkPackageCategory->title}» از بسته کاری «{$WorkPackage->title}» تایید شد. لطفا به بخش صورتحساب ها رفته و صورتحساب ایجاد شده را پرینت و ارسال نمایید.");

                    } else {
                        SmsHandlerController::Send([$Payment->Users->phone], " تبریک، مرحله «{$WorkPackageCategory->title}» از بسته کاری «{$WorkPackage->title}» تایید شد.");
                    }
                }
            } else {
                $WorkPackageTask = WorkPackageTask::find($Progress->task_id);
                $WorkPackageTask->update(['status' => 'rejected']);
                $WorkPackageCategory = WorkPackageCategory::find($WorkPackageTask->category_id);
                $WorkPackageCategory->update(['status' => null]);
                if ($WorkPackageCategory->price_percentage > 0) {
                    $Payment = Payment::where('users_id', $Progress->user_id)->where('category_id', $WorkPackageTask->category_id)->first();
                    if ($Payment) {
                        $Payment->update(['status' => 'stop']);
                    }
                }
            }
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'اطلاعات بروزرسانی شد'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'danger',
                'message' => 'بروزرسانی با خطا روبرو شد'
            ]);
        }
    }
}

