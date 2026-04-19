@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('admin.layouts.partials.showLabour', [
                'title' => 'Labour',
                'labour' => $labour,
            ])
        </div>
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($row->id) ? 'Edit report' : 'New report' }}</h3>
                    <div class="card-header-actions">
                        <a href="{{ route('admin.labours.pulmonary-annual-reports.list', ['labour' => $labour->l_id]) }}"
                            class="card-header-action">
                            <i class="fa fa-list"></i> All reports
                        </a>
                    </div>
                </div>
                <form method="post"
                    action="{{ isset($row->id)
                        ? route('admin.labours.pulmonary-annual-reports.update', ['pulmonary_annual_report' => $row->id])
                        : route('admin.labours.pulmonary-annual-reports.store', ['labour' => $labour->l_id]) }}">
                    @csrf
                    @if (isset($row->id))
                        @method('PUT')
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.input', [
                                    'name' => 'test_date',
                                    'label' => 'Test date',
                                    'value' => old('test_date', $row->test_date ?? ''),
                                    'id' => 'test_date',
                                    'type' => 'date',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.input', [
                                    'name' => 'fy_year',
                                    'label' => 'Financial year',
                                    'value' => old('fy_year', $row->fy_year ?? $defaultFyYear ?? ''),
                                    'id' => 'fy_year',
                                    'type' => 'text',
                                    'required' => true,
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'severity_level',
                                    'label' => 'Severity level',
                                    'value' => old('severity_level', $row->severity_level ?? 'normal'),
                                    'id' => 'severity_level',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'normal', 'text' => 'Normal'],
                                        ['value' => 'Refer to Health Department', 'text' => 'Refer to Health Department'],
                                    ],
                                ])
                            </div>
                            <div class="col-md-12">
                                @include('admin.layouts.partials.form.textarea', [
                                    'name' => 'remarks',
                                    'label' => 'Remarks',
                                    'value' => old('remarks', $row->remarks ?? ''),
                                    'id' => 'remarks',
                                ])
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.labours.pulmonary-annual-reports.list', ['labour' => $labour->l_id]) }}"
                            class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
