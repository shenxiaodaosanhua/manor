<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LogisticsInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $content = $this->content;

        return [
            'list' => $content['list'],
            'name' => $content['expName'],
            'phone' => $content['expPhone'],
            'logo' => $content['logo'],
        ];
    }
}
