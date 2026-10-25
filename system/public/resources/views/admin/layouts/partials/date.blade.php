@php
$day = 0;
$month = 0;
$year = 0;
if($date){
    $day = date('d', strtotime($date));
    $month = date('m', strtotime($date));
    $year = date('Y', strtotime($date));
}
@endphp
<div class="form-group">
    <div class="row">
     <div class="col-sm-6 col-6">
       <label class="english">
         {{$title}}<span class="required">*</span> 
       </label>			
     </div>
     <div class="col-sm-6 col-6 text-right">
       <label class="urdu">
         <span class="required">*</span>
        </label>
     </div>
   </div>
   <div class="row">
     <div class="col-3">
       <!-- <label class="tip">Day</span> -->
           <select 
               {{(isset($disabled)&&$disabled)?"disabled":""}}
               name="{{$name}}_day"
               class="form-control">
                 <option selected="" disabled="">Day</option>
               @for($i=1;$i<=31;$i++)
               <option {{($i==old($name."_day",(isset($row))?$day:""))?"selected":""}} value="{{$i}}">{{$i}}</option>
               @endfor
             </select>
             @error('{{$name}}_day')
             <small class="text-danger">
               {!! $message !!}
             </small>
              @enderror
     </div>
     <div class="col-3">
       <!-- <label class="tip">Month</span> -->
           <select 
             name="{{$name}}_month"
             {{(isset($disabled)&&$disabled)?"disabled":""}}
             class="form-control">
             <option selected="" disabled="">Month</option>
             @for($i=1;$i<=12;$i++)
             <option {{($i==old($name."_month",(isset($row))?$month:""))?"selected":""}} value="{{$i}}">{{$i}}</option>
             @endfor
           </select>
           @error('{{$name}}_month')
           <small class="text-danger">
             {!! $message !!}
           </small>
            @enderror
     </div>
     <div class="col-3">
       <!-- <label class="tip">Year</span> -->
       <select 
         name="{{$name}}_year"
         {{(isset($disabled)&&$disabled)?"disabled":""}}
         class="form-control">
           <option selected="" disabled="">Year</option>
           @for($i=date("Y");$i>=1940;$i--)
           <option {{($i==old($name."_year",(isset($row))?$year:""))?"selected":""}} value="{{$i}}">{{$i}}</option>
           @endfor
         </select>
         @error('{{$name}}_year')
         <small class="text-danger">
           {!! $message !!}
         </small>
          @enderror
     </div>
   </div>
 </div>