<?php

namespace Modules\FreelancerOffer\Http\Controllers;

use Illuminate\Routing\Controller;

class OfferListPDFContentController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/accept-rules
    * GET
    * */
    static public function ContractText($WorkPackage, $FreelancerOffer)
    {
        $rows = '';

        foreach ($FreelancerOffer as $item) {
            $freelancerName = $item->users->first_name . ' ' . $item->users->last_name;
            $price = number_format($item->price) . ' تومان';
            $time = ($WorkPackage->work_package_type === 'hourly_contract' ? $item->time * 8 : $item->time) . ' ' . ($WorkPackage->work_package_type === 'hourly_contract' ? 'ساعت' : 'روز');
            $grade = $item->gradeScore ?? '-';
            $created = \Morilog\Jalali\Jalalian::forge($item->created_at)->format('H:i - Y/m/d');

            $rows .= "
        <tr style='border-bottom: 1px solid #ccc;'>
            <td style=\"padding: 8px; border: 1px solid #999;\">{$freelancerName}</td>
            <td style=\"padding: 8px; border: 1px solid #999;\">{$price}</td>
            <td style=\"padding: 8px; border: 1px solid #999;\">{$time}</td>
            <td style=\"padding: 8px; border: 1px solid #999;\">{$grade}</td>
            <td style=\"padding: 8px; border: 1px solid #999;\">{$created}</td>
        </tr>
    ";
        }


        $Contract = <<<HTML
        <div dir='rtl' style='font-size: 10pt; font-size: 15px; line-height: 2.5;'>
            <div style='text-align: center; font-weight: bold; margin-bottom: 25px; font-size: 20px'>لیست پیشنهاد قیمت فریلنسرها</div>
            <div style='text-align: center; font-weight: 600; font-size: 20px'>بسته کاری <span style='font-weight: bold;'>{$WorkPackage->title}</span></div>
            <div style='text-align: center; font-weight: 600; margin-bottom: 50px; font-size: 20px'>به شماره <span style='font-weight: bold;'>{$WorkPackage->unique_id}</span></div>

            <table style="
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
                font-size: 13px;
                text-align: center;
                direction: rtl;
            ">
                <thead>
                    <tr style="
                        background-color: #f2f2f2;
                        font-weight: bold;
                        border-bottom: 2px solid #555;
                    ">
                        <td style="
                            border: 1px solid #999;
                            padding: 8px;
                        ">پیشنهاد دهنده</td>
                        <td style="
                            border: 1px solid #999;
                            padding: 8px;
                        ">پیشنهاد قیمت</td>
                        <td style="
                            border: 1px solid #999;
                            padding: 8px;
                        ">پیشنهاد زمانی</td>
                        <td style="
                            border: 1px solid #999;
                            padding: 8px;
                        ">نمره فنی</td>
                        <td style="
                            border: 1px solid #999;
                            padding: 8px;
                        ">زمان ثبت</td>
                    </tr>
                </thead>
                <tbody>
                    {$rows}
                </tbody>
            </table>

            <table style="width:100%; border-collapse:collapse; text-align:center; direction:rtl; font-size:14px; margin-top:50px;">
                <tr>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر اول
                    </td>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر دوم
                    </td>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر سوم
                    </td>
                </tr>
                <tr>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر چهارم
                    </td>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر پنجم
                    </td>
                    <td style="width:33.33%; height:150px; border:1px solid #999; vertical-align:bottom; padding:10px;">
                        امضای نفر ششم
                    </td>
                </tr>
            </table>
        </div>
        HTML;

        return $Contract;
    }
}
