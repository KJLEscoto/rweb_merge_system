<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebRevisions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function web_job_orders()
    {
        return $this->belongsTo(WebJobOrder::class, 'web_job_order_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
