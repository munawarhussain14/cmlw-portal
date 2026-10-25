@extends('admin.layouts.app')

@push('styles')

@endpush


@section("content")
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">{{$params['singular_title']}}</h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form id="data-form" action="{{(isset($row))?route($params['route'].".update",$parm):route($params['route'].'.store')}}" 
    method="post" 
    enctype="multipart/form-data">
    @csrf
    @if(isset($row))
      @method('put')
    @endif
      <div class="card-body">
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <label for="name">Husband Name</label>
              <input type="text" class="form-control @error('husband_name') is-invalid @enderror" 
              id="name" 
              required
              name="husband_name"
              placeholder="Enter Husband Name"
              value="{{ old('name',(isset($row))?$row->husband_name:"") }}"
              />
              @error('husband_name')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="name">Husband CNIC</label>
              <input type="text" class="form-control @error('husband_cnic') is-invalid @enderror" 
              id="name" 
              required
              name="husband_cnic"
              placeholder="Enter Husband CNIC"
              value="{{ old('name',(isset($row))?$row->husband_cnic:"") }}"
              />
              @error('husband_cnic')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
        </div>
      </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>
@endsection