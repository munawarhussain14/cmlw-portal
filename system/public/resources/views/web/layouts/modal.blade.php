<!-- Modal -->
@if($announcement)
<div class="modal fade" id="announcement" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body" style="padding:0; position: relative;">
        <button style="position: absolute; right:0; z-index:20; background:white; width:25px; height:25px;" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div style="position: relative; min-height:100px;">
            <div style="z-index:2; position:absolute; margin-top:30px; background:white;">
                @if($announcement->title!="")
                <h1 class="text-center" style="">
                    {{$announcement->title}}
                </h1>
                @endif
                @if($announcement->content!="")
                <p class="text-center">
                    {!!$announcement->content!!}
                </p>
                @endif
            </div>
            @if($announcement->featured!="")
              <img src="{{asset($announcement->featured)}}" width="100%"/>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endif