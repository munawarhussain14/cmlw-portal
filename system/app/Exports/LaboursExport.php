<?php

namespace App\Exports;

use App\Models\Labour;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaboursExport implements FromCollection, WithHeadings
{
    private $columns;
    private $status;
    private $districts;
    private $card_printed;

    function __construct($columns = [], $status = null, $districts = null, $card_printed = null)
    {
        $this->columns = $columns;
        $this->status = $status;
        $this->districts = $districts;
        $this->card_printed = $card_printed;
    }

    public function headings(): array
    {
        $header = [];
        foreach ($this->columns as $key => $column) {
            $head = "";
            if ($column == "l_id") {
                $head = "ID";
            } elseif ($column == "name") {
                $head = "Name";
            } elseif ($column == "father_name") {
                $head = "Father Name";
            } elseif ($column == "dob") {
                $head = "Date of Birth";
            } elseif ($column == "cnic") {
                $head = "CNIC";
            } elseif ($column == "gender") {
                $head = "Gender";
            } elseif ($column == "purpose") {
                $head = "Category";
            } elseif ($column == "work_id") {
                $this->columns[$key] = "(select title from work_types where wt_id = work_id) as work_type";
                $head = "Work Type";
            } elseif ($column == "cell_no_primary") {
                $head = "Cell No Primary";
            } elseif ($column == "cell_no_secondary") {
                $head = "Cell No Secondary";
            } elseif ($column == "domicile_district") {
                $this->columns[$key] = "(select name from districts where d_id = domicile_district) as domicile_district";
                $head = "Domicile";
            } elseif ($column == "lease_district_id") {
                $this->columns[$key] = "(select name from districts where d_id = lease_district_id) as lease_district";
                $head = "Lease District";
            } elseif ($column == "married") {
                $head = "Married";
            } elseif ($column == "lease_owner_name") {
                $head = "Lease Owner";
            } elseif ($column == "mineral_title") {
                $head = "Mineral Title";
            } elseif ($column == "card_printed") {
                $head = "Card Printed";
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
        $status = $this->status;
        $districts = $this->districts;
        $card_printed = $this->card_printed;
        $data = [];
        if (count($this->columns) > 0) {
            $data = Labour::selectRaw(join(",", $this->columns))
                ->when($districts, function ($query, $districts) {
                    return $query->whereIn("lease_district_id", explode(",", $districts));
                })
                ->when($status, function ($query, $status) {
                    return $query->where("labour_status", $status);
                })
                ->when($card_printed, function ($query, $card_printed) {
                    return $query->where("card_printed", $card_printed==="print");
                })
                
                // ->where("lease_district_id", 26)
                ->get();
        } else {
            $data = Labour::where("lease_district_id", 26)->get();
        }
        return $data;
    }
}
