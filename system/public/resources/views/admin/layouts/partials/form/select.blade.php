<div class="form-group @error($name) has-danger @enderror">
    @include('admin.layouts.partials.form.label')
    <select name="{{ $name }}" {{ isset($required) ? 'required' : '' }} {{ isset($readonly) ? 'readonly' : '' }}
        {{ isset($disabled) ? 'disabled' : '' }} class="{{ isset($class) ? $class : 'form-control' }}"
        id="{{ isset($id) ? $id : '' }}">
        <option disabled="" selected="">Select {{ $label }}</option>
        @foreach ($options as $option)
            <option {{ old($name, isset($value) ? $value : '') == $option['value'] ? 'selected' : '' }}
                value="{{ $option['value'] }}">
                {{ isset($option['text']) ? $option['text'] : $option['value'] }}
            </option>
        @endforeach
    </select>
    @error($name)
        <label class="error">{!! $message !!}</label>
    @enderror
</div>
