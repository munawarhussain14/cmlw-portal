<div class="accordion" id="actionTabContainer">
    <form id="data-form" action="{{ route('admin.grants.disable.status', ['id' => $row->id]) }}" method="post"
        enctype="multipart/form-data">
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
                if (isset($errors) && ($errors->has('status') || $errors->has('cnic_status') || $errors->has('appointment_letter_status') || $errors->has('schedule_a_status') || $errors->has('enquiry_report_status') || $errors->has('xray_status') || $errors->has('disability_cert_status') || $errors->has('remarks'))) {
                    $show = 'show';
                }
            @endphp
            <div class="collapse {{ $show }}" id="actionTabBody" aria-labelledby="actionTab"
                data-parent="#actionTabContainer">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'status',
                                'label' => 'Status',
                                'value' => $data->status,
                                'id' => 'status',
                                'required' => true,
                                'options' => [
                                    ['value' => 'pending', 'text' => 'Pending'],
                                    ['value' => 'in process', 'text' => 'In Process'],
                                    // ['value' => 'document verified', 'text' => 'Document Verified'],
                                    ['value' => 'approved', 'text' => 'Approved'],
                                    ['value' => 'rejected', 'text' => 'Rejected'],
                                ],
                            ])
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
                        </div>


                        <div class="col-md-3">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'enquiry_report_status',
                                'label' => 'Inspector Inquary Report',
                                'value' => $data->enquiry_report_status,
                                'id' => 'enquiry_report_status',
                                'required' => true,
                                'options' => [
                                    ['value' => 'approved', 'text' => 'Approved'],
                                    ['value' => 'missing', 'text' => 'Missing'],
                                    ['value' => 'not clear', 'text' => 'Not Clear'],
                                    // ['value' => 'not attested', 'text' => 'Not Attested'],
                                    ['value' => 'in valid', 'text' => 'In Valid'],
                                ],
                            ])
                        </div>

                        <div class="col-md-3">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'xray_status',
                                'label' => 'X-Rays or Photo',
                                'value' => $data->xray_status,
                                'id' => 'xray_status',
                                'required' => true,
                                'options' => [
                                    ['value' => 'approved', 'text' => 'Approved'],
                                    ['value' => 'missing', 'text' => 'Missing'],
                                    ['value' => 'not clear', 'text' => 'Not Clear'],
                                    // ['value' => 'not attested', 'text' => 'Not Attested'],
                                    ['value' => 'in valid', 'text' => 'In Valid'],
                                ],
                            ])
                        </div>


                        <div class="col-md-3">
                            @include('admin.layouts.partials.form.select', [
                                'name' => 'disability_cert_status',
                                'label' => 'Disability Certificate',
                                'value' => $data->disability_cert_status,
                                'id' => 'disability_cert_status',
                                'required' => true,
                                'options' => [
                                    ['value' => 'approved', 'text' => 'Approved'],
                                    ['value' => 'missing', 'text' => 'Missing'],
                                    ['value' => 'not clear', 'text' => 'Not Clear'],
                                    // ['value' => 'not attested', 'text' => 'Not Attested'],
                                    ['value' => 'in valid', 'text' => 'In Valid'],
                                ],
                            ])
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Disability Percent</label>

                                <div class="input-group">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div> --}}
                                    <input type="number" max="100" min="0" class="form-control" required
                                        name="disability_percent"
                                        value="{{ old('disability_percent', isset($data->disability_percent) ? $data->disability_percent : '') }}" />
                                </div>
                                @error('disability_percent')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <!-- /.input group -->
                            </div>
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
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
