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
                        <label for="title_eng">English Title English Title:</label>
                        <input type="text" class="form-control @error('title_eng') is-invalid @enderror" id="title_eng"
                            name="title_eng" placeholder="Enter English Title"
                            value="{{ old('title_eng', isset($row) ? $row->title_eng : '') }}" />
                        @error('title_eng')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="title_urdu">Urdu Title</label>
                        <input type="text" class="form-control @error('title_urdu') is-invalid @enderror" id="title_urdu"
                            name="title_urdu" placeholder="Enter Urdu Title"
                            value="{{ old('title_urdu', isset($row) ? $row->title_urdu : '') }}" />
                        @error('title_urdu')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="desc_eng">English Description</label>
                        <textarea type="text" class="form-control @error('desc_eng') is-invalid @enderror" id="desc_eng" name="desc_eng"
                            placeholder="Enter English Description">
                            {{ old('desc_eng', isset($row) ? $row->desc_eng : '') }}
                        </textarea>
                        @error('desc_eng')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="desc_urdu">Urdu Description</label>
                        <textarea type="text" class="form-control @error('desc_urdu') is-invalid @enderror" id="desc_urdu" name="desc_urdu"
                            placeholder="Enter Urdu Description">
                            {{ old('desc_urdu', isset($row) ? $row->desc_urdu : '') }}
                        </textarea>
                        @error('desc_urdu')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="scheme_type_id">Scheme Type</label>
                        <select required class="form-control @error('scheme_type_id') is-invalid @enderror" id="scheme_type_id"
                            name="scheme_type_id">
                            @foreach (\App\Models\SchemeType::all() as $type)
                                <option value="{{ $type['id'] }}"
                                    {{ old('scheme_type_id', isset($row) ? ($row->scheme_type_id == $type['id'] ? 'selected' : '') : '') }}>
                                    {{ $type['title_eng'] }}</option>
                            @endforeach
                        </select>
                        @error('scheme_type_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="start_from">Start Date</label>
                        <input type="date" class="form-control @error('start_from') is-invalid @enderror" id="start_from"
                            name="start_from" placeholder="Start Date"
                            value="{{ old('start_from', isset($row) ? $row->start_from : '') }}" />
                        @error('start_from')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="last_date">Last Date</label>
                        <input type="date" class="form-control @error('last_date') is-invalid @enderror" id="last_date"
                            name="last_date" placeholder="Last Date"
                            value="{{ old('last_date', isset($row) ? $row->last_date : '') }}" />
                        @error('last_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="url">Redirect URL</label>
                        <input type="text" class="form-control @error('url') is-invalid @enderror" id="url"
                            name="url" placeholder="Enter Url" value="{{ old('url', isset($row) ? $row->url : '') }}" />
                        @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="open" value="true"
                                {{ old('open', isset($row) ? ($row->open ? 'checked' : '') : '') }} id="open">
                            <label class="custom-control-label" for="open">Open</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="active" value="true"
                                {{ old('active', isset($row) ? ($row->active ? 'checked' : '') : '') }} id="active">
                            <label class="custom-control-label" for="active">Active</label>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
    </div>
    <!-- /.card-body -->
@endsection
