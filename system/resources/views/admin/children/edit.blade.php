@extends('admin.layouts.app')

@push('styles')

@endpush

@push('scripts')
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js')}}"></script>

<script>
  $(function () {

    $('[data-mask]').inputmask();
    $('.image-file').change(function() {
        var fileName = this.files[0].name;
        $(this).parent().find(".custom-file-label").text(fileName);
        
        let reader = new FileReader();

        reader.onload = function(e){
          $(".logo-image img").attr("src",e.target.result);
        }

        reader.readAsDataURL(this.files[0]);
    });

    $('input[type="file"]').change(function() {
        var fileName = this.files[0].name;
        $(this).parent().find(".custom-file-label").text(fileName);
    });

    $("#primary_color").on("change paste keyup",function(){
      $(".primary_color_block").css("background",this.value);
    });
    $(".primary_color_block").css("background",$("#primary_color").val());
    
    $("#secondary_color").on("change paste keyup",function(){
      $(".secondary_color_block").css("background",this.value);
    });
    $(".secondary_color_block").css("background",$("#secondary_color").val());

  });

</script>
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
              <label for="name">Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" 
              id="name" 
              required
              name="name"
              placeholder="Enter Name"
              value="{{ old('name',(isset($row))?$row->name:"") }}"
              />
              @error('name')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label>Phone No</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                </div>
                <input 
                type="text" 
                class="form-control" 
                required
                name="phone"
                value="{{ old('phone',(isset($row))?$row->phone:"") }}"
                data-inputmask='"mask": "(+999) 999999999"' data-mask/>
              </div>
              @error('phone')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
              <!-- /.input group -->
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="address">Address</label>
              <textarea class="form-control @error('address') is-invalid @enderror" 
              id="address" 
              name="address"
              required
              placeholder="Enter Address">{{ old('address',(isset($row))?$row->address:"") }}</textarea>
              @error('address')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="name">Primary Color</label>
              <div class="input-group mb-3">
                <input 
                  type="color" class="form-control @error('primary_color') is-invalid @enderror" 
                  id="primary_color" 
                  name="primary_color"
                  placeholder="Enter Primary Color"
                  value="{{ old('primary_color',(isset($row))?$row->primary_color:"") }}"
                  />
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <div class='color-box primary_color_block' style='background:white'></div>
                    </span>
                  </div>
              </div>
              @error('primary_color')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="name">Secondary Color</label>
              <div class="input-group mb-3">
                  <input 
                  type="color" class="form-control @error('secondary_color') is-invalid @enderror" 
                  id="secondary_color" 
                  name="secondary_color"
                  placeholder="Enter Secondary Color"
                  value="{{ old('secondary_color',(isset($row))?$row->secondary_color:"") }}"
                  />
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <div class='color-box secondary_color_block' style='background:white'></div>
                    </span>
                  </div>
              </div>
              @error('secondary_color')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="logo">Logo</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input 
                    type="file" 
                    name="logo"
                    accept="application/jpg,jpeg,png,gif" 
                    value="{{ old('logo') }}" 
                    class="image-file" 
                    id="logo">
                    <label class="custom-file-label" for="logo">{{ old('logo',"Choose file") }}</label>
                  </div>
                </div>
                @error('logo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="logo-image">
                <img width="150" src="{{asset(isset($row->logo)?Storage::url($row->logo):'assets/images/no-image.jpg')}}"/>
              </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="pdf_menu">PDF Menu</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input 
                    type="file" 
                    name="pdf_menu" 
                    accept="application/pdf" 
                    value="{{ old('pdf_menu') }}" 
                    class="custom-file-input" 
                    id="pdf_menu">
                    <label class="custom-file-label" for="pdf_menu">{{ old('pdf_menu',"Choose file") }}</label>
                  </div>
                </div>
                @error('pdf_menu')
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