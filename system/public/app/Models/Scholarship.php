<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\Scopes\ScholarshipScope;

class Scholarship extends Model
{
    use HasFactory;
    protected $table = 'scholarship_apply';
    protected $primaryKey = "id";
    protected $fillable = [
        "s_id",
        "ad_date",
        "institute",
        "class",
        "subject",
        "session",
        "obtained_marks",
        "total_marks",
        "other_apply",
        "position_holder",
        "special_institute",
        "passing_year",
        "roll_no",
        "board",
        "reg_no",
        "category",
        "fy_year",
        "serial",
        "covid"
    ];

    protected static function booted()
    {
        static::addGlobalScope(new ScholarshipScope);
    }

    public function student()
    {
        return $this->hasOne(Children::class,"id","s_id");
    }

    public function doc_verfied()
    {
        return $this->hasOne(User::class,"id","doc_verify_by");
    }

}