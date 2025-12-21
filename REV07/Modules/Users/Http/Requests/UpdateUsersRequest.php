<?php

namespace Modules\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user,
            'phone' => 'nullable|digits:11|numeric|regex:/^[0]{1}[9]{1}[0-9]{2}[0-9]{3}[0-9]{4}$/|unique:users,phone,' . $this->user,
            'avatar' => [
                'nullable',
                'file',
                'max:2048', // KB
                'mimes:jpg,jpeg,png,webp,bmp,gif,svg', // svg optional
            ],
        ];

        if (!$this->routeIs('users.update')) {
            $this->dd(1345);
            $rules['role'] = 'required';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام خانوادگی',
            'email' => 'آدرس ایمیل',
            'role' => 'سطح دسترسی',
            'avatar' => 'تصویر پروفایل'
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
