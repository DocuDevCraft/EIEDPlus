<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountAuthenticationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'otp' => 'required|min:6',
            'certpass' => 'required|min:6',
        ];
    }

    public function attributes()
    {
        return [
//            'otp' => 'کد یک‌بار مصرف',
            'certpass' => 'رمز عبور آسان امضا'
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
