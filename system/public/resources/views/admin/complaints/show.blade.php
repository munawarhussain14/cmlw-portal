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
                    <div class="form-group">
                        <label for="title">Complaint No</label>
                        <p>{{ $row->complaint_no }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="title">Subject</label>
                        <p>{{ $row->subject }}</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="name">Description</label>
                        <p>{{ $row->content }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <p>{{ $row->name }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="name">Contact</label>
                        <p>{{ $row->contact }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="name">CNIC</label>
                        <p>{{ $row->cnic }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="name">Email</label>
                        <p>{{ $row->email }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <p>{{ $row->status }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="parties">Remarks</label>
                        <p>{{ $row->remarks?$row->remarks:"None" }}</p>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card-footer">
            @can('update-users')
                <a class="btn btn-primary"
                    href="{{ route('admin.complaints.edit', ['complaint' => $row->id]) }}">Edit</a>
            @endcan
        </div>
    </div>
@endsection
