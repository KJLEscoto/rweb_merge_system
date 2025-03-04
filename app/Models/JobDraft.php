<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDraft extends Model
{
    use HasFactory;

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'job_order_id',
        'type',
        'date_started',
        'date_target',
        'signature_worker',
        'signature_supervisor',
        'status',
        'draft',
        'worker_signed',
        'supervisor_signed',
        'content_writer_id',
        'graphic_designer_id',
        'client_id',
        'feedback',
        'date_completed',
        'reference_draft_id',
        'days_to_add',
        'draft_op_sign',
        'op_signed_draft',
        'draft_sup_sign',
        'sup_signed_draft'
    ];

    public function jobOrder()
    {
        return $this->belongsTo(JobOrder::class, 'job_order_id');
    }

    /**
     * Get the content writer assigned to this draft.
     */
    public function contentWriter()
    {
        return $this->belongsTo(User::class, 'content_writer_id');
    }

    /**
     * Get the graphic designer assigned to this draft.
     */
    public function graphicDesigner()
    {
        return $this->belongsTo(User::class, 'graphic_designer_id');
    }

    /**
     * Get the client assigned to this draft.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get all revisions for this job draft.
     */
    public function revisions()
    {
        return $this->hasMany(Revision::class, 'job_draft_id');
    }

    public function parentDraft()
    {
        return $this->belongsTo(JobDraft::class, 'reference_draft_id');
    }

    /**
     * Get all drafts that reference this draft.
     */
    public function childDrafts()
    {
        return $this->hasMany(JobDraft::class, 'reference_draft_id');
    }
}
