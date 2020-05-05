<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScreenResource extends JsonResource
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
            'title' => $this->title,
            'attachments' => AttachmentResource::collection($this->attachments),
            'messages' => MessageResource::collection($this->messages()),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
