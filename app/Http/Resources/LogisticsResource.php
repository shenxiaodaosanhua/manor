<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogisticsResource extends JsonResource
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
            'address' => $this->address,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'tracking_number' => $this->tracking_number,
            'logistics' => new LogisticsInfoResource($this->logistics),
        ];
    }
}
