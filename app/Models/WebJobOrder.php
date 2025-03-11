<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebJobOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function web_project_channels()
    {
        return $this->hasMany(WebProjectChannel::class, 'web_job_order_id');
    }

    public function web_revisions()
    {
        return $this->hasMany(WebRevisions::class, 'web_job_order_id');
    }
}
