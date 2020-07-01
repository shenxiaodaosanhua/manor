<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BagNotifyRequest
 * @package App\Http\Requests
 */
class BagNotifyRequest extends FormRequest
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
        return [
            'state' => 'required|numeric'
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'state.required' => '请选择状态',
            'state.numeric' => '状态类型不正确',
        ];
    }
}
