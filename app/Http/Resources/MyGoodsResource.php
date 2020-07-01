<?php

namespace App\Http\Resources;

use App\Models\ReceiveGoods;
use Illuminate\Http\Resources\Json\JsonResource;

class MyGoodsResource extends JsonResource
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
            'id' => $this->id,
            'goods_id' => $this->goods_id,
            'name' => $this->goods->name,
            'image' => $this->goods->imageUrl,
            'desc' => $this->goods->desc,
            'state' => ReceiveGoods::$state[$this->state],
        ];
    }
}
