<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request) {
        return [
                "id" => $this->id,
                'title' => $this->title,
                'description' => $this->description,
                'image' => $this->full_path_image,
                'rate' => $this->rate,
                'category_id' => $this->category_id,
                'category' => new CategoryResource($this->whenLoaded('category'))
        ];
    }
}
