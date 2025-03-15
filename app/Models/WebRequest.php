<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function web_projects()
    {
        return $this->belongsTo(WebProject::class, 'project_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
