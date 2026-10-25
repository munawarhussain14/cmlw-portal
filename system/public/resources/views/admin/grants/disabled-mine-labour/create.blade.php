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
@include("admin.grants.partials.labourInfo")
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
    @method('put')
      <div class="card-body">
        <div class="row">
          <div class="col-sm-12">
            @include("admin.layouts.partials.form.select",
						[
						"name"=>"purpose",
						"label"=>"Select the Registration Type",
						"value"=>$row->labour->purpose,
						"id"=>"purpose",
						"required"=>false,
            "disabled"=>true,
						"options"=>[
							["value"=>"labour","text"=>"Active Mine Labour"],
							["value"=>"deceased labour","text"=>"Labour Died in Mine Accident"],
							["value"=>"permanent disabled","text"=>"Permanent Disabled Labour due to Mine Accident"],
							["value"=>"occupational desease","text"=>"Mine Labour with Occupational Pulmonary Disease"]
							]
						])
          </div>
    
          <div class="col-sm-6 {{(!old('purpose',(isset($row->labour))?$row->labour->purpose:"")||old('purpose',(isset($row->labour))?$row->labour->purpose:"")=='labour')?"hide":""}}" id="doa_date">
            @include("admin.layouts.partials.date",["name"=>"doa","disabled"=>false,"title"=>"Date of Accident","date"=>$row->labour->doa])
          </div>
    
          <div class="col-12">
            <p id="labour_msg" class="{{(old('purpose')!="labour")?"hide":"" }}">
              <!-- For Labour Message -->
            </p>
            <p id="deceased_msg" class="{{(old('purpose')!="deceased labour")?"hide":"" }}">
              <!-- For Deceased Labour Message -->
            </p>
            <p id="disabled_msg" class="{{(old('purpose')!="permanent disabled")?"hide":"" }}">
              <!-- For Permanent Disabled -->
            </p>
            <p id="disease_msg" class="{{(old('purpose')!="occupational desease")?"hide":"" }}">
              <!-- For Disease Message -->
            </p>
          </div>

          
          <div class="col-12">
            @include("admin.layouts.partials.form.input",
                  [
                    "name"=>"disability_factor",
                    "label"=>"Disability",
                    "id"=>"disability",
                    "required"=>true,
                    "value"=>$row->disability
                    ])
          </div>
          <div class="col-sm-6">
                  @include('admin.layouts.partials.form.select', [
                            'name' => 'disability_type',
                            'label' => 'Disability Type',
                            'id' => 'disability_type',
                            'required' => true,
                            'options' => [
                                ['value' => 'permanent', 'text' => 'Permanent'],
                                ['value' => 'temporary', 'text' => 'Temporary'],
                            ],
                            'value' => $row->disability_type,
                        ])
          </div>
    
        </div>
    
        <div class="row">
    
          <div class="col-sm-12">
            <h5 class="section-title">
              <div class="row">
                <div class="col-sm-6 col-6">
                  <span class="english">Work Information</span>
                </div>
                <div class="col-sm-6 col-6 text-right">
                  <span class="urdu"> کام کے بارے میں معلومات  </span>	
                </div>
                <div class="col-12">
                  <hr>
                </div>
              </div>
            </h5>
          </div>
    
    
          <div class="col-sm-6">
            @include("admin.layouts.partials.date",["name"=>"work_from","title"=>"Work From","date"=>$row->labour->work_from])
          </div>

          <div class="col-sm-6">
            @include("admin.layouts.partials.date",["name"=>"work_end","title"=>"Work End","date"=>$row->labour->work_end_date])
          </div>
    
        </div>
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