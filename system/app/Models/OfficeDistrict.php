<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;
use App\Scopes\DistrictScope;

class OfficeDistrict extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $primaryKey = "d_id";

}