<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoaParticular extends Model
{
    use HasFactory;

    protected $fillable = [
        'soa_id',
        'quantity',
        'particulars',
        'charges',
        'credits'
    ];

    public function soa()
    {
        return $this->belongsTo(Soa::class);
    }
}
