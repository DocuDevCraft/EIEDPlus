<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdentityInformailtionRequest extends FormRequest
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
            'meli_code' => 'required|numeric',
            'cardserialno' => 'required',
            'birthday' => 'required',
            'birthday_miladi' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'نام',
            'last_name' => 'نام نام خانوادگی',
            'meli_code' => 'کد ملی',
            'cardserialno' => 'سریال کارت ملی',
            'birthday' => 'تاریخ تولد',
            'birthday_miladi' => 'تاریخ تولد میلادی',
            'phone'=>'شماره موبایل',
            'email'=>'ایمیل',
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
