<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseHistoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'academy' => 'required',
            'at_time' => 'required',
            'to_time' => ['nullable','required_with:date',
                function (string $attribute, mixed $value, $fail) {
                    if ($value !== true) {
                        if ($value >= $this->at_time) {
                        } else {
                            $fail('تاریخ پایان دوره باید بیشتر یا مساوی با تاریخ شروع دوره باشد.');
                        }
                    }
                },
            ],
            'course_file' => 'nullable|file|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان دوره',
            'academy' => 'نام موسسه',
            'at_time' => 'تاریخ شروع دوره',
            'to_time' => 'تاریخ پایان دوره',
            'course_file' => 'فایل گواهی نامه',
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
