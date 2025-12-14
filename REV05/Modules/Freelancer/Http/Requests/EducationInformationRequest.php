<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'field_of_study' => 'required',
            'orientation' => 'required',
            'education_level' => 'required',
            'university' => 'required',
            'at_time' => 'required',
            'to_time' => ['nullable','required_with:date',
                function (string $attribute, mixed $value, $fail) {
                    if ($value !== true) {
                        if ($value >= $this->at_time) {
                        } else {
                            $fail('تاریخ پایان تحصیل باید بیشتر یا مساوی با تاریخ شروع تحصیل باشد.');
                        }
                    }
                },
            ],
            'country' => 'required',
            'city' => 'required',
            'gpa' => 'nullable|numeric|min:0|max:20',
            'education_file' => 'nullable|file|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'field_of_study' => 'رشته تحصیلی',
            'orientation' => 'گرایش',
            'education_level' => 'سطح تحصیلات',
            'university' => 'دانشگاه',
            'at_time' => 'تاریخ شروع دوره تحصیلی',
            'to_time' => 'تاریخ پایان دوره تحصیلی',
            'country' => 'کشور',
            'city' => 'شهر',
            'gpa' => 'معدل',
            'education_file' => 'فایل گواهی پایان دوره تحصیلی',
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
