@extends('admin.layouts.app')

@section('styles')
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <script>
        $(function() {
            $("#change_password").on("change", function() {
                // alert("Test");
                if ($(this).is(":checked")) {
                    $("#password,#password_confirmation").attr("disabled", false);
                } else {
                    $("#password,#password_confirmation").attr("disabled", true);
                }
            });
        })
    </script>
@endpush


@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ isset($row) ? route($params['route'] . '.update', $parm) : route($params['route'] . '.store') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($row))
                @method('put')
            @endif
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" required placeholder="Enter Name"
                        value="{{ old('name', isset($row) ? $row->name : '') }}" />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="title">Designation</label>
                    <input type="text" class="form-control @error('designation') is-invalid @enderror" id="name"
                        name="designation" required placeholder="Enter Designation"
                        value="{{ old('designation', isset($row) ? $row->designation : '') }}" />
                    @error('designation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="title">Short Designation</label>
                    <input type="text" class="form-control @error('short_desg') is-invalid @enderror" id="name"
                        name="short_desg" required placeholder="Enter Short Designation"
                        value="{{ old('short_desg', isset($row) ? $row->short_desg : '') }}" />
                    @error('short_desg')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" type="email" required placeholder="Enter Email"
                        value="{{ old('email', isset($row) ? $row->email : '') }}" />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-check">
                    <input type="checkbox" {{ old('change_password') ? 'checked' : '' }} name="change_password"
                        class="form-check-input" id="change_password">
                    <label class="form-check-label" for="change_password">Change Password</label>
                </div>
                <div class="form-group">
                    <label for="title">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required {{ old('change_password') ? '' : 'disabled' }}
                        placeholder="Enter Password" value="{{ old('password') }}" />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="title">Confirm Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password_confirmation" required {{ old('change_password') ? '' : 'disabled' }}
                        placeholder="Confirm Password" value="{{ old('password') }}" />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Office</label>
                    <select name="office_id" class="form-control select2 @error('office_id') is-invalid @enderror"
                        style="width: 100%;">
                        <option selected disabled>Select Office</option>
                        @foreach ($offices as $office)
                            <option
                                {{ old('office_id', isset($row) && $row->office_id ? $row->office_id : '') == $office->id ? 'selected' : '' }}
                                value="{{ $office->id }}">
                                {{ $office->address . ', ' . $office->officeDistrict->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('office_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>User Type</label>
                    <select name="type" class="form-control select2 @error('type') is-invalid @enderror"
                        style="width: 100%;">
                        <option selected disabled>Select User Type</option>
                        <option {{ old('type', isset($row) && $row->type ? $row->type : '') == 'staff' ? 'selected' : '' }}
                            value="staff">
                            Staff
                        </option>
                        <option
                            {{ old('type', isset($row) && $row->type ? $row->type : '') == 'superadmin' ? 'selected' : '' }}
                            value="superadmin">
                            Super Admin
                        </option>

                    </select>
                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                {{-- <div class="form-group">
                    <label for="avatar">Profile Image</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="attachment" accept="application/pdf" value="{{ old('avatar') }}"
                                class="custom-file-input" id="avatar">
                            <label class="custom-file-label" for="avatar">{{ old('attachment', 'Choose file') }}</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Upload</span>
                        </div>
                    </div>
                    @error('avatar')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div> --}}
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection
