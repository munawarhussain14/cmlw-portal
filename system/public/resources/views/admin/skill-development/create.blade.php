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
    <div class="row">
        <div class="col-12"></div>
        <div class="col-md-3 order-last">
            @include('admin.scholarships.partials.showStudent', ['student' => $row->student])
            @include('admin.layouts.partials.showLabour', [
                'title' => 'Father Detail',
                'labour' => $row->student->father,
            ])
        </div>
        <div class="col-md-9">
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
                        <div id="children">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2 class="section-title">
                                        <div class="row">
                                            <div class="col-sm-6 col-6">
                                                <span class="english">Student Personal Information</span>
                                            </div>
                                            <div class="col-sm-6 col-6 urdu">
                                                <span class="urdu">
                                                    طالب علم کے ذاتی کوائف
                                                </span>
                                            </div>
                                        </div>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        @include('admin.scholarships.partials.studentForm', ['student' => $row->student])
                        <div class="col-lg-12">
                            <br>
                            <h2 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Educational Information</span>
                                    </div>
                                </div>
                            </h2>
                        </div>
                        <div id="class-container" class="col-lg-6">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'qualification',
                                'label' => 'Qualification',
                                'value' => $row->qualification,
                                'id' => 'qualification',
                                'required' => true,
                                'options' => [
                                    ['value' => 'Matric', 'text' => 'Matric'],
                                    ['value' => 'Intermediate', 'text' => 'Intermediate'],
                                    ['value' => 'Diploma', 'text' => 'Diploma'],
                                    ['value' => 'Bachelor', 'text' => 'Bachelor'],
                                    ['value' => 'Master', 'text' => 'Master'],
                                ],
                            ])
                        </div>

                        <div class="col-lg-12">
                            <div class="">
                                <div class="row marks">
                                    <div class="col-lg-12">
                                        <br>
                                        <h5 class="section-title">
                                            <span class="english">Please provide your <span style="color:red;"><span
                                                        class="result-class">Previous Class/Semester</span> Result</span>
                                                and
                                                Passing Year</span>
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        @include('admin.layouts.partials.form.input', [
                                            'name' => 'matric_obtained_marks',
                                            'label' => 'Matric Obtained Marks/CGPA',
                                            'value' => $row->matric_obtained_marks,
                                            'id' => 'matric_obtained_marks',
                                        ])
                                    </div>
                                    <div class="col-md-6">
                                        @include('admin.layouts.partials.form.input', [
                                            'name' => 'matric_total_marks',
                                            'label' => 'Matric Total Marks/CGPA',
                                            'value' => $row->matric_total_marks,
                                            'id' => 'matric_total_marks',
                                        ])
                                    </div>
                                    <div class="col-md-4">
                                        @include('admin.layouts.partials.form.input', [
                                            'name' => 'passing_year',
                                            'label' => 'Passing Year',
                                            'value' => $row->passing_year,
                                            'id' => 'passing_year',
                                        ])
                                    </div>
                                    <div class="col-md-4">
                                        @include('admin.layouts.partials.form.input', [
                                            'name' => 'roll_no',
                                            'label' => 'Roll No',
                                            'value' => $row->roll_no,
                                            'id' => 'roll_no',
                                        ])
                                    </div>
                                    <div class="col-md-4">
                                        @include('admin.layouts.partials.form.input', [
                                            'name' => 'board',
                                            'label' => 'Board',
                                            'value' => $row->board,
                                            'id' => 'board',
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Did you apply for any other Scholarship Scheme in other organization?<span
                                                class="required">*</span>
                                        </label>
                                        <label class="urdu">
                                            <span class="required">*</span>
                                            کیا آپ نے کسی اور اسکالرشپ اسکیم کے لئے دوسری ادارے میں درخواست دی ہے؟
                                        </label>
                                    </div>
                                    <div class="col-sm-6 col-6 urdu">

                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input position-static" name="other_apply"
                                        @if (old('other_apply', $row->other_apply) == 'yes') checked="" @endif type="radio" value="yes">
                                    Yes/
                                    <span class="urdu">جی ہاں</span>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input position-static"
                                        @if (old('other_apply', $row->other_apply) == 'no') checked="" @endif name="other_apply"
                                        type="radio" value="no"> No/ <span class="urdu">نہیں</span>
                                </div>
                                @error('other_apply')
                                    <label class="error">{!! $message !!}</label>
                                @enderror
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#cnic").mask("00000-0000000-0");

            var classNames = {
                "1": "",
                "2": "1st Class",
                "3": "2nd Class",
                "4": "3rd Class",
                "5": "4th Class",
                "6": "5th Class",
                "7": "6th Class",
                "8": "7th Class",
                "9": "8th Class",
                "10": "9th Class",
                "11": "Matric",
                "12": "1st Year",
                "13": "Previous Class",
                "13-1": "Matric",
                "13-2": "1st Year",
                "13-3": "2nd Year",
                "16": "Previous Class",
                "16-1": "FSc/DAE/D.COM",
                "16-2": "FSc/DAE/D.COM",
                "16-3": "2nd Semester",
                "16-4": "3rd Semester",
                "16-5": "4th Semester",
                "16-6": "5th Semester",
                "16-7": "6th Semester",
                "16-8": "7th Semester",
                "18": "Previous Class",
                "18-1": "Bachelor Degree",
                "18-2": "1st Semester",
                "18-3": "2nd Semester",
                "18-4": "3rd Semester",
            };

            $(".loading-container").fadeOut(function() {
                $("#child-form").fadeIn();
            });

            var current_class = $("#class").val();
            var dae_year = $(".dae_year").val();
            var current_semester = $("#semester").val();
            var disable = $("#disabled").val();
            var special_institute = $("#special_institute").val();
            let boardGrads = [10, 11, 12];

            if (boardGrads.includes(current_class) || (current_class == 13 && dae_year == 1) || (current_class ==
                    16 && (current_semester == 1 || current_semester == 2))) {
                $(".roll_no_container").fadeIn();
            }

            if (current_class == "9" || current_class == "10") {
                if (!$(".matric").is("visiable")) {
                    $(".fsc,.dae,.bachelor").slideUp();
                    $(".matric").slideDown();
                }

                if (current_class == "10") {
                    $(".roll_no_container").slideDown();
                }
            } else if (current_class == "11" || current_class == "12") {
                if (!$(".fsc").is("visiable")) {
                    $(".matric,.dae,.bachelor").slideUp();
                    $(".fsc,.roll_no_container").slideDown();
                }
            } else if (current_class == "13") {
                if (!$(".dae").is("visiable")) {
                    $(".matric,.fsc,.bachelor").slideUp();
                    $(".dae").slideDown();
                }
            } else if (current_class == "16" || current_class == "18") {
                if (current_class == "16") {
                    // alert(this.value);
                    $("#semester").attr("max", 8);
                } else if (current_class == "18") {
                    // alert(this.value);
                    var str = parseInt($("#semester").val());

                    if (str > 4)
                        $("#semester").val("");
                    $("#semester").attr("max", 4);
                }

                if (!$(".bachelor").is("visiable")) {
                    $(".matric,.dae,.fsc").slideUp();
                    $(".bachelor").slideDown();
                }
            } else {
                $(".matric,.dae,.fsc,.bachelor").slideUp();
            }

            if (current_class == null || current_class == "1") {
                $(".marks-container").slideUp();
            } else {
                $(".marks-container").slideDown();
            }

            var name;

            $(".dae_year,#semester").on("change", function() {
                var temp = name + "-" + this.value;

                current_class = $("#class").val();

                $(".result-class").text(classNames[temp]);

                if (current_class == 16 && (this.value == 1 || this.value == 2)) {
                    $(".roll_no_container").slideDown();
                } else if (current_class == 13 && this.value == 1) {
                    $(".roll_no_container").slideDown();
                } else {
                    $(".roll_no_container").slideUp();
                }

            });

            const changeClass = (name) => {

                $(".result-class").text(classNames[name]);

                if (boardGrads.includes(parseInt(name))) {
                    $(".roll_no_container").slideDown();
                } else {
                    $(".roll_no_container").slideUp();
                }

                if (name == "9" || name == "10") {
                    if (!$(".matric").is("visiable")) {
                        $(".fsc,.dae,.bachelor").slideUp();
                        $(".matric").slideDown();
                    }
                } else if (name == "11" || name == "12") {
                    if (!$(".fsc").is("visiable")) {
                        $(".matric,.dae,.bachelor").slideUp();
                        $(".fsc").slideDown();
                    }
                } else if (name == "13") {
                    if (!$(".dae").is("visiable")) {
                        $(".matric,.fsc,.bachelor").slideUp();
                        $(".dae").slideDown();
                    }

                    let dae_year = $(".dae_year").val();
                    if (dae_year == 1) {
                        $(".roll_no_container").slideDown();
                    }
                } else if (name == "16" || name == "18") {
                    if (name == "16") {
                        $("#semester").empty();
                        $("#semester").append(
                            $("<option></option>")
                            .attr("value", "")
                            .attr("selected", "true")
                            .attr("disabled", "true")
                            .text("Select Semester"));

                        for (var i = 1; i <= 8; i++) {
                            $("#semester").append(
                                $("<option></option>").attr("value", i).text(i)
                            );
                        }
                    } else if (name == "18") {
                        $("#semester").empty();
                        $("#semester").append(
                            $("<option></option>")
                            .attr("value", "")
                            .attr("selected", "true")
                            .attr("disabled", "true")
                            .text("Select Semester"));
                        for (var i = 1; i <= 4; i++) {
                            $("#semester").append(
                                $("<option></option>").attr("value", i).text(i)
                            );
                        }
                    }

                    if (!$(".bachelor").is("visiable")) {
                        $(".matric,.dae,.fsc").slideUp();
                        $(".bachelor").slideDown();
                    }
                } else {
                    $(".matric,.dae,.fsc,.bachelor").slideUp();
                }

                if (name == "1") {
                    $(".marks-container").slideUp();
                } else {
                    $(".marks-container").slideDown();
                }
            }

            $("#class").on("change", function() {
                changeClass(this.value);
            });


            $("#covid_container input[type=radio]").on("change", function() {

                var class_instance = $("#class").val();

                var dae_year_instance = $(".dae_year").val();

                var current_semester_instance = $("#semester").val();

            });


            $(".cnic").mask("00000-0000000-0");
            $("#passing_year").mask("2000");


            @if ($row && $row->preApply)
                var grade = "{{ $row->preApply->class }}";
                switch (grade) {
                    case "1st Year":
                        grade = 11;
                        break;
                    case "2nd Year":
                        grade = 12;
                        break;
                    case "DAE":
                        grade = 13;
                        break;
                    case "Bachelor":
                        grade = 16;
                        break;
                    case "Master":
                        grade = 18;
                        break;
                }
                var temp = parseInt(grade);
                if (temp == 18)
                    temp++;
                $("#class").val(temp);
                changeClass(temp);
            @endif

            $("#special_institute").on("change", function() {
                if (this.value == "yes") {
                    var current_class = $("#class").val();
                    $("#class").find("option[value='1']").attr("selected", true);
                    $("#class-container,.marks-container").slideUp();
                } else {
                    $("#class-container").slideDown();
                }
            });

        });
    </script>
@endpush
