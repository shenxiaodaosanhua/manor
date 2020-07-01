<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'waters' => $this->waters,
            'stage' => $this->stage,
            'stage_number' => $this->stage_number,
            'stage_last_number' => $this->stage_last_number,
            'next_stage_number' => $this->level->number ?? 0,
            'upgrade_waters' => $this->previous_waters,
            'state' => $this->state,
        ];
    }
}
