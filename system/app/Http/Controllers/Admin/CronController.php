<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\FyYear;
use Auth;
use Carbon\Carbon;

class CronController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {}

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
        $this->updateAgeEligibility();

        return response()->json([
            'message' => 'Cron Operation successful'
        ]);
    }

}
