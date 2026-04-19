<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\Scopes\AssignDistrictScope;

class Labour extends Model
{
    use HasFactory;
    protected $table = 'labours';
    protected $primaryKey = "l_id";

    protected static function booted()
    {
        static::addGlobalScope(new AssignDistrictScope);
    }

    protected $fillable = [
        "lease_owner_name",
        "lease_no",
        "lease_district_id",
        "mineral_id",
        "lease_address",
        "work_end_date",
        "issue_date_cnic",
        "expire_date_cnic",
        "cnic_front",
        "cnic_back",
        "profile_image",
        "info_acknowledgment",
        "bank_name",
        "other_bank_name",
        "iban",
        "urdu_name",
        "urdu_father_name",
        "urdu_perm_address",
        "card_issue_date",
        "issued",
        "card_printed"
    ];

    public function district()
    {
        return $this->hasOne(District::class, "d_id", "domicile_district")->withoutGlobalScopes();
    }

    public function account()
    {
        return $this->hasOne(BankAccount::class, "l_id", "l_id");
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, "b_id", "bank_id");
    }

    public function work()
    {
        return $this->hasOne(WorkType::class, "wt_id", "work_id");
    }

    public function perm_district()
    {
        return $this->hasOne(District::class, "d_id", "perm_district_id")->withoutGlobalScopes();
    }

    public function postal_district()
    {
        return $this->hasOne(District::class, "d_id", "postal_district_id")->withoutGlobalScopes();
    }

    public function lease_district()
    {
        return $this->hasOne(District::class, "d_id", "lease_district_id")->withoutGlobalScopes();
    }

    public function mineral()
    {
        return $this->hasOne(Minerals::class, "m_id", "mineral_id");
    }

    public function child()
    {
        return $this->hasOne(Children::class, "father_id", "l_id");
    }

    public function children()
    {
        return $this->hasMany(Children::class, "father_id", "l_id");
    }

    public function wife()
    {
        return $this->hasMany(LabourWife::class, "husband_id", "l_id");
    }

    public function history()
    {
        return $this->hasMany(LabourWorkHistory::class, "labour_id", "l_id");
    }

    public function pulmonary()
    {
        $fy = FyYear::first();
        return $this->hasOne(PulmonaryLabour::class, "l_id", "l_id")->where("fy_year", $fy->year)->withoutGlobalScopes();
    }

    public function disabledLabour()
    {
        $fy = FyYear::first();
        return $this->hasOne(DisableLabour::class, "l_id", "l_id")->where("fy_year", $fy->year)->withoutGlobalScopes();
    }

    public function deceaseLabour()
    {
        $fy = FyYear::first();
        return $this->hasOne(DeceasedLabour::class, "labour_id", "l_id")->where("fy_year", $fy->year)->withoutGlobalScopes();
    }

    public function grantedPulmonary()
    {
        $fy = FyYear::first();
        return $this->hasOne(PulmonaryLabour::class, "l_id", "l_id")->where("status", "approved")->withoutGlobalScopes();
    }

    public function grantedDisabledLabour()
    {
        $fy = FyYear::first();
        return $this->hasOne(DisableLabour::class, "l_id", "l_id")->where("status", "approved")->withoutGlobalScopes();
    }

    public function grantedDeceaseLabour()
    {
        $fy = FyYear::first();
        return $this->hasOne(DeceasedLabour::class, "labour_id", "l_id")->where("status", "approved")->withoutGlobalScopes();
    }

    public function doc_verfied()
    {
        return $this->hasOne(User::class, "id", "doc_verify_by");
    }

    public function marriageGrants()
    {
        return $this->hasMany(MarriageGrant::class, "l_id", "l_id");
    }

    public function pulmonaryGrants()
    {
        return $this->hasMany(PulmonaryLabour::class, "l_id", "l_id");
    }

    public function deceasedGrants()
    {
        return $this->hasMany(DeceasedLabour::class, "labour_id", "l_id");
    }

    public function disableGrants()
    {
        return $this->hasMany(DisableLabour::class, "l_id", "l_id");
    }

    public function pulmonaryAnnualReport()
    {
        return $this->hasMany(PulmonaryAnnualReport::class, "l_id", "l_id");
    }

}
