<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageHistoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language_name' => 'required',
            'language_level' => 'required',
            'language_file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ];
    }

    public function attributes()
    {
        return [
            'language_name' => 'زبان',
            'language_level' => 'سطح',
            'language_file' => 'فایل مدرک زبان',
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
