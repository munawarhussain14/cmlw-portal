<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourWife extends Model
{
    use HasFactory;
    protected $table = 'labour_wife';
    protected $primaryKey = "w_id";

    protected $fillable = [
        "name",
        "cnic",
        "husbend_cnic",
    ];

    public function husband()
    {
        return $this->hasOne(Children::class, "husband_id", "l_id");
    }
}
