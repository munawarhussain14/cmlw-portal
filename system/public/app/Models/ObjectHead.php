<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ObjectHead extends Model
{
    use HasFactory;
    protected $table = "object_heads_tables";
    protected $primaryKey = "id";

    public function parent()
    {
        return $this->hasOne(ObjectHead::class, "id", "object_head_id");
    }

    public function credit($office_id=0)
    {
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            ->where("type", "credit")
            ->where("office_id", $office_id)
            // ->where("office_id", Auth::getUser()->staff->office->id)
            ->sum("amount");

        return $sum;
    }

    public function debit($office_id)
    {
        $fy = FyYear::first();
        $sum = Budget::where("object_head_id", $this->id)
            ->where("fy_year", $fy->year)
            ->where("type", "debit")
            ->where("office_id", $office_id)
            // ->where("office_id", Auth::getUser()->staff->office->id)
            ->sum("amount");

        return $sum;
    }

    public function exp($office_id,$date, $id = -1, $ref = "none")
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
            ->where("office_id", $office_id)
            // ->where("office_id", Auth::getUser()->staff->office->id)
            ->sum("amount");

        return $sum;
    }

    public function prograssive($office_id,$date, $id = -1, $ref = "none")
    {
        $fy = FyYear::first();
        $sum = Prograssive::where("object_head_id", $this->id)
            ->where("expenditure_month", $date)
            ->where("compilation_id", $id)
            ->where("office_id", $office_id)
            // ->where("office_id", Auth::getUser()->staff->office->id)
            ->sum("amount");

        return $sum;
    }

    public function progressiveReal($date, $id = -1, $ref = "none")
    {
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $fy = FyYear::first();

        $compilation = Compilation::
                whereMonth("expenditure_month", "<=", $month)
                ->whereYear("expenditure_month", "<=", $year)
                ->whereMonth("expenditure_month", ">=", 7)
                ->whereYear("expenditure_month", ">=", $year-1)
                ->pluck("id");


        $sum = Budget::where("object_head_id", $this->id)
        ->where("fy_year", $fy->year)
        ->where("type", "debit")
        ->whereIn("ref_id", $compilation)
        // ->where("ref_id", $id)
        ->where("ref", $ref)
        ->where("office_id", Auth::getUser()->staff->office->id)->sum("amount");

        return $sum;
    }
}