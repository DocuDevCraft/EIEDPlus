<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkExperienceHistoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activity_type' => 'required',
            'post' => 'required',
            'field' => 'required',
            'company' => 'required',
            'at_time' => 'required',
            'to_time' => ['nullable','required_with:date',
                function (string $attribute, mixed $value, $fail) {
                    if ($value !== true) {
                        if ($value >= $this->at_time) {
                        } else {
                            $fail('تاریخ پایان همکاری باید بیشتر یا مساوی با تاریخ شروع همکاری باشد.');
                        }
                    }
                },
            ],
            'work_experience_file' => 'nullable|file|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'activity_type' => 'نوع فعالیت',
            'post' => 'سمت سازمانی',
            'field' => 'زمینه کاری',
            'company' => 'نام شرکت',
            'at_time' => 'تاریخ شروع همکاری',
            'to_time' => 'تاریخ پایان همکاری',
            'work_experience_file' => 'نامه سابقه کار',
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
