<div class="card">
    <h5 class="card-header">
        Wife
    </h5>
    <div class="card-body">
        <div class="row">
            @if ($wife->count() > 0)
                @foreach ($wife as $item)
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Name</label>
                                <p>{{ $item->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <label>CNIC</label>
                                <p>{{ $item->cnic }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <h5 class="text-center">No Wife</h5>
                </div>
            @endif
        </div>
    </div>
</div>
