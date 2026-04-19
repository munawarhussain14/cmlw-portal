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
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Object Head</th>
                                    <th>Original Budget</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($budget_heads as $head)
                                    <tr>
                                        <td>{{ $head->no }}-{{ $head->title }}</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control @error('budget') is-invalid @enderror"
                                                    id="budget" name='budget[{{ $head->id }}]' placeholder="Budget"
                                                    value="{{ old('budget', $head->credit($office_id)) }}" />
                                                @error('budget')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
