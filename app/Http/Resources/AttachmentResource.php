<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'image' => url('/') .'/' . $this->image_link,
            'youtube' => $this->youtube_link,
            'tweetInfo' => $this->tweet_info,
            'type' => $this->type,
            'text' => $this->text,
            'employeeName' => $this->employee_name,
            'quoteAuthor' => $this->quote_author
        ];
    }
}
