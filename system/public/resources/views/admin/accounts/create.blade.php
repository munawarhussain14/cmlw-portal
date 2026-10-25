@extends('admin.layouts.app')

@section('styles')
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- Summernote -->
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- mask -->
    <script src="{{ asset('assets/admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            // Summernote
            $(".select2").select2({
                theme: 'bootstrap4'
            });
            $('#summernote').summernote();
            $('input[type="file"]').change(function() {
                var fileName = this.files[0].name;
                $(this).parent().find(".custom-file-label").text(fileName);
            });

            $('#date').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
        })
    </script>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
            {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
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
                    <label for="title">Title:</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" placeholder="Title" value="{{ old('title', isset($row) ? $row->title : '') }}" />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="account_no">Account No</label>
                    <input type="text" class="form-control @error('account_no') is-invalid @enderror" id="account_no"
                        name="account_no" placeholder="Account No"
                        value="{{ old('account_no', isset($row) ? $row->account_no : '') }}" />
                    @error('account_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="bank">Bank</label>
                    <input type="text" class="form-control @error('bank') is-invalid @enderror" id="bank"
                        name="bank" placeholder="Bank" value="{{ old('bank', isset($row) ? $row->bank : '') }}" />
                    @error('bank')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">Type</label>
                    <select required class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                        <option value="regular"
                            {{ old('type', isset($row) ? ($row->type == 'regular' ? 'selected' : '') : '') }}>
                            Regular</option>
                        <option value="welfare"
                            {{ old('type', isset($row) ? ($row->type == 'welfare' ? 'selected' : '') : '') }}>
                            Welfare</option>
                    </select>
                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="active" value="true"
                            {{ old('active', isset($row) ? ($row->active ? 'checked' : '') : '') }} id="active">
                        <label class="custom-control-label" for="active">Active</label>
                    </div>
                </div>
            </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </form>
    </div>
@endsection
