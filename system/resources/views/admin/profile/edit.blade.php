@extends('admin.layouts.app')

@push('scripts')
<script src="{{asset('assets/admin/plugins/select2/js/select2.full.min.js')}}"></script>
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<script>
$(function () {
  $("#change_password").on("change",function(){
    // alert("Test");
    if($(this).is(":checked")){
      $("#password,#password_confirmation").attr("disabled",false);
    }else{
      $("#password,#password_confirmation").attr("disabled",true);
    }
  });
  })
</script>
@endpush

@section("content")
<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">{{$params['singular_title']}}</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{route($params['route'].".update",$parm)}}" 
                method="post"
                enctype="multipart/form-data">
                @csrf
                @if(isset($row))
                  @method('put')
                @endif
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name"
                    required
                    placeholder="Enter Name"
                    value="{{ old('name',(isset($row))?$row->name:"") }}"
                    />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email"
                    type="email"
                    readonly
                    placeholder="Enter Email"
                    value="{{ old('email',(isset($row))?$row->email:"") }}"
                    />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-check">
                      <input type="checkbox"  
                      {{ old('change_password')?"checked":"" }}
                      name="change_password" class="form-check-input" id="change_password">
                      <label class="form-check-label" for="change_password">Change Password</label>
                  </div>
                  <div class="form-group">
                    <label for="title">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password"
                    required
                    {{ old('change_password')?"":"disabled" }}
                    placeholder="Enter Password"
                    value="{{ old('password') }}"
                    />
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="title">Confirm Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password_confirmation"
                    required
                    {{ old('change_password')?"":"disabled" }}
                    placeholder="Confirm Password"
                    value="{{ old('password') }}"
                    />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                    {{-- <div class="form-group">
                      <label for="avatar">Profile Image</label>
                      <div class="input-group">
                        <div class="custom-file">
                          <input 
                          type="file" 
                          name="attachment" 
                          accept="application/pdf" 
                          value="{{ old('avatar') }}" 
                          class="custom-file-input" 
                          id="avatar">
                          <label class="custom-file-label" for="avatar">{{ old('attachment',"Choose file") }}</label>
                        </div>
                      </div>
                      @error('avatar')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div> --}}
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
@endsection