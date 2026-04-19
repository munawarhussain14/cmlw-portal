@extends('admin.layouts.app')

@push('styles')
<style>
  .urdu{
  display:none;
}
</style>
@endpush

@push('scripts')
<script src="{{asset('assets/admin/plugins/moment/moment.min.js')}}"></script>
{{-- <script src="{{asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js')}}"></script> --}}
<script src="{{asset("assets/plugins/jquery-mask/jquery.mask.js")}}"></script>

<script>
  $(function () {
    // $('[data-mask]').inputmask();
    $(".cnic").mask('00000-0000000-0');
  	$(".cell").mask('0000-0000000');
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
					
		@include("admin.scholarships.partials.studentForm",["student"=>$row])
			  		
      </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{!! $error !!}</li>
                  @endforeach
              </ul>
          </div>
      @endif
    </div>
  </form>
</div>
@endsection

@push("scripts")
<script src="{{asset("assets/plugins/jquery-mask/jquery.mask.js")}}"></script>
<script type="text/javascript">

	$(document).ready(function() {

		$("#cnic").mask("00000-0000000-0");
        
	});
</script>
@endpush