<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Labour Detail</h3>
        <div class="card-header-actions">
          <a href="{{route("admin.labours.edit",["labour"=>isset($row->labour_id)?$row->labour_id:$row->l_id])}}" target="_blank" class="card-header-action"><i class="fa fa-edit"></i> Edit</a>
      </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                @include("admin.layouts.partials.form.select",
                            [
                            "name"=>"purpose",
                            "label"=>"Registration Type",
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

            <div class="col-lg-12">
              <h2 class="section-title">
                <div class="row">
                  <div class="col-sm-6 col-6">
                    <span class="english">Personal Information</span>			
                  </div>
                  <div class="col-sm-6 col-6 text-right">
                    <span class="urdu">ذاتی معلومات</span>
                  </div>
                  <div class="col-12">
                    <hr>
                  </div>						
                </div>
              </h2>
            </div>
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.input",
                    [
                      "name"=>"name",
                      "label"=>"Name",
                      "id"=>"name",
                      "required"=>true,
                      "value"=>$row->labour->name,
                      "disabled"=>false
                      ])
            </div>
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.input",
                    [
                      "name"=>"cnic",
                      "label"=>"Labour CNIC No",
                      "id"=>"cnic",
                      "required"=>true,
                      "value"=>$row->labour->cnic,
                      "disabled"=>false
                      ])
            </div>
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.input",
                    [
                      "name"=>"father_name",
                      "label"=>"Father Name",
                      "id"=>"father_name",
                      "required"=>true,
                      "value"=>$row->labour->father_name,
                      "disabled"=>false
                      ])
            </div>
        
            <div class="col-sm-6">
              @include("admin.layouts.partials.date",["name"=>"dob","disabled"=>true,"title"=>"Date of Birth","date"=>$row->labour->dob])
            </div>
        
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.input",
                    [
                      "name"=>"cell_no_primary",
                      "label"=>"Mobile No (Primary Non-Convertable)",
                      "id"=>"cell_no_primary",
                      "required"=>true,
                      "value"=>$row->labour->cell_no_primary,
                      "disabled"=>false
                      ])
            </div>
        
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.input",
                    [
                      "name"=>"cell_no_secondary",
                      "label"=>"Mobile No (Secondary)",
                      "id"=>"cell_no_secondary",
                      "required"=>true,
                      "value"=>$row->labour->cell_no_secondary,
                      "disabled"=>false
                      ])
            </div>
        
            <div class="col-sm-6">
              @include("admin.layouts.partials.form.select",
                          [
                          "name"=>"gender",
                          "label"=>"Gender",
                          "value"=>$row->labour->gender,
                          "id"=>"gender",
                          "required"=>false,
              "disabled"=>true,
                          "options"=>[
                              ["value"=>"male","text"=>"Male"],
                              ["value"=>"female","text"=>"Female"]
                              ]
                          ])
            </div>
        
            <div class="col-sm-6">
              <div class="form-group">
                 <div class="row">
                  <div class="col-sm-6 col-6">
                    <label class="english">
                      Domicile District<span class="required">*</span>
                    </label>			
                  </div>
                  <div class="col-sm-6 col-6 text-right">
                    <label class="urdu">
                      <span class="required">*</span> ڈ ڈومیسائل ضلع</label>
                  </div>
                </div>
                  <select disabled
                  name="domicile_district" 
                  class="form-control">
                    <option disabled="" selected="">Select District</option>
                    @foreach($districts as $district)
                  <option {{(old('domicile_district',(isset($row->labour))?$row->labour->domicile_district:"")==$district->d_id)?"selected":""}} value="{{$district->d_id}}">{{$district->name}}</option>
                  @endforeach
                  </select>
        
                  @error('domicile_district')
                  <small class="text-danger">
                    {!! $message !!}
                  </small>
                 @enderror
              </div>
            </div>
          </div>
    </div>
  </div>
