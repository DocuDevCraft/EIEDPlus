<?php

namespace Modules\Freelancer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shenasnameh'=> 'required',
            'address'=> 'required',
            'postal_code'=> 'required|digits:10',
            'sarbazi_file' => 'nullable|file|max:10240',
        ];
    }

    public function attributes()
    {
        return [
            'shenasnameh' => 'شماره شناسنامه',
            'address' => 'آدرس محل سکونت',
            'postal_code' => 'کد پستی محل سکونت',
            'sarbazi_file' => 'فایل کارت پایان خدمت'
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
