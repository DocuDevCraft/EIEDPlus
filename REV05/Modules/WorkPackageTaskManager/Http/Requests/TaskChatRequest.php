<?php

namespace Modules\WorkPackageTaskManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskChatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'message' => 'متن پیام'
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
