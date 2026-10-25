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
                    <div class="col-6">
                        <div class="form-group">
                            <label for="expenditure_month">Expenditure Month:</label>
                            <input type="date" class="form-control @error('expenditure_month') is-invalid @enderror"
                                id="expenditure_month" name="expenditure_month" placeholder="Enter English Title"
                                value="{{ old('expenditure_month', isset($row) ? $row->expenditure_month : '') }}" />
                            @error('expenditure_month')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="scheme_type_id">Scheme Type</label>
                            <select required class="form-control @error('status') is-invalid @enderror" id="status"
                                name="status">
                                <option value="pending"
                                    {{ old('status', isset($row) ? ($row->status == 'pending' ? 'selected' : '') : '') }}>
                                    Pending</option>
                                <option value="in-progress"
                                    {{ old('status', isset($row) ? ($row->status == 'in-progress' ? 'selected' : '') : '') }}>
                                    In-Progress</option>
                                <option value="complete"
                                    {{ old('status', isset($row) ? ($row->status == 'complete' ? 'selected' : '') : '') }}>
                                    Complete</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="office">Office</label>
                            <select required class="form-control @error('office_id') is-invalid @enderror" id="office_id"
                                name="office_id">
                                @foreach (\App\Models\Office::all() as $office)
                                    <option value="{{ $office->id }}"
                                        {{ old('office_id', isset($row) ? ($row->office_id == $office->id ? 'selected' : '') : '') }}>
                                        {{ $office->name . ', ' . $office->officeDistrict->name }}</option>
                                @endforeach
                            </select>
                            @error('office_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
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
