<?php

namespace App\Http\Resources;

use App\Models\Plant;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeOutResource extends JsonResource
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
            'state' => Plant::$stateType[$this->state],
            'time_out' => (string) $this->time_out_at ? $this->time_out_at->timestamp : '',
        ];
    }
}
