<?php

namespace App\Exports;

use App\Models\MarriageGrant;
use App\Models\FyYear;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarriageGrantsExport implements FromCollection, WithHeadings
{
    private $columns;
    private $status;
    private $districts;

    function __construct($columns = [], $status = null, $districts = null)
    {
        $this->columns = $columns;
        $this->status = $status;
        $this->districts = $districts;
    }

    public function headings(): array
    {
        $header = [];
        // dd($this->columns);
        foreach ($this->columns as $key => $column) {
            $head = "";
            if ($column == "marriage_grant.id") {
                $head = "ID";
            } elseif ($column == "labours.name as labour_name") {
                $head = "Labour Name";
            } elseif ($column == "children.name as child_name") {
                $head = "Daughter Name";
            } elseif ($column == "children.reg_no") {
                $head = "Daughter CNIC";
            } elseif ($column == "labours.dob") {
                $head = "Date of Birth";
            } elseif ($column == "labours.cnic") {
                $head = "CNIC";
            } elseif ($column == "labours.gender") {
                $head = "Gender";
            } elseif ($column == "labours.domicile_district") {
                $this->columns[$key] = "(select name from districts where d_id = labours.domicile_district) as domicile_district";
                $head = "Domicile District";
            } elseif ($column == "labours.cell_no_primary") {
                $head = "Cell No Primary";
            } elseif ($column == "marriage_grant.husband_name") {
                $head = "Husband Name";
            } elseif ($column == "marriage_grant.husband_cnic") {
                $head = "Husband CNIC";
            } elseif ($column == "marriage_grant.marriage_held_on") {
                $head = "Marraige Held In";
            } elseif ($column == "marriage_grant.fy_year") {
                $head = "Fy Year";
            } else {
                $head = $column;
            }

            array_push($header, $head);
        }

        return $header;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $fy = FyYear::first();
        $status = null;
        $districts = null;

        if ($this->status != "all") {
            $status = $this->status;
        }

        $districts = $this->districts;
        $data = [];
        if (count($this->columns) > 0) {
            $data = MarriageGrant::leftJoin("labours", "labours.l_id", "=", "marriage_grant.l_id")
                ->leftJoin("children", "marriage_grant.c_id", "=", "children.id")
                ->leftJoin("districts", "labours.lease_district_id", "=", "districts.d_id")
                ->selectRaw(join(",", $this->columns))
                ->when($status, function ($query, $status) {
                    return $query->where("status", $status);
                })
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("labours.lease_district_id", explode(",", $districts));
                })
                ->where("marriage_grant.fy_year", $fy->year)->get();
            // dd($data[0]);
        } else {
            $data = [];
        }
        return $data;
    }
}
