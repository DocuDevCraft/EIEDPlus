<?php


namespace Modules\WorkPackageTaskManager\ImportsExports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\WorkPackageTaskManager\Services\WorkPackageStorageService;

class ActivityImport implements ToCollection
{
    protected $ID;

    public function __construct($id)
    {
        $this->ID = $id;
    }

    public function collection(Collection $rows)
    {
        $data = $rows->slice(2)->values();

        $allCleanRows = [];
        $errors = [];

        foreach ($data as $rowIndex => $row) {
            $cleanRow = $row->toArray();

            $rowErrors = [];
            if (!isset($cleanRow[0]) || $cleanRow[0] === '') {
                $rowErrors[] = 'عنوان فعالیت الزامی است';
            }
            if (!isset($cleanRow[1]) || $cleanRow[1] === '') {
                $rowErrors[] = 'درصد فعالیت الزامی است';
            }

            if ($rowErrors) {
                return redirect()->back()->with('importError', $rowErrors);
            }

            if (!isset($cleanRow[2])) {
                $cleanRow[2] = null;
            }

            if (!empty($rowErrors)) {
                $errors[$rowIndex] = $rowErrors;
                continue;
            }

            $activity = [
                'title' => $cleanRow[0],
                'price_percentage' => $cleanRow[1],
                'category_list' => [],
            ];

            $categoriesRaw = array_slice($cleanRow, 3);

            for ($i = 0; $i < count($categoriesRaw); $i += 3) {
                if ($categoriesRaw[$i] || $categoriesRaw[$i + 1] || $categoriesRaw[$i + 2]) {
                    $categoryErrors = [];
                    if (!isset($categoriesRaw[$i]) || $categoriesRaw[$i] === '') {
                        $categoryErrors[] = 'عنوان دسته بندی الزامی است (فعالیت: "' . ($cleanRow[0]) . '")';
                    }
                    if (!isset($categoriesRaw[$i + 1]) || $categoriesRaw[$i + 1] === '') {
                        $categoryErrors[] = 'زمان دسته بندی الزامی است (فعالیت: "' . ($cleanRow[0]) . '")';
                    }
                    if (!isset($categoriesRaw[$i + 2]) || $categoriesRaw[$i + 2] === '') {
                        $categoryErrors[] = 'درصد دسته بندی الزامی است (فعالیت: "' . ($cleanRow[0]) . '")';
                    }
                    if (!empty($categoryErrors)) {
                        $errors["row_{$rowIndex}_category_" . ($i / 3)] = $categoryErrors;
                        continue;
                    }

                    if ($errors) {
                        return redirect()->back()->with('importError', $errors);
                    }

                    $activity['category_list'][] = [
                        'title' => $categoriesRaw[$i],
                        'due_date' => $categoriesRaw[$i + 1],
                        'price_percentage' => $categoriesRaw[$i + 2],
                        'task_list' => [[
                            'title' => $cleanRow[0],
                            'price_percentage' => 100,
                            'desc' => $cleanRow[2],
                        ]]
                    ];
                }
            }

            if (!empty($activity['category_list'])) {
                $allCleanRows[] = $activity;
            }
        }

        if ($errors) {
            return redirect()->back()->with('importError', $errors);
        } else {
            $service = new WorkPackageStorageService();
            try {
                $service->store($this->ID, ['activity_list' => $allCleanRows]);
            } catch (\Exception $e) {
                return redirect()->back()->with('notification', [
                    'class' => 'alert',
                    'message' => $e->getMessage(),
                ])->withInput()->with('form_debug', $allCleanRows);
            }
        }
    }
}
