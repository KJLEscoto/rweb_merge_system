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

    public static function mySignature($user_id): ?File
    {
        return File::find(Signature::find(User::find($user_id)?->signature_id)?->file_id);
    }

    // In the Signature model
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
