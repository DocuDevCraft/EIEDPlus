<?php

namespace Modules\WorkPackageManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkPackageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'package_number' => 'required|numeric',
            'title' => 'required',
            'section_id' => 'required|numeric',
            'man_hour' => 'required|numeric',
            'minimum_technical_grade' => 'required|numeric',
            'seniority' => 'required',
            'package_time_type' => 'required',
            'package_price_type' => 'required',
            'recommend_time' => 'required',
            'recommend_price' => 'required',
            'winning_formula' => 'required',
            'minimum_offers' => 'required|numeric',
            'coordinator' => 'required',
            'daily_fine' => 'required|numeric',
            'fine_after_day' => 'required|numeric',
            'fine_after_price' => 'required|numeric',
            'fine_after_negative' => 'required|numeric',
            'offer_time' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'package_number' => 'شماره بسته کاری',
            'title' => 'عنوان بسته کاری',
            'section_id' => 'بخش',
            'man_hour' => 'نفر ساعت',
            'minimum_technical_grade' => 'حداقل نمره قبولی',
            'seniority' => 'ارشدیت',
            'package_time_type' => 'نوع زمان بسته کاری',
            'package_price_type' => 'نوع قیمت بسته کاری',
            'recommend_time' => 'زمان پیشنهادی کارفرما',
            'recommend_price' => 'قیمت پیشنهادی کارفرما',
            'winning_formula' => 'فرمول برنده بسته کاری',
            'minimum_offers' => 'حداقل تعداد پیشنهاد',
            'coordinator' => 'هماهنگ کننده',
            'daily_fine' => 'جریمه هر روز تاخیر',
            'fine_after_day' => 'جریمه پس از گذشت n روز',
            'fine_after_price' => 'مبلغ جریمه پس از گذشت n',
            'fine_after_negative' => 'نمره منفی پس از گذشت n روز',
            'offer_time' => 'زمان مناقصه',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
