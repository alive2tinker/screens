<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    protected $fillable = [
        'title'
    ];

    public function attachments()
    {
        return $this->belongsToMany(Attachment::class, 'attachment_screen');
    }

    public function messages()
    {
        return Message::orderby('created_at','desc')->get();
    }
}
