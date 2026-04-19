<div class="accordion" id="actionTabContainer">
    <form id="data-form" action="{{ route('admin.scholarships.action', ['id' => $row->id, 'action' => 'status']) }}"
        method="post" enctype="multipart/form-data">
        @csrf
        <div class="card card-danger">
            <div class="card-header p-0" id="actionTab">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                    data-target="#actionTabBody" aria-expanded="true" aria-controls="collapseOne">
                    <h3 class="card-title">Action</h3>
                    <div class="card-header-actions">
                        Last Action Taken By:
                        {{ $data->doc_verfied ? $data->doc_verfied->short_desg . ' - ' . $data->doc_verfied->name : 'None' }}{{ ', Updated at : ' . date('d-m-Y h:m:s a', strtotime($data->updated_at)) }}
                    </div>
                </button>
            </div>
            @php
                $show = '';
                if (isset($errors) && ($errors->has('status') || $errors->has('cnic_status') || $errors->has('form_b_status') || $errors->has('schedule_a_status') || $errors->has('marks_sheet_status') || $errors->has('remarks'))) {
                    $show = 'show';
                }
            @endphp
            <div class="collapse {{ $show }}" id="actionTabBody" aria-labelledby="actionTab"
                data-parent="#actionTabContainer">
                <div class="card-body">
                    @php
                        $check = false;
                        if ($row->student->gender == 'none') {
                            $check = true;
                        }
                        if (in_array($row->class, [10, 11, 12]) && !$row->roll_no) {
                            $check = true;
                        }
                        
                        if (in_array($row->class, [13, 16]) && !$row->roll_no) {
                            if ($row->class == 13 && $row->session == 1) {
                                $check = true;
                            } elseif (in_array($row->class, [16]) && ($row->session == 1 || $row->session == 2)) {
                                $check = true;
                            }
                        }
                    @endphp
                    <div class="row">
                        <div class="col-md-12">
                            @can('application-approval')
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'status',
                                    'label' => 'Status',
                                    'value' => $data->status,
                                    'id' => 'status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'pending', 'text' => 'Pending'],
                                        ['value' => 'in process', 'text' => 'In Process'],
                                        ['value' => 'document verified', 'text' => 'Document Verified'],
                                        ['value' => 'approved', 'text' => 'Approved'],
                                        ['value' => 'rejected', 'text' => 'Rejected'],
                                    ],
                                ])
                            @else
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'status',
                                    'label' => 'Status',
                                    'value' => $data->status,
                                    'id' => 'status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'pending', 'text' => 'Pending'],
                                        ['value' => 'in process', 'text' => 'In Process'],
                                        ['value' => 'document verified', 'text' => 'Document Verified'],
                                        ['value' => 'rejected', 'text' => 'Rejected'],
                                    ],
                                ])
                                @endif
                            </div>
                            <div class="col-md-3">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'cnic_status',
                                    'label' => 'CNIC Status',
                                    'value' => $data->cnic_status,
                                    'id' => 'cnic_status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'approved', 'text' => 'Approved'],
                                        ['value' => 'missing', 'text' => 'Missing'],
                                        ['value' => 'not clear', 'text' => 'Not Clear'],
                                        ['value' => 'not attested', 'text' => 'Not Attested'],
                                        ['value' => 'in valid', 'text' => 'In Valid'],
                                    ],
                                ])
                            </div>

                            <div class="col-md-3">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'form_b_status',
                                    'label' => 'Form-B Status',
                                    'value' => $data->form_b_status,
                                    'id' => 'form_b_status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'approved', 'text' => 'Approved'],
                                        ['value' => 'missing', 'text' => 'Missing'],
                                        ['value' => 'not clear', 'text' => 'Not Clear'],
                                        ['value' => 'not attested', 'text' => 'Not Attested'],
                                        ['value' => 'in valid', 'text' => 'In Valid'],
                                    ],
                                ])
                            </div>

                            {{-- <div class="col-md-4">
                    @include('admin.layouts.partials.form.select', [
                        'name' => 'appointment_letter_status',
                        'label' => 'Appointment Letter Status',
                        'value' => $data->appointment_letter_status,
                        'id' => 'appointment_letter_status',
                        'required' => true,
                        'options' => [
                            ['value' => 'approved', 'text' => 'Approved'],
                            ['value' => 'missing', 'text' => 'Missing'],
                            ['value' => 'not clear', 'text' => 'Not Clear'],
                            ['value' => 'not attested', 'text' => 'Not Attested'],
                            ['value' => 'in valid', 'text' => 'In Valid'],
                        ],
                    ])
                </div> --}}

                            <div class="col-md-3">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'schedule_a_status',
                                    'label' => 'Schedule A Status',
                                    'value' => $data->schedule_a_status,
                                    'id' => 'schedule_a_status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'approved', 'text' => 'Approved'],
                                        ['value' => 'missing', 'text' => 'Missing'],
                                        ['value' => 'not clear', 'text' => 'Not Clear'],
                                        ['value' => 'not attested', 'text' => 'Not Attested'],
                                        ['value' => 'in valid', 'text' => 'In Valid'],
                                    ],
                                ])
                            </div>

                            <div class="col-md-3">
                                @include('admin.layouts.partials.form.select', [
                                    'name' => 'marks_sheet_status',
                                    'label' => 'Marks Sheet',
                                    'value' => $data->marks_sheet_status,
                                    'id' => 'marks_sheet_status',
                                    'required' => true,
                                    'options' => [
                                        ['value' => 'approved', 'text' => 'Approved'],
                                        ['value' => 'missing', 'text' => 'Missing'],
                                        ['value' => 'not clear', 'text' => 'Not Clear'],
                                        ['value' => 'not attested', 'text' => 'Not Attested'],
                                        ['value' => 'in valid', 'text' => 'In Valid'],
                                    ],
                                ])
                            </div>

                            <div class="col-md-12">
                                @include('admin.layouts.partials.form.textarea', [
                                    'name' => 'remarks',
                                    'label' => 'Remarks',
                                    'value' => $data->remarks,
                                    'id' => 'remarks',
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($check)
                            <p class="error">Please update Student data i.e Gender, Roll No and Board</p>
                        @endif
                        <button type="submit" @if ($check) disabled @endif
                            class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
