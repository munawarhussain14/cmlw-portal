<div class="col-md-3">
    <div class="card card-danger @if(count($tasks)===0) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title">Tasks</h3>
            <div class="card-tools">
                @if(count($tasks)===0)
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                @else
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                @endif
            </div>
        </div>

        <div class="card-body" style="@if(count($tasks)===0) display: none; @endif max-height:200px; overflow-y:scroll; overflow-x:hide">
            @foreach ($tasks as $task)
                <div>
                    <h6>
                        <b>{{$task["title"]}}</b>
                    </h6>
                    <p>{!!$task["message"]!!}</p>
                    <a href="{{$task['link']}}">See Detail</a>
                    <hr>
                </div>
                <!-- <span class="badge badge-success mr-2"></span> -->
            @endforeach

        </div>

    </div>
</div>