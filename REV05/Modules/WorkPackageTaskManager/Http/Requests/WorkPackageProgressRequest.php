<?php

namespace Modules\WorkPackageTaskManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkPackageProgressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attachment' => 'required|file|max:10240'
        ];
    }

    public function attributes()
    {
        return [
            'attachment' => 'فایل'
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
