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
                    <label for="no">Head No:</label>
                    <input type="text" class="form-control @error('no') is-invalid @enderror" id="no"
                        name="no" placeholder="Head No" value="{{ old('no', isset($row) ? $row->no : '') }}" />
                    @error('no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="title">Head Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" placeholder="Title" value="{{ old('title', isset($row) ? $row->title : '') }}" />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="head_type">Head Type</label>
                    <select required class="form-control @error('head_type') is-invalid @enderror" id="head_type"
                        name="head_type">
                        <option value="regular"
                            {{ old('head_type', isset($row) ? ($row->head_type == 'regular' ? 'selected' : '') : '') }}>
                            Regular</option>
                        <option value="welfare"
                            {{ old('head_type', isset($row) ? ($row->head_type == 'welfare' ? 'selected' : '') : '') }}>
                            Welfare</option>
                    </select>
                    @error('head_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="object_head_id">Parent Head</label>
                    <select class="form-control @error('object_head_id') is-invalid @enderror" id="object_head_id"
                        name="object_head_id">
                        <option value="">None</option>
                        @foreach (\App\Models\ObjectHead::all() as $type)
                            <option value="{{ $type['id'] }}"
                                {{ old('object_head_id', isset($row) ? ($row->object_head_id == $type['id'] ? 'selected' : '') : '') }}>
                                {{ $type['no'] . ' ' . $type['title'] }}</option>
                        @endforeach
                    </select>
                    @error('object_head_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="leaf" value="true"
                            {{ old('leaf', isset($row) ? ($row->leaf ? 'checked' : '') : '') }} id="leaf">
                        <label class="custom-control-label" for="leaf">Leaf</label>
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
