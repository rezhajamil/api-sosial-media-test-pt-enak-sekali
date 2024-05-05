<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function follower()
    {
        return $this->hasOne(User::class, 'id', 'follower_id');
    }
}
