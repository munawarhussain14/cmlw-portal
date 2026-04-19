<div class="accordion" id="actionTabContainer">
    <form id="data-form" action="{{ route('update-test-report', ['id' => $row->l_id]) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="card card-danger">
            <div class="card-header p-2" id="actionTab">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#actionTabBody" aria-expanded="true" aria-controls="collapseOne">
                    <h3 class="card-title">Test Report Action</h3>
                </button>
            </div>
            @php
                $show = '';
                if ($errors->has('test_date') ||
                    $errors->has('test_result_value') ||
                    $errors->has('severity_level') ||
                    $errors->has('test_remarks')) {
                    $show = 'show';
                }
            @endphp
            <div class="collapse {{ $show }}" id="actionTabBody" aria-labelledby="actionTab"
                data-parent="#actionTabContainer">
                <div class="card-body">
                    <div class="row">

                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.input', [
                                    'name' => 'test_date',
                                    'label' => 'Test Date',
                                    'value' => old('test_date', $data->test_date ?? ''),
                                    'id' => 'test_date',
                                    'type' => 'date',
                                    'required' => true,
                                ])
                            </div>
                        
                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.input', [
                                    'name' => 'test_result_value',
                                    'label' => 'Test Result Value',
                                    'value' => old('test_result_value', $data->test_result_value ?? ''),
                                    'id' => 'test_result_value',
                                    'type' => 'number',
                                    'required' => true,
                                ])
                            </div>
                        
                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'severity_level',
                                    'label' => 'Severity Level',
                                    'value' => old('severity_level', $data->severity_level ?? ''),
                                    'id' => 'severity_level',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'normal', 'text' => 'Normal'],
                                        ['value' => 'moderate', 'text' => 'Moderate'],
                                        ['value' => 'serious', 'text' => 'Serious'],
                                    ],
                                ])
                            </div>
                        
                            <div class="col-md-12">
                                @include('admin.layouts.partials.form.textarea', [
                                    'name' => 'test_remarks',
                                    'label' => 'Test Remarks',
                                    'value' => old('test_remarks', $data->test_remarks ?? ''),
                                    'id' => 'test_remarks',
                                ])
                            </div>
                        
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
