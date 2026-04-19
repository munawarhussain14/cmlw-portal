@extends('admin.layouts.app')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Object Head</th>
                                <th>Budget</th>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th>{{ $sum }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budget_heads as $head)
                                <tr>
                                    <td>{{ $head->no }}-{{ $head->title }}</td>
                                    <td>
                                        {{ $head->credit($office_id) }}
                                    </td>
                                </tr>
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
