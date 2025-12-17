<?php

namespace App\Helpers;


use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\WorkPackageManager\Entities\WorkPackage;

class WorkPackageScaleHelper
{
    /**
     * بررسی و به‌روزرسانی سطح بسته کاری (work_package_scale)
     */
    public static function updateScale(int $workPackageId): void
    {
        // جمع یا میانگین قیمت پیشنهادها
        $offers = FreelancerOffer::where('work_package_id', $workPackageId)
            ->whereNotNull('price')
            ->pluck('price');

        if ($offers->isEmpty()) {
            return;
        }

        // می‌تونی جمع یا میانگین بگیری، اینجا میانگین گرفتیم
        $averagePrice = $offers->avg();

        $scale = null;

        if ($averagePrice <= 300_000_000) {
            $scale = 'minor'; // جزئی
        } elseif ($averagePrice <= 3_000_000_000) {
            $scale = 'normal'; // عادی
        } else {
            $scale = 'major'; // در صورت نیاز (برای آینده)
        }

        WorkPackage::where('id', $workPackageId)->update([
            'work_package_scale' => $scale,
        ]);
    }
}
