<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReceiveGoodsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'goods_id' => $this->goods_id,
            'desc' => $this->goods->desc,
            'name' => $this->goods->name,
            'image' => $this->goods->imageUrl,
        ];
    }
}
