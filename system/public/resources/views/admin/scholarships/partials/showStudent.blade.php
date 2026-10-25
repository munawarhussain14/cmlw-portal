<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Child Detail</h3>
        <div class="card-header-actions">
            @can('update-children')
                <a href="{{ route('admin.children.edit', ['child' => $student->id]) }}" target="_blank"
                    class="card-header-action"><i class="fa fa-edit"></i> Edit</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Name',
                    'value' => $student->name,
                ])
            </div>
            <div class="col-md-12">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Form-B Registration No/CNIC',
                    'value' => $student->reg_no,
                    'id' => 'cnic',
                ])
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        @include('admin.layouts.partials.form.field', [
                            'label' => 'Date of Birth',
                            'value' => $student->dob,
                            'id' => 'dob',
                        ])
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Gender',
                    'value' => ucwords($student->gender),
                    'id' => 'gender',
                ])
            </div>
        </div>
    </div>
</div>
