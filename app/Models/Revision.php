<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'job_draft_id',
        'declined_by',
        'summary',
        'last_draft',
        'submitted_draft',
        'date_submitted',
        'revision_date',
        'status'
    ];

    /**
     * Get the job draft associated with this revision.
     */
    public function jobDraft()
    {
        return $this->belongsTo(JobDraft::class, 'job_draft_id');
    }

    /**
     * Get the user who declined the job draft.
     */
    public function declinedBy()
    {
        return $this->belongsTo(User::class, 'declined_by');
    }
}
