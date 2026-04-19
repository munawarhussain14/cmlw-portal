<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeType extends Model
{
    use HasFactory;
    protected $table = "scheme_types";

    public function schemes()
    {
        return $this->hasMany(Scheme::class,"scheme_type_id","id");
    }

}
