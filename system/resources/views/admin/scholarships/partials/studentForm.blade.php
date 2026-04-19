<div class="row">

    <div class="col-lg-6">
        @include('admin.layouts.partials.form.input', [
            'name' => 'name',
            'label' => 'Name',
            'value' => $student->name,
            'required' => true,
        ])
    </div>
    <div class="col-lg-6">
        @include('admin.layouts.partials.form.input', [
            'name' => 'reg_no',
            'label' => 'Form-B Registration No/CNIC',
            'value' => $student->reg_no,
            'placeholder' => '00000-0000000-0',
            'id' => 'reg_no',
            'required' => true,
            'note' => 'Please enter child registration number which is mentioned in Form-B (00000-0000000-0)',
        ])
    </div>

    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-12">
                @include('admin.layouts.partials.date', [
                    'name' => 'dob',
                    'title' => 'Student Date of Birth',
                    'date' => $student->dob,
                ])
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        @include('admin.layouts.partials.form.select', [
            'name' => 'gender',
            'label' => 'Gender',
            'value' => $student->gender,
            'id' => 'gender',
            'required' => true,
            'options' => [['value' => 'male', 'text' => 'Male'], ['value' => 'female', 'text' => 'Female']],
        ])
    </div>

</div>
