<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebProjectChannel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function web_feedbacks()
    {
        return $this->belongsTo(WebFeedback::class, 'web_feedback_id');
    }

    public function web_job_orders()
    {
        return $this->belongsTo(WebJobOrder::class, 'web_job_order_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function web_project()
    {
        return $this->belongsTo(WebProject::class, 'project_id');
    }
}
