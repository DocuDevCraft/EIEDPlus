<?php

namespace Modules\SectionManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DivisionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:division,title,' . $this->division,
            'subsection' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان قسمت',
            'subsection' => 'زیر بخش'
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
