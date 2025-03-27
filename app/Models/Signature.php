<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasOne(User::class, 'signature_id');
    }

    public static function mySignature($user_id): File
    {
        return File::where('id', Signature::where('id', User::where('id', $user_id)->first()->signature_id)->first()->file_id)->first();
    }

    // In the Signature model
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
