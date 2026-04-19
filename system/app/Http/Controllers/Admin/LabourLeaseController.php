<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\District;
use App\Models\Minerals;
use App\Models\MineralTitle;
use App\Models\MiningArea;
use App\Models\LabourWorkHistory;
use App\Http\Requests\LabourLeaseRequest;

class LabourLeaseController extends AdminController
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
    }

    public function getMineralTitle(Request $request)
    {
        $district = $request->district;
        $keywords = $request->keywords;
        $mineral_titles = [];

        $mineral_titles = MineralTitle::selectRaw("id, CONCAT(code,' - ',parties) as text")
            ->when($district, function ($query, $district) {
                return $query->where("district", $district);
            })
            ->when($keywords, function ($query, $keywords) {
                return $query->whereRaw("(code like '%$keywords%' or parties like '%$keywords%')");
            })->take(5)->get();

        return response()->json(["data" => $mineral_titles]);
    }

    public function getMiningArea(Request $request)
    {
        $district = $request->district;
        $lease = $request->lease;
        $keywords = $request->keywords;
        $data = [];

        $data = MiningArea::selectRaw("id, CONCAT(code,' - ',parties) as text")
            ->when($district, function ($query, $district) {
                return $query->where("district", $district);
            })
            ->when($keywords, function ($query, $keywords) {
                return $query->whereRaw("(code like '%$keywords%' or parties like '%$keywords%')");
            })->take(5)->get();

        return response()->json(["data" => $data]);
    }

    public function updateLabourLeaseDetail(LabourLeaseRequest $request, $labour)
    {
        $labourWorkHistory = new LabourWorkHistory();

        $mining_area = null;

        $mineral_title = MineralTitle::find($request->mineral_title);

        if ($request->has("mining_area")) {
            $mining_area = MiningArea::find($request->mining_area);
            if ($mining_area->district == $mineral_title->district) {
                $labourWorkHistory->mining_area_id = $mining_area->id;
                $labourWorkHistory->area_code = $mining_area->code;
            }
        }

        $labourWorkHistory->labour_id = $labour;
        $labourWorkHistory->mineral_title_id = $mineral_title->id;
        $labourWorkHistory->code = $mineral_title->code;
        $labourWorkHistory->start = $request->start;
        if ($request->has("end") && $request->end != null) {
            $labourWorkHistory->start = $request->end;
        }

        $district = District::where("name", $mineral_title->district)->first();

        $labourWorkHistory->added_by = "portal user";
        $labourWorkHistory->save();

        $labour = Labour::find($labour);
        $labour->lease_id = $mineral_title->id;
        $labour->lease_owner_name = $mineral_title->parties;
        $labour->lease_no = $mineral_title->code;
        $labour->mineral_title = $mineral_title->code;
        if ($request->has("mining_area")) {
            $labour->mining_area = $mining_area->code;
        }
        $labour->lease_district_id = $district->d_id;
        $labour->save();

        return redirect()->back()->with("message", "Lease Updated!");
    }
}
