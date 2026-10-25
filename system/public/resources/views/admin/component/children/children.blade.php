<div class="card">
    <h5 class="card-header">
      Children
    </h5>
    <div class="card-body">
      <div class="row">
        @if($children->count()>0)
        @foreach($children as $child)
        <div class="col-12">
          <div class="row">
            <div class="col-md-4">
              <label>Name</label>
              <p>{{$child->name}}</p>
            </div>
            <div class="col-md-4">
              <label>Reg No</label>
              <p>{{$child->reg_no}}</p>
            </div>
            <div class="col-md-4">
              <label>Date of Birth</label>
              <p>{{$child->dob}}</p>
            </div>
          </div>
        </div>
        <div class="col-12"><hr></div>
        @endforeach
        @else
        <div class="col-12">
            <h5 class="text-center">No Children</h5>
        </div>
        @endif
      </div>
    </div>
  </div>