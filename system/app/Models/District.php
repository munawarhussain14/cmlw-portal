<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\DistrictScope;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = "d_id";
    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new DistrictScope);
    }

    public function lease_labours()
    {
        return $this->hasMany(Labour::class, "lease_district_id", "d_id")->withoutGlobalScopes();
    }

    public function labours()
    {
        return $this->hasMany(Labour::class, "perm_district_id", "d_id")->withoutGlobalScopes();
    }

    public function assignTo()
    {
        return $this->hasOne(Office::class, "id", "assign_id");
    }
}
