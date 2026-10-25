@extends('admin.layouts.app')

@push('styles')
    <style>
        .urdu {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="{{asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js')}}"></script> --}}
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.js') }}"></script>

    <script>
        $(function() {
            // $('[data-mask]').inputmask();
            $(".cnic").mask('00000-0000000-0');
            $(".cell").mask('0000-0000000');
        });
    </script>
@endpush

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $params['singular_title'] }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

        <form id="data-form"
            action="{{ isset($row) ? route($params['route'] . '.update', $parm) : route($params['route'] . '.store') }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($row))
                @method('put')
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Select the Registration Type<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>
                                        رجیسریشن کا انتخاب کریں
                                    </label>
                                </div>
                            </div>
                            <select id="doa_select" name="purpose" required
                                class="form-control @error('purpose')'is-invalid'@enderror">
                                <option disabled="" selected="">Select Purpose</option>
                                <option {{ old('purpose', isset($row) ? $row->purpose : '') == 'labour' ? 'selected' : '' }}
                                    value="labour">Active Mine Labour (<span class="urdu"> فعال کان کن </span>) </option>
                                <option
                                    {{ old('purpose', isset($row) ? $row->purpose : '') == 'deceased labour' ? 'selected' : '' }}
                                    value="deceased labour">Labour Died in Mine Accident (<span class="urdu"> مائن حادثے
                                        میں فوت شدہ کان کن </span>)</option>
                                <option
                                    {{ old('purpose', isset($row) ? $row->purpose : '') == 'permanent disabled' ? 'selected' : '' }}
                                    value="permanent disabled">Permanent Disabled Labour due to Mine Accident(<span
                                        class="urdu"> مائن حادثے کی وجہ سے مستقل معذور کان کن </span>)</option>
                                <option
                                    {{ old('purpose', isset($row) ? $row->purpose : '') == 'occupational desease' ? 'selected' : '' }}
                                    value="occupational desease">Mine Labour with Occupational Pulmonary Disease (<span
                                        class="urdu">
                                        پیشہ ور پلمونری بیماری کا شکار کان کن مزدور
                                    </span>)</option>
                            </select>

                            @error('purpose')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6 {{ !old('purpose', isset($row) ? $row->purpose : '') || old('purpose', isset($row) ? $row->purpose : '') == 'labour' ? 'hide' : '' }}"
                        id="doa_date">
                        @include('admin.layouts.partials.date', [
                            'name' => 'doa',
                            'title' => 'Date of Accident',
                            'date' => $row->doa ? $row->doa : null,
                        ])
                    </div>

                    <div class="col-sm-6 {{ !old('purpose') || old('purpose') != 'deceased labour' ? 'hide' : '' }}"
                        id="death_date">
                        @include('admin.layouts.partials.date', [
                            'name' => 'death',
                            'title' => 'Date of Death',
                            'date' => $row->death_date,
                        ])
                    </div>

                    <div class="col-12">
                        <p id="labour_msg" class="{{ old('purpose') != 'labour' ? 'hide' : '' }}">
                            <!-- For Labour Message -->
                        </p>
                        <p id="deceased_msg" class="{{ old('purpose') != 'deceased labour' ? 'hide' : '' }}">
                            <!-- For Deceased Labour Message -->
                        </p>
                        <p id="disabled_msg" class="{{ old('purpose') != 'permanent disabled' ? 'hide' : '' }}">
                            <!-- For Permanent Disabled -->
                        </p>
                        <p id="disease_msg" class="{{ old('purpose') != 'occupational desease' ? 'hide' : '' }}">
                            <!-- For Disease Message -->
                        </p>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Personal Information</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu">ذاتی معلومات</span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Labour Name<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span> مزدور کا نام
                                    </label>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="name" placeholder="Name"
                                value="{{ old('name', isset($row) ? $row->name : '') }}" name="name" />
                            @error('name')
                                <div class="text-danger error-message">
                                    {!! $message !!}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Labour CNIC No<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span> مزدورکا شناختی کارڈ نمبر
                                    </label>
                                </div>
                                <input type="text" name="cnic" class="cnic digit-only form-control" id="cnic"
                                    readonly value="{{ old('cnic', isset($row) ? $row->cnic : '') }}"
                                    placeholder="00000-0000000-0" />
                                @error('cnic')
                                    <small class="text-danger" style="width: 100%;">
                                        {!! $message !!}
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Father Name<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>والد کا نام
                                    </label>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="fatherName" placeholder="Father Name"
                                value="{{ old('fathername', isset($row) ? $row->father_name : '') }}"
                                name="fathername" />
                            @error('fathername')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-6">
                        @include('admin.layouts.partials.date', [
                            'name' => 'dob',
                            'title' => 'Date of Birth',
                            'date' => $row->dob,
                        ])
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-7 col-7">
                                    <label class="english">
                                        Mobile No (Primary Non-Convertable)<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-5 col-5 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span> (مزدورکا موبائل نمبر(پہلا
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="cell_no_primary" class="cell form-control" id="contact"
                                value="{{ old('cell_no_primary', isset($row) ? $row->cell_no_primary : '') }}"
                                placeholder="0300-0000000" />

                            @error('cell_no_primary')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Mobile No (Secondary)
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        مزدورکا موبائل نمبر (دوسرا)
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="cell_no_secondary" class="cell form-control"
                                id="cell_no_secondary"
                                value="{{ old('cell_no_secondary', isset($row) ? $row->cell_no_secondary : '') }}"
                                placeholder="0300-0000000" />

                            @error('cell_no_secondary')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Gender<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>صنف
                                    </label>
                                </div>
                            </div>
                            <select name="gender" class="form-control" id="gender">
                                <option disabled="" selected="">Select Gender</option>
                                <option {{ old('gender', isset($row) ? $row->gender : '') == 'male' ? 'selected' : '' }}
                                    value="male">Male</option>
                                <option {{ old('gender', isset($row) ? $row->gender : '') == 'female' ? 'selected' : '' }}
                                    value="female">Female</option>
                            </select>

                            @error('gender')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Domicile District<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span> ڈ ڈومیسائل ضلع</label>
                                </div>
                            </div>
                            <select name="domicile_district" class="form-control">
                                <option disabled="" selected="">Select District</option>
                                @foreach (\App\Models\District::withoutGlobalScopes()->get() as $district)
                                    <option
                                        {{ old('domicile_district', isset($row) ? $row->domicile_district : '') == $district->d_id ? 'selected' : '' }}
                                        value="{{ $district->d_id }}">{{ $district->name }}</option>
                                @endforeach
                            </select>

                            @error('domicile_district')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Married<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>شادی شدہ
                                    </label>
                                </div>
                            </div>
                            <div class="form-check">
                                <!--data-dest="#family-info-container" data-show-value="yes"-->
                                    <input class="form-check-input position-static"
                                        {{ old('married', isset($row) ? $row->married : '') == 'yes' ? 'checked' : '' }}
                                        name="married" type="radio" value="yes"> Yes/ <span class="urdu">جی
                                        ہاں</span>
                            </div>
                            <div class="form-check">
                                <!--data-dest="#family-info-container" data-show-value="yes"-->
                                    <input class="form-check-input position-static"
                                        {{ old('married', isset($row) ? $row->married : '') == 'no' ? 'checked' : '' }}
                                        name="married" type="radio" value="no"> No/ <span
                                        class="urdu">نہیں</span>
                            </div>
                            @error('married')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        EOBI<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>کیا آپ "ای او بی آئی" سے رجسٹرڈ ہیں؟ </label>
                                </div>
                            </div>
                            <div class="form-check">
                                <input data-dest="#eobi_no" data-show-value="yes"
                                    class="form-check-input position-static" name="eobi"
                                    {{ old('eobi', isset($row) ? $row->eobi : '') == 'yes' ? 'checked' : '' }}
                                    type="radio" value="yes"> Yes/ <span class="urdu">جی ہاں</span>
                            </div>
                            <div class="form-check">
                                <input data-dest="#eobi_no" data-show-value="yes"
                                    class="form-check-input position-static" name="eobi"
                                    {{ old('eobi', isset($row) ? $row->eobi : '') == 'no' ? 'checked' : '' }}
                                    type="radio" value="no"> No/ <span class="urdu">نہیں</span>
                            </div>
                            @error('eobi')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6 {{ old('eobi') != 'yes' ? 'hide' : '' }}" id="eobi_no">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        EOBI No<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>ای او بی آئی" نمبر"
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="eobi_no" class="form-control" placeholder="EOBI No"
                                value="{{ old('eobi_no', isset($row) ? $row->eobi_no : '') }}" />

                            @error('eobi_no')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-sm-12">
                        <h5 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Work Information</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu"> کام کے بارے میں معلومات </span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h5>
                    </div>


                    <div class="col-sm-6">
                        @include('admin.layouts.partials.date', [
                            'name' => 'work_from',
                            'title' => 'Work From',
                            'date' => $row->work_from,
                        ])
                    </div>

                    <div class="col-sm-6">
                        @include('admin.layouts.partials.date', [
                            'name' => 'work_end_date',
                            'title' => 'Work End Date',
                            'date' => $row->work_end_date,
                        ])
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Nature of Work<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>
                                    </label>
                                </div>
                            </div>
                            <select name="work_type" class="form-control" data-dest="#other_work_type">
                                <option disabled="" selected="">Select Work Type</option>
                                @foreach ($worktypes as $key => $value)
                                    <option
                                        {{ $value['wt_id'] == old('work_type', isset($row) ? $row->work_id : '') ? 'selected' : '' }}
                                        value="{{ $value['wt_id'] }}">{{ $value['title'] }}</option>
                                @endforeach
                                <!-- <option {{ old('work_type') == 'other' ? 'selected' : '' }} value="other">
                                    <span class="english">Other</span>
                                    <span class="urdu" style="float: right;"> اگر آپ کا کام فہرست میں موجود نہیں ہے
                                    </span>
                                </option> -->
                            </select>
                            @error('work_type')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <!-- <div class="col-sm-6 {{ old('work_type') != 'other' ? 'hide' : '' }}" id="other_work_type">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Other Nature of Work
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>کان میں آپ کے کام کی قسم
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="other_work_type" class="form-control"
                                value="{{ old('other_work_type') }}" placeholder="Other Nature of Work" />

                            @error('other_work_type')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div> -->

                    <div class="col-sm-12">
                        <h5 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Permanent Address</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu"> (شناختی کارڈ کے مطابق)مستقل پتہ </span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h5>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Village/Town Address
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        گاؤں / قصبے کا پتہ
                                    </label>
                                </div>
                            </div>
                            <textarea name="perm_address" class="form-control" placeholder="Permanent Address">{{ old('perm_address', isset($row) ? $row->perm_address : '') }}</textarea>

                            @error('perm_address')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        District
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        ضلع
                                    </label>
                                </div>
                            </div>
                            <select name="perm_district" class="form-control">
                                <option disabled="" selected="">Select District</option>
                                @foreach (\App\Models\District::withoutGlobalScopes()->get() as $key => $value)
                                    <option
                                        {{ $value['d_id'] == old('perm_district', isset($row) ? $row->perm_district_id : '') ? 'selected' : '' }}
                                        value="{{ $value['d_id'] }}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>

                            @error('perm_district')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <h5 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Postal Address</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu"> ڈاک کا پتا </span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h5>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Postal Address
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        گاؤں / قصبے کا پتہ
                                    </label>
                                </div>
                            </div>
                            <textarea name="postal_address" class="form-control" value="{{ old('postal_address') }}"
                                placeholder="Postal Address">{{ old('postal_address', isset($row) ? $row->postal_address : '') }}</textarea>

                            @error('postal_address')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        District
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        ضلع
                                    </label>
                                </div>
                            </div>
                            <select name="postal_district" class="form-control">
                                <option disabled="" selected="">Select District</option>
                                @foreach (\App\Models\District::withoutGlobalScopes()->get() as $key => $value)
                                    <option
                                        {{ $value['d_id'] == old('postal_district', isset($row) ? $row->postal_district_id : '') ? 'selected' : '' }}
                                        value="{{ $value['d_id'] }}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>

                            @error('postal_district')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Lease Holder</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu"> لیز ہولڈر </span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h2>
                    </div>
                    <div id="other_lease_holder" class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Lease Holder/Company Name<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>لیز ہولڈر / کمپنی کا نام
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="lease_owner_name" class="form-control" id="lease_owner_name"
                                value="{{ old('lease_owner_name', isset($row) ? $row->lease_owner_name : '') }}"
                                placeholder="Lease Holder Name" />

                            @error('lease_owner_name')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Mineral Title<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>لیز نمبر
                                    </label>
                                </div>
                            </div>
                            <input type="text" name="mineral_title" class="form-control" id="lease_no"
                                value="{{ old('mineral_title', isset($row) ? $row->mineral_title?$row->mineral_title:$row->lease_no : '') }}"
                                placeholder="Mineral Title" />

                            @error('mineral_title')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Lease District<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span> لیزکس ضلع میں واقع ہے؟
                                    </label>
                                </div>
                            </div>
                            <select name="lease_district" class="form-control">
                                <option disabled="" selected="">Select District</option>
                                @foreach ($districts as $key => $value)
                                    <option
                                        {{ $value['d_id'] == old('lease_district', isset($row) ? $row->lease_district_id : '') ? 'selected' : '' }}
                                        value="{{ $value['d_id'] }}">{{ $value['name'] }}</option>
                                @endforeach
                            </select>
                            @error('lease_district')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Mineral<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>معدنیات
                                    </label>
                                </div>
                            </div>
                            <select data-dest="#other_mineral_container" name="mineral" class="form-control">
                                <option disabled="" selected="">Select Mineral</option>
                                @foreach ($minerals as $key => $value)
                                    { }}
                                    <option
                                        {{ $value['m_id'] == old('mineral', isset($row) ? $row->mineral_id : '') ? 'selected' : '' }}
                                        value="{{ $value['m_id'] }}">{{ $value['name'] }}</option>
                                @endforeach
                                <!-- <option {{ old('mineral') == 'other' ? 'selected' : '' }} value="other">Other</option> -->
                            </select>

                            @error('mineral')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div>
                    <!-- <div class="col-sm-6 {{ old('mineral') != 'other' ? 'hide' : '' }}" id="other_mineral_container">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Other Mineral
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>دیگر معدنیات
                                    </label>
                                </div>
                            </div>
                            <input type="text" id="other_mineral" name="other_mineral" class="form-control"
                                value="{{ old('other_mineral') }}" placeholder="Other Mineral" />

                            @error('other_mineral')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div> -->

                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Lease Address<span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        <span class="required">*</span>پتہ
                                    </label>
                                </div>
                            </div>
                            <textarea name="lease_address" class="form-control" value="{{ old('lease_address') }}" placeholder="Lease Address">{{ old('lease_address', isset($row) ? $row->lease_address : '') }}</textarea>

                            @error('lease_address')
                                <small class="text-danger">
                                    {!! $message !!}
                                </small>
                            @enderror

                        </div>
                    </div>
                </div>

                <!-- Bank Information Section -->
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-title">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <span class="english">Bank Information</span>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <span class="urdu">بینک کی معلومات</span>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                        </h2>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Bank
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        بینک کا نام
                                    </label>
                                </div>
                            </div>
                            <select class="form-control @error('bank_id') is-invalid @enderror"
                            id="bank_id"
                            name="bank_id">
                                <option value="">Select Bank</option>
                                @if(isset($banks))
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->b_id }}"
                                        {{ old('bank_id', (isset($row)) ? $row->bank_id : '') == $bank->b_id ? 'selected' : '' }}>
                                            {{ $bank->name }}{{$row->b_id}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('bank_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        Other Bank Name
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        دوسرے بینک کا نام
                                    </label>
                                </div>
                            </div>
                            <input type="text" class="form-control @error('other_bank_name') is-invalid @enderror"
                            id="other_bank_name"
                            name="other_bank_name"
                            placeholder="Enter Bank Name (if Other selected)"
                            value="{{ old('other_bank_name',(isset($row))?$row->other_bank_name:"") }}"
                            />
                            @error('other_bank_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> -->
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-6">
                                    <label class="english">
                                        IBAN
                                    </label>
                                </div>
                                <div class="col-sm-6 col-6 text-right">
                                    <label class="urdu">
                                        آئی بی اے این
                                    </label>
                                </div>
                            </div>
                            <input type="text" class="form-control @error('iban') is-invalid @enderror"
                            id="iban"
                            name="iban"
                            placeholder="Enter IBAN (e.g., PK36SCBL0000001123456702)"
                            value="{{ old('iban',(isset($row))?$row->iban:"") }}"
                            maxlength="34"
                            />
                            @error('iban')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
