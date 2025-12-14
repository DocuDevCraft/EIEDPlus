<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'phone' => ['nullable', 'digits:11', 'numeric', 'regex:/^[0]{1}[9]{1}[0-9]{2}[0-9]{3}[0-9]{4}$/', Rule::unique('users')->ignore($this->user)],
            'role' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'email' => 'آدرس ایمیل',
            'phone' => 'موبایل',
            'role' => 'سطح دسترسی',
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
