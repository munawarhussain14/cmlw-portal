@extends('admin.layouts.app')

@section("content")
<section class="content">
      <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row mb-3">
          <div class="col-lg-2 col-md-4 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $summary['total'] ?? 0 }}</h3>
                <p>Total Complaints</p>
              </div>
              <div class="icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $summary['pending'] ?? 0 }}</h3>
                <p>Pending</p>
              </div>
              <div class="icon">
                <i class="fas fa-pause-circle"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $summary['in_progress'] ?? 0 }}</h3>
                <p>In Progress</p>
              </div>
              <div class="icon">
                <i class="fas fa-clock"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $summary['resolved'] ?? 0 }}</h3>
                <p>Resolved</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $summary['rejected'] ?? 0 }}</h3>
                <p>Rejected</p>
              </div>
              <div class="icon">
                <i class="fas fa-times-circle"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- DataTable -->
        <div class="row">
          <div class="col-12">
            @include("admin.layouts.partials.datatable")
          </div>
        </div>
      </div>
</section>
@endsection
