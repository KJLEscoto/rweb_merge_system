<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department',
        'date',
        'status',
        'description',
        'requested_by',
        'manager_id',
        'receiver_id'
    ];

    /**
     * Relationship: RequestForm belongs to a User (Requested By).
     */
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Relationship: RequestForm belongs to a User (Manager).
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relationship: RequestForm belongs to a User (Receiver).
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relationship: RequestForm has many Particulars.
     */
    public function particulars()
    {
        return $this->hasMany(Particular::class, 'request_form_id');
    }
}
