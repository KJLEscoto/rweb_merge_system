<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEndorsementForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client_id',
        'date_issued',
        'person_in_charge',
        'issued_by',
        'project_scope',
        'timeline',
        'deliverables',
        'prepared_by',
        'noted_by',
        'approved_by',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function personInCharge()
    {
        return $this->belongsTo(User::class, 'person_in_charge');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function preparedBy()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function notedBy()
    {
        return $this->belongsTo(User::class, 'noted_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
