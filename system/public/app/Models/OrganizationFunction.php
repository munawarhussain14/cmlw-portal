<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrganizationFunction extends Model
{
    use HasFactory;
    protected $table = "organization_functions";
    protected $primaryKey = "id";

    public function organization()
    {
        return $this->hasOne(Organization::class, "id", "org_id");
    }

    public function function()
    {
        return $this->hasOne(ObjectHead::class, "id", "object_id");
    }

}
