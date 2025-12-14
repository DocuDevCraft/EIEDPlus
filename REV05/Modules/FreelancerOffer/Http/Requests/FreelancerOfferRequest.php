<?php

namespace Modules\FreelancerOffer\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FreelancerOfferRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'time' => 'required|numeric|min:1',
            'attachment' => 'nullable|file|max:10240',
        ];
    }


    public function attributes()
    {
        return [
            'price' => 'قیمت',
            'time' => 'زمان',
            'attachment' => 'فایل پیوست پیشنهاد',
        ];
    }


    public function messages()
    {
        return [
            'price.min' => 'مبلغ وارد شده صحیح نمی باشد',
            'time.min' => 'زمان وارد شده صحیح نمی باشد',
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
