<?php

namespace Modules\SupportSystem\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportSystemRequest extends FormRequest
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
            'ticket_content' => 'required|min:10',
            'status' => 'required',
            'department' => 'required',
            'priority' => 'required',
            'uid' => 'required',
            'attachments' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'موضوع تیکت',
            'ticket_content' => 'محتوای تیکت',
            'status' => 'وضعیت تیکت',
            'department' => 'دپارتمان',
            'priority' => 'اولویت',
            'uid' => 'کاربر هدف',
            'attachments' => 'پیوست'
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
