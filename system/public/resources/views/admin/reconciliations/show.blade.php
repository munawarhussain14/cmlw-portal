@extends('admin.layouts.app')

@section('content')
<div class="row mb-2">
    <div class="col-6">
        @if ($row->status != 'complete')
        <form action="{{ route($params['route'] . '.status', ['id' => $row->id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($row))
            @method('put')
            @endif
            <input type="hidden" name="status" value="complete" />
            <button type="submit" class="btn btn-primary">Mark as Complete</button>
        </form>
        @else
        @can('update-compilations-status')
        <form action="{{ route($params['route'] . '.status', ['id' => $row->id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @if (isset($row))
            @method('put')
            @endif
            <input type="hidden" name="status" value="in-progress" />
            <button type="submit" class="btn btn-warning">Mark as Pending</button>
        </form>
        @endcan
        @endif
    </div>
    <div class="col-6 text-right">
        <a target="_blank" href="{{ route($params['route'] . '.print', ['id' => $row->id]) }}"
            class="btn btn-success"><i class="fa fa-print"></i> Print</a>
        @include('admin.layouts.status', ['status' => $row->status])
    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $params['singular_title'] }}</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <h4>Expenditure for the Month of {{ $expenditure_date->format('F Y') }}</h4>
            </div>
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Object Head</th>
                            <th>Expendititure</th>
                            <th>Prograssive</th>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th>{{ $sum }}</th>
                            <th>{{ $prograssive }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budget_heads as $head)
                        @if ($head->credit($row->office_id) > 0)
                        <tr>
                            <td>{{ $head->no }}-{{ $head->title }}</td>
                            <td>
                                {{ $head->exp($row->office_id,$row->expenditure_month, $row->id, 'compilations') }}
                            </td>
                            <td>
                                {{ $head->prograssive($row->office_id,$row->expenditure_month, $row->id, 'prograssive') }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="card-footer">
        <a href="{{ route($params['route'] . '.edit', $parm) }}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
    </div>
</div>
@endsection