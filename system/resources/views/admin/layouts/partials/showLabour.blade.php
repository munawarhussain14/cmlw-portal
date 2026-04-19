<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">{{ $title ? $title : 'Labour Detail' }}</h3>
        <div class="card-header-actions">
            @if ($labour->labour_status == 'approved')
                <a class="card-header-action">
                    <i class="fa fa-check"></i>
                </a>
            @elseif($labour->labour_status == 'rejected')
                <a class="card-header-action">
                    <i class="fa fa-times"></i>
                </a>
            @elseif($labour->labour_status == 'pending')
                <a class="card-header-action">
                    <i class="fa fa-clock"></i>
                </a>
            @endif
            &nbsp;|&nbsp;
            <a href="{{ route('admin.labours.show', ['labour' => $labour->l_id]) }}" target="_blank"
                class="card-header-action"><i class="fa fa-eye"></i> Show</a>
            @can('update-labours')
                &nbsp;|&nbsp;
                <a href="{{ route('admin.labours.edit', ['labour' => $labour->l_id]) }}" target="_blank"
                    class="card-header-action">
                    <i class="fa fa-edit"></i> Edit
                </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Name',
                    'value' => $labour->name,
                ])
            </div>
            <div class="col-lg-12">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'CNIC',
                    'value' => $labour->cnic,
                    'id' => 'cnic',
                ])
            </div>
            <div class="col-lg-12">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Cell No Primary',
                    'value' => $labour->cell_no_primary,
                    'id' => 'cell_no_primary',
                ])
            </div>

            <div class="col-md-6">
                <div class="row">
                    <div class="col-lg-12">
                        @include('admin.layouts.partials.form.field', [
                            'label' => 'Date of Birth',
                            'value' => $labour->dob,
                            'id' => 'dob',
                        ])
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Gender',
                    'value' => ucwords($labour->gender),
                    'id' => 'gender',
                ])
            </div>
            <div class="col-lg-12">
                @php
                $purpose = "None";
                $purpose = $labour->purpose == 'labour' ? 'Active Mine Labour' : 'Labour Died in Mine Accident';
                $purpose = $labour->purpose == 'deceased labour' ? 'Labour Died in Mine Accident' : $purpose;
                $purpose = $labour->purpose == 'permanent disabled' ? 'Permanent Disabled Labour due to Mine Accident' : $purpose;
                $purpose = $labour->purpose == 'occupational desease' ? 'Mine Labour with Occupational Pulmonary Disease' : $purpose;
                @endphp
                @include('admin.layouts.partials.form.field', [
                    'label' => 'Status',
                    'value' => $purpose,
                    'id' => 'purpose',
                ])
            </div>
        </div>
    </div>
</div>
