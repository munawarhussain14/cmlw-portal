@extends('admin.layouts.app')

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/qrcode/qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.10.377/build/pdf.min.js"></script>
    {{-- https://www.cssscript.com/qr-code-generator-logo-title/ --}}
    <script type="text/javascript">
        $(document).ready(function() {
            @if ($row->labour->pdf_menu)
                var qrcode = new QRCode(document.getElementById("qrcode"),
                    "{{ route('download.pdf', ['restaurant_id' => $row->labour->id]) }}");
                @if (!file_exists($row->labour->qrcode))
                    setTimeout(function() {
                        let image = $("#qrcode img").attr("src");
                        $.post("{{ route('admin.restaurants.qrcode', ['restaurant_id' => $row->labour->id]) }}", {
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
                    $("#qrcode-container img").attr("src", "{{ url($row->labour->qrcode) }}");
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
        @can('disabled-grant-status')
            <div class="col-12">
                @include('admin.grants.partials.actionTab', ['data' => $row])
            </div>
        @endcan
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $params['plural_title'] }}</h3>
                    <div class="card-header-actions">
                        @can('update-disabled-mine-labour')
                            <a href="{{ route('admin.grants.disabled-mine-labour.edit', ['disabled_mine_labour' => $row->id]) }}"
                                target="_blank" class="card-header-action">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            &nbsp;|&nbsp;
                        @endcan
                        <!--<a href="https://app.cmlw.gkp.pk/grants/disabled-mine-labour/print/{{ $row->l_id }}/5"-->
                        <!--    target="_blank" class="card-header-action"><i class="fa fa-print"></i> Print Form</a>-->
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
                                    @if ($row->labour->purpose == 'labour')
                                        Active Mine Labour
                                    @elseif($row->labour->purpose == 'deceased labour')
                                        Labour died in Mine Accident
                                    @elseif($row->labour->purpose == 'permanent disabled')
                                        Permanent Disabled Labour due to Mine Accident
                                    @elseif($row->labour->purpose == 'occupational desease')
                                        Mine Labour with Occupational Pulmonary Disease
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($row->labour->doa)
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
                                        {{ $row->labour->doa }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Disability
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->disability }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Disability Type
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ ucwords($row->disability_type) }}
                                </p>
                            </div>
                        </div>

                        @if ($row->labour->death_date)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-6">
                                            <label class="english">
                                                Date of Death
                                            </label>
                                        </div>
                                    </div>
                                    <p class="form-control">{{ $row->labour->death_date }}</p>
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
                                <p class="form-control">{{ $row->labour->name }}</p>
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
                                <p class="form-control">{{ $row->labour->cnic }}</p>
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
                                <p class="form-control">{{ $row->labour->father_name }}</p>

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
                                <p class="form-control">{{ $row->labour->dob }}</p>
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
                                <p class="form-control">{{ $row->labour->cell_no_primary }}</p>
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
                                <p class="form-control">{{ $row->labour->cell_no_secondary }}</p>
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
                                <p class="form-control">{{ ucwords($row->labour->gender) }}</p>
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
                                    {{ $row->labour->district->name }}
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
                                <p class="form-control">{{ ucwords($row->labour->married) }}</p>
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
                                <p class="form-control">{{ ucwords($row->labour->eobi) }}</p>
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
                                <p class="form-control">{{ $row->labour->eobi_no }}</p>
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
                                <p class="form-control">{{ $row->labour->work_from }}</p>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Work End Date
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->labour->work_end_date }}</p>
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
                                    {{ $row->labour->work->title }}
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
                                <p class="form-control">{{ $row->labour->perm_address }}</p>
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
                                    {{ $row->labour->perm_district->name }}
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
                                    {{ $row->labour->perm_postal_address ? $row->labour->perm_postal_address : 'None' }}
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
                                    {{ $row->labour->postal_district ? $row->labour->postal_district->name : 'None' }}
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
                                    {{ $row->labour->lease_owner_name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Lease No
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->labour->lease_no }}
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
                                    {{ $row->labour->lease_district->name }}
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
                                    {{ $row->labour->mineral->name }}
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
                                    {{ $row->labour->lease_address }}
                                </p>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            @include('admin.component.children.children', ['children' => $row->labour->children])
        </div>
    </div>
@endsection
