<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebRevisions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function web_project_channel()
    {
        return $this->belongsTo(WebProjectChannel::class, 'web_project_channel_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function declined_by()
    {
        return $this->belongsTo(User::class, 'declined_by_id');
    }
}
