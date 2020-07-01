<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReceiveGoodsAddressRequest
 * @package App\Http\Requests
 */
class ReceiveGoodsAddressRequest extends FormRequest
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
            'goods_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'mobile' => 'required',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'goods_id.required' => '请选择商品id',
            'name.required' => '请输入收货人名称',
            'address.required' => '请输入收货地址',
            'mobile.required' => '请输入收货人手机号码',
        ];
    }
}
