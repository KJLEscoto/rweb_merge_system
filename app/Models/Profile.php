<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";
    protected $guarded = [];


    public function users()
    {
        return $this->hasOne(User::class, 'profile_id');
    }

    public function files()
    {
        return $this->belongsTo(File::class, 'profile_id');
    }

    public function file() // Changed from files to file
    {
        return $this->belongsTo(File::class, 'file_id'); // Corrected foreign key
    }

    public function profiles()
    {
        return $this->hasOne(Profile::class, 'profile_id');
    }

    public static function myProfile($id): File
    {
        $user = User::findOrFail($id);
        return File::where('id', $user->profiles->file_id)->first();
    }
}
