<?php

namespace Modules\SectionManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubsectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required||unique:subsection,title,' . $this->subsection,
            'section' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان زیر بخش',
            'section' => 'بخش'
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
