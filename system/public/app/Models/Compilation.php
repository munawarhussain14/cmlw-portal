<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SchemeScope;
use Illuminate\Support\Facades\Auth;

class Compilation extends Model
{
    use HasFactory;

    public function office()
    {
        return $this->hasOne(Office::class, "id", "office_id");
    }


    public function credit()
    {
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            ->where("type", "credit")
            ->where("office_id", $this->office_id)
            ->sum("amount");

        return $sum;
    }

    public function debit()
    {
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            ->where("type", "debit")
            ->where("office_id", $this->office_id)
            ->sum("amount");

        return $sum;
    }

    public function exp($date, $id = -1)
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            // ->whereMonth("created_at", "=", $month)
            // ->whereYear("created_at", "=", $year)
            ->where("type", "debit")
            ->where("ref_id", $this->id)
            ->where("ref", "compilations")
            ->where("office_id", $this->office_id)
            ->sum("amount");

        return $sum;
    }

    public function progressive($date, $id = -1, $ref = "none")
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            // ->whereMonth("created_at", "=", $month)
            // ->whereYear("created_at", "=", $year)
            ->where("type", "debit")
            ->where("ref_id", $id)
            ->where("ref", $ref)
            ->where("office_id", $this->office_id)
            ->sum("amount");

        return $sum;
    }
}