<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\FyYear;
use App\Models\District;
use Auth;

class GrantLabourScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(Auth::getUser()->type==="superadmin"){
            return;
        }
        // $office_id = Auth::getUser()->office_id;
        $fy = FyYear::first();

        if (Auth::getUser()->hasRole("super-admin") || Auth::getUser()->hasRole("admin") || Auth::getUser()->hasRole("supervision")) {
            $builder->where('fy_year', $fy->year);
        } else {
            $office_id = 0;
            if(Auth::getUser()->staff){
                if(Auth::getUser()->staff->access==="department"){
                    return;
                }
                $office_id = Auth::getUser()->staff->office->id;
            }
            $id = Auth::getUser()->id;
            $districts = District::where("assign_id", $office_id)->where("province", "khyber pakhtunkhwa")->pluck("d_id")->toArray();
            // $builder->where('fy_year', $fy->year)->whereIn('labours.perm_district_id', $districts);
            
            $builder->where('fy_year', $fy->year)
            ->where(function ($query) use ($districts) {
                $query->where(function ($q) use ($districts) {
                    $q->where('districts.province','<>', 'khyber pakhtunkhwa')
                      ->whereIn('labours.lease_district_id', $districts);
                })->orWhere(function ($q) use ($districts) {
                    $q->where('districts.province', 'khyber pakhtunkhwa')
                      ->whereIn('labours.lease_district_id', $districts);
                    //   ->whereIn('labours.domicile_district', $districts);
                });
            });
        }
    }
}