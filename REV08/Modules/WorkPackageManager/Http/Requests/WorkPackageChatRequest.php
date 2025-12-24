<?php

namespace Modules\WorkPackageManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkPackageChatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'message' => 'required'
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx'
        ];
    }

    public function attributes()
    {
        return [
            'attachment' => 'پیوست'
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
