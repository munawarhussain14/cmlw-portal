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
                    {{-- <label for="expenditure_month">Expenditure Month:</label> --}}
                    <input type="hidden" class="form-control @error('expenditure_month') is-invalid @enderror"
                        id="expenditure_month" name="expenditure_month" placeholder="Enter English Title"
                        value="{{ old('expenditure_month', isset($row) ? $row->expenditure_month : '') }}" />
                    @error('expenditure_month')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            {{-- <div class="col-6">
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
    </div> --}}
    <div class="col-12">
        <h4>Expenditure for the Month of {{ $expenditure_date->format('F Y') }}</h4>
    </div>
    <div class="col-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Object Head</th>
                    <th>Expenditure</th>
                    <th>Prograssive</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budget_heads as $head)
                <tr>
                    <td>{{ $head->no }}-{{ $head->title }}</td>
                    <td>
                        <div class="form-group">
                            <input type="text" {{ $row->status === 'complete' ? 'readonly' : '' }}
                                class="form-control @error('budget') is-invalid @enderror" id="budget"
                                name='budget[{{ $head->id }}]' placeholder="Budget"
                                value="{{ old('budget', $head->exp($row->office_id,$row->expenditure_month, $row->id, 'compilations')) }}" />
                            @error('budget')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" {{ $row->status === 'complete' ? 'readonly' : '' }}
                                class="form-control @error('prograssive') is-invalid @enderror" id="budget"
                                name='prograssive[{{ $head->id }}]' placeholder="Prograssive"
                                value="{{ old('prograssive', $head->prograssive($row->office_id,$row->expenditure_month, $row->id, 'prograssive')) }}" />
                            @error('prograssive')
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
    </div>
    <!-- /.card-body -->

    @if ($row->status !== 'complete')
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    @endif
</form>
</div>
@endsection