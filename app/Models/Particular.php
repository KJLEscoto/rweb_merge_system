<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Particular extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_form_id',
        'particular',
    ];

    /**
     * Relationship: Particular belongs to a RequestForm.
     */
    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class, 'request_form_id');
    }
}
