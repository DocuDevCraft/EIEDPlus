<?php

namespace Modules\WorkPackageTaskManager\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class WorkPackageStorageService
{
    /**
     * ذخیره فعالیت‌ها، دسته‌بندی‌ها و تسک‌های یک بسته کاری
     *
     * @param int $workPackageId
     * @param array $data
     * @throws Exception
     */
    public function store(int $workPackageId, array $data): void
    {
        $workPackage = WorkPackage::select('id', 'status')->findOrFail($workPackageId);

        if ($workPackage->status !== 'pending') {
            throw new Exception('این بسته کاری در حال پردازش است!');
        }

        DB::beginTransaction();

        try {
            // حذف رکوردهای قبلی
            WorkPackageActivity::where('work_package_id', $workPackageId)->delete();
            WorkPackageCategory::where('work_package_id', $workPackageId)->delete();
            WorkPackageTask::where('work_package_id', $workPackageId)->delete();

            // اگر لیست فعالیت‌ها وجود داشت
            if (!empty($data['activity_list'])) {
                $this->storeActivities($workPackageId, $data['activity_list']);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * ذخیره فعالیت‌ها و دسته‌بندی‌ها و تسک‌هایشان
     */
    protected function storeActivities(int $workPackageId, array $activities): void
    {
        $totalActivityPercentage = 0;

        foreach ($activities as $activityData) {
            $totalActivityPercentage += $activityData['price_percentage'];

            if ($totalActivityPercentage > 100) {
                throw new Exception('مجموع درصد فعالیت‌ها نمی‌تواند بیشتر از 100 باشد.');
            }

            $activity = WorkPackageActivity::create([
                'work_package_id' => $workPackageId,
                'title' => $activityData['title'],
                'price_percentage' => $activityData['price_percentage'],
            ]);

            if (!empty($activityData['category_list'])) {
                $this->storeCategories($workPackageId, $activity->id, $activity->title, $activityData['category_list']);
            }
        }
    }

    /**
     * ذخیره دسته‌بندی‌ها و تسک‌هایشان
     */
    protected function storeCategories(int $workPackageId, int $activityId, string $activityTitle, array $categories): void
    {
        $totalCategoryPercentage = 0;
        $stage = 0;

        foreach ($categories as $categoryData) {
            $totalCategoryPercentage += $categoryData['price_percentage'];
            $stage++;

            if ($totalCategoryPercentage > 100) {
                throw new Exception("مجموع درصد دسته‌بندی‌های فعالیت {$activityTitle} نمی‌تواند بیشتر از 100 باشد.");
            }

            $category = WorkPackageCategory::create([
                'work_package_id' => $workPackageId,
                'activity_id' => $activityId,
                'title' => $categoryData['title'],
                'stage' => $stage,
                'price_percentage' => $categoryData['price_percentage'],
                'due_date' => $categoryData['due_date'] ?? null,
            ]);

            if (!empty($categoryData['task_list'])) {
                $this->storeTasks($workPackageId, $category->id, $category->title, $categoryData['task_list']);
            }
        }
    }

    /**
     * ذخیره تسک‌ها
     */
    protected function storeTasks(int $workPackageId, int $categoryId, string $categoryTitle, array $tasks): void
    {
        $totalTaskPercentage = 0;

        foreach ($tasks as $taskData) {
            $totalTaskPercentage += $taskData['price_percentage'];

            if ($totalTaskPercentage > 100) {
                throw new Exception("مجموع درصد تسک‌های دسته {$categoryTitle} نمی‌تواند بیشتر از 100 باشد.");
            }

            WorkPackageTask::create([
                'work_package_id' => $workPackageId,
                'category_id' => $categoryId,
                'title' => $taskData['title'],
                'desc' => $taskData['desc'] ?? null,
                'price_percentage' => $taskData['price_percentage'],
                'prerequisite' => null,
            ]);
        }
    }
}
