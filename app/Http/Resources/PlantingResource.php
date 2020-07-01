<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantingResource extends JsonResource
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
            'waters' => $this->waters ?? 0,
            'water_number' => $this->water_number ?? 0,
            'state' => $this->state ?? 0,
        ];
    }
}
