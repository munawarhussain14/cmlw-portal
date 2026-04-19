<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeceasedLabour;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Labour;
use App\Models\FyYear;
use App\Models\Scholarship;
use App\Models\Diploma;
use App\Models\DisableLabour;
use App\Models\PulmonaryLabour;
use App\Models\MarriageGrant;
use App\Models\Compilation;
use App\Models\District;
use Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    private $params = [
        "singular_title" => "Dashboard",
        "plural_title" => "Dashboard",
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateAgeEligibility(){
        //Update age
        Labour::withoutGlobalScopes()
        ->where('dob', '<', Carbon::now()->subYears(60)->toDateString())
        ->where('labour_status', '!=', 'overage')
        ->update([
            'labour_status' => 'overage'
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $fy = FyYear::first();

        $this->updateAgeEligibility();

        // dd(Labour::where("lease",1)->count());
        $data["labour"]["total"] = Labour::count();

        $data["scholarship"]["total"] = Scholarship::whereIn("category", ["General", "Other"])
            ->leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["prof"]["total"] = Scholarship::whereIn("category", ["Medical", "Engineering"])
            ->leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["special"]["total"] = Scholarship::where("category", "Special")
            ->leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["top"]["total"] = Scholarship::where("category", "Top")
            ->leftJoin("children", "scholarship_apply.s_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["diploma"]["gems"]["total"] = Diploma::where("scheme_id", 7)
            ->leftJoin("children", "diplomas.child_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["diploma"]["lapidary"]["total"] = Diploma::where("scheme_id", 8)
            ->leftJoin("children", "diplomas.child_id", "=", "children.id")
            ->leftJoin("labours", "labours.l_id", "=", "children.father_id")->where("fy_year", $fy->year)->count();

        $data["disabled"]["total"] = 0;//DisableLabour::where("fy_year", $fy->year)->leftJoin("labours", "labours.l_id", "=", "disabled_labour.l_id")->count();

        $data["pulmonary"]["total"] = 0;//PulmonaryLabour::where("fy_year", $fy->year)->leftJoin("labours", "labours.l_id", "=", "pulmonary_labour.l_id")->count();

        $data["deceased"]["total"] = 0;//DeceasedLabour::where("fy_year", $fy->year)->leftJoin("labours", "labours.l_id", "=", "death_grants.labour_id")->count();

        $data["marriage"]["total"] = 0;//MarriageGrant::where("fy_year", $fy->year)->leftJoin("labours", "labours.l_id", "=", "marriage_grant.l_id")->count();

        $tasks = $this->tasks();

        $params = $this->params;
        return view('admin.dashboard', compact("params", "data","tasks"));
    }

    function tasks(){
        $user = Auth::getUser();
        $tasks = [];

        if($user->name==="Administrator"){
            $compilations = Compilation::where("status","<>","complete")->get();
        }else{
            $compilations = Compilation::where("office_id",$user->staff->office->id)->where("status","<>","complete")->get();
        }
        foreach($compilations as $compilation){
            $date = new Carbon($compilation->expenditure_month);
            $date = $date->format('F Y');
            array_push($tasks,[
                "title"=>"Compilation",
                "message"=>"For the month of <b>$date</b>",
                "link"=>route('admin.compilations.show',["compilation"=>$compilation->id])
            ]);
        }
        
        return $tasks;
    }

}
