@extends('admin.layouts.app')

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/qrcode/qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.10.377/build/pdf.min.js"></script>
    {{-- https://www.cssscript.com/qr-code-generator-logo-title/ --}}
    <script type="text/javascript">
        $(document).ready(function() {
            @if ($row->pdf_menu)
                var qrcode = new QRCode(document.getElementById("qrcode"),
                    "{{ route('download.pdf', ['restaurant_id' => $row->id]) }}");
                @if (!file_exists($row->qrcode))
                    setTimeout(function() {
                        let image = $("#qrcode img").attr("src");
                        $.post("{{ route('admin.restaurants.qrcode', ['restaurant_id' => $row->id]) }}", {
                                qrcode: image
                            },
                            function(result) {
                                console.log(result);
                                $("#qrcode-container img").attr("src", result.qrcode +
                                    "?datetime={{ date('Y.m.d-h:m:s') }}");
                                $(".loading").fadeOut(function() {
                                    $("#qrcode-container").fadeIn();
                                });
                            });
                    }, 1000);
                @else
                    $("#qrcode-container img").attr("src", "{{ url($row->qrcode) }}");
                    $(".loading").fadeOut(function() {
                        $("#qrcode-container").fadeIn();
                    });
                @endif
            @endif
        });
    </script>
@endpush

@section('content')
    <div class="row">
        @can('labour-action')
            <div class="col-12">
                <form id="data-form" action="{{ route('admin.labour.action', ['id' => $row->l_id]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card card-danger collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Action</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-header-actions">
                                Last Action Taken By:
                                {{ $row->doc_verfied ? $row->doc_verfied->short_desg . ' - ' . $row->doc_verfied->name : 'None' }}{{ ', Updated at : ' . date('d-m-Y h:m:s a', strtotime($row->updated_at)) }}
                            </div>
                        </div>
                        <div class="card-body" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    @can('application-approval')
                                        @include('admin.layouts.partials.form.select', [
                                            'name' => 'status',
                                            'label' => 'Status',
                                            'value' => $row->labour_status,
                                            'id' => 'status',
                                            'required' => true,
                                            'options' => [
                                                ['value' => 'pending', 'text' => 'Pending'],
                                                ['value' => 'in process', 'text' => 'In Process'],
                                                ['value' => 'approved', 'text' => 'Approved'],
                                                ['value' => 'rejected', 'text' => 'Rejected'],
                                            ],
                                        ])
                                    @else
                                        @include('admin.layouts.partials.form.select', [
                                            'name' => 'status',
                                            'label' => 'Status',
                                            'value' => $row->labour_status,
                                            'id' => 'status',
                                            'required' => true,
                                            'options' => [
                                                ['value' => 'pending', 'text' => 'Pending'],
                                                ['value' => 'in process', 'text' => 'In Process'],
                                                ['value' => 'rejected', 'text' => 'Rejected'],
                                            ],
                                        ])
                                    @endcan
                                </div>

                                <div class="col-md-12">
                                    @include('admin.layouts.partials.form.textarea', [
                                        'name' => 'remarks',
                                        'label' => 'Remarks',
                                        'value' => $row->remarks,
                                        'id' => 'remarks',
                                    ])
                                </div>

                                {{-- <div class="col-12">
                                @if ($errors->any())
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{!! $error !!}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div> --}}

                            </div>
                        </div>
                        <div class="card-footer" style="display: none;">
                            <button type="submit" class="btn btn-primary pull-right">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        @endcan
        
        @can('pulmonary-test-report')
        <div class="col-12">
            @include('admin.labours.test_report', ['data' => $row])
        </div>
        @endcan

        <div class="col-12">
            @include('admin.layouts.partials.labour_lease')
        </div>
        @can('labour-card-action')
        <div class="col-12">
            @include('admin.layouts.partials.card',["labour" => $row])
        </div>
        @endcan
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $params['plural_title'] }}</h3>
                    <div class="card-header-actions">
                        @can('update-labours')
                            <a href="{{ route('admin.labours.edit', ['labour' => $row->l_id]) }}" target="_blank"
                                class="card-header-action">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            &nbsp;|&nbsp;
                        @endcan
                        <a href="https://app.cmlw.gkp.pk/labour/print/{{ $row->l_id }}" target="_blank"
                            class="card-header-action"><i class="fa fa-print"></i> Print Form</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Registration Type
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    @if ($row->purpose == 'labour')
                                        Active Mine Labour
                                    @elseif($row->purpose == 'deceased labour')
                                        Labour died in Mine Accident
                                    @elseif($row->purpose == 'permanent disabled')
                                        Permanent Disabled Labour due to Mine Accident
                                    @elseif($row->purpose == 'occupational desease')
                                        Mine Labour with Occupational Pulmonary Disease
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($row->doa)
                            <div class="col-sm-6 id="doa_date">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-6">
                                            <label class="english">
                                                Date of Accident
                                            </label>
                                        </div>
                                    </div>
                                    <p class="form-control">
                                        {{ $row->doa }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if ($row->death_date)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-6">
                                            <label class="english">
                                                Date of Death
                                            </label>
                                        </div>
                                    </div>
                                    <p class="form-control">{{ $row->death_date }}</p>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Personal Information</span>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h2>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Labour Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->name }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Labour CNIC No
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->cnic }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Father Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->father_name }}</p>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Date of Birth
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->dob }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-7 col-7">
                                        <label class="english">
                                            Mobile No (Primary Non-Convertable)
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->cell_no_primary }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Mobile No (Secondary)
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->cell_no_secondary }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Gender
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ ucwords($row->gender) }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Domicile District
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->district->name }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Married
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ ucwords($row->married) }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            EOBI
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ ucwords($row->eobi) }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            EOBI No
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->eobi_no }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Bank Information</span>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h5>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Bank Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->bank ? $row->bank->name : 'Not Provided' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            IBAN Number
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->iban ? $row->iban : 'Not Provided' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Card Information</span>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h5>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Card Issue Date
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->card_issue_date ? \Carbon\Carbon::parse($row->card_issue_date)->format('d M Y') : 'Not Issued Yet' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Card Status
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    @if($row->issued)
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Issued</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-hourglass-half"></i> Not Issued</span>
                                    @endif
                                </p>
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
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h5>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Work Start From
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->work_from }}</p>
                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Work End From
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->work_end_date }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Nature of Work
                                        </label>
                                    </div>
                                    <div class="col-sm-6 col-6 text-right">
                                        <label class="urdu">

                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->work->title }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <h5 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Permanent Address</span>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h5>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Village/Town Address
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->perm_address }}</p>
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
                                </div>
                                <p class="form-control">
                                    {{ $row->perm_district->name }}
                                </p>

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <h5 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Postal Address</span>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>
                            </h5>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Postal Address
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->perm_postal_address ? $row->perm_postal_address : 'None' }}
                                </p>

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
                                </div>
                                <p class="form-control">
                                    {{ $row->postal_district ? $row->postal_district->name : 'None' }}
                                </p>
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
                                            Lease Holder/Company Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->lease_owner_name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Mineral Title
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->mineral_title }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Lease District
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->lease_district->name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Mineral
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ isset($row->mineral)?$row->mineral->name:"None" }}
                                </p>

                                @error('mineral')
                                    <small class="text-danger">
                                        {!! $message !!}
                                    </small>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Lease Address
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->lease_address }}
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            @include('admin.component.children.children', ['children' => $row->children])
        </div>
    </div>
@endsection
