<?php

namespace App\Http\Resources;

use App\Models\BagNotify;
use Illuminate\Http\Resources\Json\JsonResource;

class BagNotifyResource extends JsonResource
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
            'user_id' => $this->user_id,
            'state' => $this->state,
        ];
    }
}
