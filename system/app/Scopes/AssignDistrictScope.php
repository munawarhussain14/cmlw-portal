<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\District;
use Auth;

class AssignDistrictScope implements Scope
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

        if (Auth::getUser()->hasRole("super-admin") || Auth::getUser()->hasRole("admin") || Auth::getUser()->hasRole("supervision")) {
        } else {
            $office_id = 0;
            if(Auth::getUser()->staff){
                if(Auth::getUser()->staff->access==="department"){
                    return;
                }
                $office_id = Auth::getUser()->staff->office->id;
            }
            //echo "<h1>".$office_id."</h1>";
            $id = Auth::getUser()->id;
            $districts = District::where("assign_id", $office_id)->where("province", "khyber pakhtunkhwa")->pluck("d_id")->toArray();
            $builder->whereIn('lease_district_id', $districts);
        }
    }
}