<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebProject extends Model
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


    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_signed_id');
    }

    public function web_requests()
    {
        return $this->hasOne(WebRequest::class, 'project_id');
    }
}
