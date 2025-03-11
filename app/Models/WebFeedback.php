<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebFeedback extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function web_project_channels()
    {
        return $this->hasMany(WebProjectChannel::class, 'web_feedback_id');
    }
}
