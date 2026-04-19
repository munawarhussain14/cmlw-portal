<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Organization extends Model
{
    use HasFactory;
    protected $table = "organizations";
    protected $primaryKey = "id";

    public function parentDept()
    {
        return $this->hasOne(Organization::class, "id", "parent");
    }

    public function functionHead()
    {
        return $this->morphToMany(ObjectHead::class, 'taggable');
    }

}