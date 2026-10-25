<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\District;
use Auth;

class DistrictScope implements Scope
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
        // dd($office_id);
        if (Auth::getUser()->hasRole("super-admin") || Auth::getUser()->hasRole("admin") || Auth::getUser()->hasRole("supervision")) {
        } else {
            $office_id = 0;
            if(Auth::getUser()->staff){
                if(Auth::getUser()->staff->access==="department"){
                    return;
                }

                $office_id = Auth::getUser()->staff->office->id;
            }

            $builder->where('assign_id', $office_id);
        }
    }
}