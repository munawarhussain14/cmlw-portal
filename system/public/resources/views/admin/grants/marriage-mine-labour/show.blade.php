@extends('admin.layouts.app')


@section('content')
    <div class="row">
        @can('marriage-grant-status')
            <div class="col-12">
                @include('admin.grants.partials.actionMarriageTab', ['data' => $row])
            </div>
        @endcan
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $params['plural_title'] }}</h3>
                    <div class="card-header-actions">
                        @can('update-marriage-mine-labour')
                            <a href="{{ route('admin.grants.marriage-mine-labour.edit', ['marriage_mine_labour' => $row->id]) }}"
                                target="_blank" class="card-header-action">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            &nbsp;|&nbsp;
                        @endcan
                        <!--<a href="https://app.cmlw.gkp.pk/labour/print/{{ $labour->l_id }}" target="_blank"-->
                        <!--    class="card-header-action"><i class="fa fa-print"></i> Print Form</a>-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Previous Granted Scheme
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    @php
                                    $message = [];
                                    if($labour->grantedPulmonary){
                                        array_push($message, "Pulmonary Scheme Granted");
                                    }
                                    if($labour->grantedDisabledLabour){
                                        array_push($message, "Disabled Scheme Granted");
                                    }
                                    if($labour->grantedDeceaseLabour){
                                        array_push($message, "Deceased Scheme Granted");
                                    }
                                    
                                    if(empty($message)) {
                                        $message[] = "No Scheme Granted";
                                    }
                                    
                                    echo implode(', ', $message);
                                    @endphp
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Labour Status
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    @if ($labour->purpose == 'labour')
                                        Active Mine Labour
                                    @elseif($labour->purpose == 'deceased labour')
                                        Labour died in Mine Accident
                                    @elseif($labour->purpose == 'permanent disabled')
                                        Permanent Disabled Labour due to Mine Accident
                                    @elseif($labour->purpose == 'occupational desease')
                                        Mine Labour with Occupational Pulmonary Disease
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        

                        <div class="col-sm-6" id="doa_date">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Father Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    <a href="{{ route('admin.labours.show', ['labour' => $labour->l_id]) }}" target="_blank">{{ $labour->name }}</a>
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6" id="doa_date">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Father CNIC
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $labour->cnic }}
                                </p>
                            </div>
                        </div>
                            
                        @if ($labour->doa)
                            <div class="col-sm-6" id="doa_date">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-6">
                                            <label class="english">
                                                Date of Accident
                                            </label>
                                        </div>
                                    </div>
                                    <p class="form-control">
                                        {{ $labour->doa }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-12">
                            <h2 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Husband Detail</span>
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
                                            Husband Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->husband_name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Husband CNIC
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $row->husband_cnic }}
                                </p>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            Marriage Date
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->marriage_held_on }}</p>
                            </div>
                        </div>

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
                                            Daughter Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->daughter->name }}</p>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            CNIC No
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">{{ $row->daughter->reg_no }}</p>
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
                                <p class="form-control">{{ $row->daughter->dob }}</p>
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
                                <p class="form-control">{{ ucwords($row->daughter->gender) }}</p>
                            </div>
                        </div>

                    </div>

                    @if($child->mother)
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="section-title">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <span class="english">Mother Detail</span>
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
                                            Mother Name
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $child->mother->name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-6">
                                        <label class="english">
                                            CNIC
                                        </label>
                                    </div>
                                </div>
                                <p class="form-control">
                                    {{ $child->mother->cnic }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
