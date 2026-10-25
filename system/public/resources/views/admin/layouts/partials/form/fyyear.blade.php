@can("change-financial-year")
@php
  $fyYears = \App\Models\FyYear::withoutGlobalScopes()->get();
  $options = [];
  foreach($fyYears as $year){
    array_push($options,["value"=>$year->getActualYear(),"text"=>$year->getActualYear()]);
  }
  
  @endphp

@include("admin.layouts.partials.form.select",
[
    "name"=>"fy_year",
    "label"=>"Financial Year",
    "id"=>"fy_year",
    "options"=>$options,
    "required"=>true,
    "value"=>$row->fy_year
])
@endcan