<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RwebDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'telephone'
    ];

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, 'detail_id');
    }
}
