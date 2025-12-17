<?php

namespace Modules\WorkPackageTaskManager\ImportsExports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class ActivityExport implements FromArray, WithHeadings
{
    protected $ID;

    public function __construct($id)
    {
        $this->ID = $id;
    }

    public function headings(): array
    {
        $Header = [
            [
                'Activity',
                '',
                '',
            ],
            [
                'Name',
                '%',
                'Description',
            ]
        ];

        for ($i = 0; $i < 20; $i++) {
            $Header[0][] = "Stage " . ($i + 1);
            $Header[0][] = "";
            $Header[0][] = "";
            $Header[1][] = "Name";
            $Header[1][] = "Days";
            $Header[1][] = "%";
        }

        return $Header;
    }

    public function array(): array
    {
        $ID = $this->ID;

        /* Get Activities */
        $activities = WorkPackageActivity::where('work_package_id', $ID)->get();

        $finalActivities = [];

        if ($activities->isNotEmpty()) {
            foreach ($activities as $activity) {
                $activityData = [
                    'title' => $activity->title,
                    'price_percentage' => $activity->price_percentage,
                    'category_list' => [],
                ];

                // گرفتن دسته‌بندی‌ها
                $categories = WorkPackageCategory::where('activity_id', $activity->id)->get();

                if ($categories->isNotEmpty()) {
                    foreach ($categories as $category) {

                        // شخصی‌سازی داده Category
                        $categoryData = [
                            'title' => $category->title,
                            'price_percentage' => $category->price_percentage,
                            'due_date' => $category->due_date,
                            'task_list' => [],
                        ];

                        // بجای گرفتن task واقعی می‌تونی همون activity رو دوباره بذاری یا تسک واقعی رو مپ کنی
                        $tasks = WorkPackageTask::where('category_id', $category->id)->get();

                        if ($tasks->isNotEmpty()) {
                            foreach ($tasks as $task) {
                                $categoryData['task_list'][] = [
                                    'title' => $task->title,
                                    'price_percentage' => 100,
                                    'desc' => $task->desc ?? null,
                                ];
                            }
                        } else {
                            $categoryData['task_list'][] = [
                                'title' => $activity->title,
                                'price_percentage' => 100,
                                'desc' => $activity->desc ?? null,
                            ];
                        }

                        $activityData['category_list'][] = $categoryData;
                    }
                }

                $finalActivities[] = $activityData;
            }
        }

        $body = [];

        if ($finalActivities) {
            foreach ($finalActivities as $activityKey => $activity) {
                $body[$activityKey][] = $activity['title'];
                $body[$activityKey][] = $activity['price_percentage'];
                $body[$activityKey][] = $activity['category_list'][0]['task_list'][0]['desc'];

                foreach ($activity['category_list'] as $categoryKey => $category) {
                    $body[$activityKey][] = $category['title'];
                    $body[$activityKey][] = $category['due_date'];
                    $body[$activityKey][] = $category['price_percentage'];
                }
            }
        }
        

        return $body;
    }
}
