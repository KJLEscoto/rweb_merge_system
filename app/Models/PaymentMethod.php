<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_id',
        'method',
        'account_name',
        'account_number'
    ];

    public function rwebDetail()
    {
        return $this->belongsTo(RwebDetail::class, 'detail_id');
    }
}
