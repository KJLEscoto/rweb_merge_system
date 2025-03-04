<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model
{
    use HasFactory;

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'title',
        'description',
        'issued_by',
        'request_id',
        'renewable'
    ];

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get all job drafts for this job order.
     */
    public function jobDrafts()
    {
        return $this->hasMany(JobDraft::class, 'job_order_id');
    }

    public function request()
    {
        return $this->belongsTo(Request::class, 'request_id');
    }
}
