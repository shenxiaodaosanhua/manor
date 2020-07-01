<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BagHelpResource extends JsonResource
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
            'content' => nl2br($this->content),
        ];
    }
}
