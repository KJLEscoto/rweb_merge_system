<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soa extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_from',
        'telephone',
        'company',
        'client_name',
        'address',
        'billing_date',
        'due_date',
        'date',
        'job_draft_id',
        'prepared_by',
        'approved_by',
        'image_path',
        'status'
    ];

    public function jobDraft()
    {
        return $this->belongsTo(JobDraft::class);
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function particulars()
    {
        return $this->hasMany(soaparticular::class);
    }
}
