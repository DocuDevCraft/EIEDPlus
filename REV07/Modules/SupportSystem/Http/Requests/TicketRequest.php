<?php

namespace Modules\SupportSystem\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'replay_text' => 'required',
            'attachments' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ];
    }

    public function attributes()
    {
        return [
            'replay_text' => 'پاسخ',
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
