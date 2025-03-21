<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleChannel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pages()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function privileges()
    {
        return $this->belongsTo(Privilege::class, 'privilege_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
