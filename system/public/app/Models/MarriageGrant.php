<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\MarriageGrantScope;

class MarriageGrant extends Model
{
    use HasFactory;

    protected $fillable = [
        "marriage_held_on",
        "husband_name",
        "husband_cnic",
        "c_id",
        "scheme_id"
    ];

    protected $table = "marriage_grant";
    
    protected static function booted()
    {
        static::addGlobalScope(new MarriageGrantScope);
    }

    public function labour()
    {
        return $this->hasOne(Labour::class,"l_id","l_id");
    }

    public function daughter()
    {
        return $this->hasOne(Children::class,"id","c_id");
    }

    public function mother()
    {
        return $this->hasMany(LabourWife::class, "w_id", "mother_id");
    }

    public function office()
    {
        $district = District::find($this->labour->domicile_district);
        return $district->assignTo;
    }
}
