<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;
    protected $table ="staffs";

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function office()
    {
        return $this->hasOne(Office::class, "id", "office_id");
    }

    public function post()
    {
        return $this->hasOne(Post::class, "id", "post_id");
    }

}