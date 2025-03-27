<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'post_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
            'building' => ['required']
        ];

        if (!$this->shouldValidateName()) {
            return $rules;
        }

        $rules['name'] = ['required'];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です',
            'post_code.required' => '郵便番号は必須です',
            'post_code.regex' => '郵便番号は「***-****」の形式で入力してください',
            'address.required' => '住所は必須です',
            'building.required' => '建物名は必須です',
        ];
    }

    protected function shouldValidateName()
    {
        return $this->input('view') !== 'address';
    }
}
