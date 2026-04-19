@php
    $colors = [
        '#f56954',
        '#00a65a',
        '#f39c12',
        '#00c0ef',
        '#3c8dbc',
        '#d2d6de',
        '#f56954',
        '#00a65a',
        '#f39c12',
        '#00c0ef',
        '#3c8dbc',
        '#d2d6de',
        '#f56954',
        '#00a65a',
        '#f39c12',
        '#00c0ef',
        '#3c8dbc',
        '#d2d6de',
        '#f56954',
        '#00a65a',
        '#f39c12',
        '#00c0ef',
        '#3c8dbc',
        '#d2d6de',
    ];
@endphp

@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

@section('content')
    <div class="row"></div>
    <div class="row justify-content-center">

        @can('labours-summary')
            <div class="col-md-4">
                @include('admin.summary.labourSummary')
            </div>
        @endcan
        <div class="col-md-3">
            <div class="card card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Area of Jurisdiction</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                    </div>

                </div>

                <div class="card-body" style="display: none;">
                    @foreach (\App\Models\District::all() as $d)
                        <span class="badge badge-success mr-2">{{ $d->name }}</span>
                    @endforeach

                </div>

            </div>
        </div>

        @can('general-scholarship-summary')
            <div class="col-md-3">
                @include('admin.summary.scholarshipSummary')
            </div>
        @endcan

        @can('read-compilations')
            @include("admin.dashboard.tasks",["tasks"=>$tasks])
        @endcan

        @can('read-labours')
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $data['labour']['total'] }}</h3>
                        <p>Labours</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.labours.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
    </div>

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grants</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="row justify-content-center">
        @can('read-disabled-mine-labour')
            <div class="col-md-4">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $data['disabled']['total'] }}</h3>
                        <p>Permanent Disabled</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wheelchair"></i>
                    </div>
                    <a href="{{ route('admin.grants.disabled-mine-labour.index') }}" class="small-box-footer">More info
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
        @can('read-pulmonary-mine-labour')
            <div class="col-md-4">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $data['pulmonary']['total'] }}</h3>
                        <p>Pulmonary</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-lungs"></i>
                    </div>
                    <a href="{{ route('admin.grants.pulmonary-mine-labour.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
        @can('read-deceased-mine-labour')
            <div class="col-md-4">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $data['deceased']['total'] }}</h3>
                        <p>Death Grant</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <a href="{{ route('admin.grants.deceased-mine-labour.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
        @can('read-marriage-mine-labour')
            <div class="col-md-4">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $data['marriage']['total'] }}</h3>
                        <p>Marriage Grant</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-venus-mars "></i>
                    </div>
                    <a href="{{ route('admin.grants.marriage-mine-labour.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
    </div>


    {{-- @can('read-skill-development')
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Skill Development</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $data['diploma']['gems']['total'] }}</h3>
                        <p>Diploma in Gems & Gemology</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <a href="{{ route('admin.skill-development.index', ['type' => 'gems-and-gemology']) }}"
                        class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <!-- small box -->
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $data['diploma']['lapidary']['total'] }}</h3>
                        <p>Diploma in Lapidary</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <a href="{{ route('admin.skill-development.index', ['type' => 'lapidary']) }}"
                        class="small-box-footer">More
                        info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    @endcan --}}

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Scholarship Schemes</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="row justify-content-center">
        @can('read-scholarships')
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $data['scholarship']['total'] }}</h3>
                        <p>Scholarship</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <a href="{{ route('admin.scholarships.general.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan

        @can('read-quality-education')
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $data['prof']['total'] }}</h3>
                        <p>Quality Education</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('admin.scholarships.quality-education.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
        @can('read-special-education')
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $data['special']['total'] }}</h3>
                        <p>Special Education</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <a href="{{ route('admin.scholarships.special-education.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
        @can('read-top-position')
            <div class="col-md-3">
                <!-- small box -->
                <div class="small-box bg-dark">
                    <div class="inner">
                        <h3>{{ $data['top']['total'] }}</h3>
                        <p>Top 50</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <a href="{{ route('admin.scholarships.top-position.index') }}" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endcan
    </div>

   
        <!--@include("admin.summary")-->
    

@endsection
