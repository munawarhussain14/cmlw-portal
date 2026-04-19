<label for="{{(isset($id))?$id:""}}" class="english">
    {{$label}}@if(isset($required)&&$required)<span class="required">*</span>@endif
</label>