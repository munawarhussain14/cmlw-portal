<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Children extends Model
{
    use HasFactory;
    protected $table = "children";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "name",
        "reg_no",
        "father_id",
        "dob",
        "gender",
        "disabled",
        "disability_factor"
    ];

    public function father()
    {
        return $this->hasOne(Labour::class,"l_id","father_id")->withoutGlobalScopes();
    }
    
    public function mother()
    {
        return $this->hasOne(LabourWife::class,"w_id","mother_id")->withoutGlobalScopes();
    }

    public function history()
    {
        return $this->hasMany(Scholarship::class,"s_id","id")->withoutGlobalScopes();
    }

    public function apply()
    {
        $fy = FyYear::first();
        return $this->hasOne(Scholarship::class,"s_id","id")->where("fy_year",$fy->year)->withoutGlobalScopes();
    }

    public function diploma()
    {
        $fy = FyYear::first();
        return $this->hasOne(Diploma::class,"child_id","id")->where("fy_year",$fy->year)->withoutGlobalScopes();
    }

}