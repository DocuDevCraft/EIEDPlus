<?php

namespace Modules\SectionManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionManagerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required||unique:section,title,' . $this->section,
            'code' => ['nullable', 'regex:/^[a-zA-Z]+$/'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان بخش',
            'code' => 'کد اختصاری'
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
