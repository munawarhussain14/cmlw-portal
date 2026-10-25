<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    public function officeDistrict()
    {
        return $this->hasOne(District::class, "d_id", "district")->withoutGlobalScopes();
    }

    public function assignDistrict()
    {
        return $this->hasMany(District::class, "assign_id", "id")->withoutGlobalScopes();
    }

    public function lease_labours()
    {
        return $this->hasManyThrough(Labour::class, District::class, "assign_id", "lease_district_id", "id", "d_id");
    }

    public function labours()
    {
        return $this->hasManyThrough(Labour::class, District::class, "assign_id", "domicile_district", "id", "d_id");
    }

    public function officer()
    {
        return $this->hasOne(User::class, "id", "incharge");
    }

    public function organization()
    {
        return $this->hasOne(Organization::class, "id", "org_id");
    }

    public function scholarship()
    {
        $fy = FyYear::first();
        $data = Scholarship::whereIn("category", ["General", "Other"])
            ->leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")
            ->whereIn("labours.lease_district_id", $this->assignDistrict->pluck("d_id"))
            ->where("fy_year", $fy->year)->withoutGlobalScopes();

        return $data;
    }

    public function labour_count()
    {
        $fy = FyYear::first();

        $data = Labour::whereIn("labours.lease_district_id", $this->assignDistrict->pluck("d_id"))->withoutGlobalScopes();
        return $data;
    }
}
