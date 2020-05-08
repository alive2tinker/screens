<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'title',
        'type',
        'text',
        'image_link',
        'youtube_link',
        'tweet_info'
    ];

    protected $casts = [
        'tweet_info' => 'array'
    ];

    public function screens()
    {
        return $this->belongsToMany(Screen::class, 'attachment_screen');
    }
}
