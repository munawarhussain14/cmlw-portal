<div class="form-group @error($name) has-danger @enderror">
    @include("admin.layouts.partials.form.label")
<input 
type="{{(isset($type))?$type:"text"}}"
name="{{$name}}"
{{(isset($required))?"required":""}}
{{(isset($readonly))?"readonly":""}}
{{(isset($disabled))?"disabled":""}}
class="{{(isset($class))?$class:"form-control"}}" 
id="{{(isset($id))?$id:""}}" 
value="{{old($name,(isset($value))?$value:"")}}" 
step="any"
autocomplete="{{(isset($autocomplete))?$autocomplete:""}}"  
placeholder="{{(isset($placeholder))?$placeholder:""}}">
    @error($name)
       <label class="error">{!! $message !!}</label>
    @enderror
    @if(isset($note))
    <small class="form-text text-muted">{!!$note!!}</small>
    @endif
</div>