<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Previous History</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($history as $item)
                <div class="col-12">
                    <div class="row">
                        <div class="col-6 col-md-4">
                            <label>Year</label>
                            <p>{{ $item->fy_year }}</p>
                        </div>
                        <div class="col-6 col-md-4">
                            <label>Class</label>
                            <p>{{ $item->class }}</p>
                        </div>
                        <div class="col-md-4">
                            <label>Scheme</label>
                            <p>
                                @if ($item->category == 'General')
                                    General
                                @elseif($item->category == 'Engineering')
                                    Professional Education (Engineering)
                                @elseif($item->category == 'Special')
                                    Special Education
                                @elseif($item->category == 'Top')
                                    Quality Education
                                @elseif($item->category == 'Medical')
                                    Professional Education (Medical)
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
