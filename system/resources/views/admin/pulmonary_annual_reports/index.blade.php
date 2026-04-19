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
                    <h3 class="card-title">Pulmonary annual test reports</h3>
                    <div class="card-header-actions">
                        <a href="{{ route('admin.labours.show', ['labour' => $labour->l_id]) }}" class="card-header-action">
                            <i class="fa fa-arrow-left"></i> Back to labour
                        </a>
                        @canany(['update-labours', 'pulmonary-test-report'])
                            &nbsp;|&nbsp;
                            <a href="{{ route('admin.labours.pulmonary-annual-reports.create', ['labour' => $labour->l_id]) }}"
                                class="card-header-action">
                                <i class="fa fa-plus"></i> Add report
                            </a>
                        @endcanany
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($reports->isEmpty())
                        <p class="p-3 mb-0 text-muted">No reports recorded yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Test date</th>
                                        <th>FY year</th>
                                        <th>Severity</th>
                                        <th>Remarks</th>
                                        <th class="text-right" style="width: 160px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ $report->test_date }}</td>
                                            <td>{{ $report->fy_year }}</td>
                                            <td>{{ $report->severity_level }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($report->remarks, 80) }}</td>
                                            <td class="text-right">
                                                @canany(['update-labours', 'pulmonary-test-report'])
                                                    <a href="{{ route('admin.labours.pulmonary-annual-reports.edit', ['pulmonary_annual_report' => $report->id]) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-pencil-alt"></i> Edit
                                                    </a>
                                                    <form class="d-inline"
                                                        action="{{ route('admin.labours.pulmonary-annual-reports.destroy', ['pulmonary_annual_report' => $report->id]) }}"
                                                        method="post"
                                                        onsubmit="return confirm('Delete this report?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endcanany
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
