<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabourWorkHistory extends Model
{
    use HasFactory;
    protected $table = "labours_work_history";

    public function labour()
    {
        return $this->hasOne(Labour::class, "l_id", "labour_id");
    }

    public function mineral_title()
    {
        return $this->hasOne(MineralTitle::class, "id", "mineral_title_id");
    }

    public function mining_area()
    {
        return $this->hasOne(MiningArea::class, "id", "mineral_title_id");
    }
}
