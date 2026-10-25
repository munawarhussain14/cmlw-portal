@extends('admin.layouts.app')

@section("content")
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            @include("admin.layouts.partials.datatable")
          </div>
        </div>
      </div>
</section>
@endsection