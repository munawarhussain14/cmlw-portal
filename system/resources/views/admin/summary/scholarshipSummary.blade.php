@php
    $offices = \App\Models\Office::all();
    $i = 0;

@endphp
<div class="card collapsed-card">
    <div class="card-header">
        <h3 class="card-title">Scholarship</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: none;">
        @php
            $approved = $offices[1]->scholarship()->where('status', 'approved')->count();
            $total = $offices[1]->scholarship()->count();
        @endphp
        @foreach ($offices as $office)
            @php
                $approved = $office->scholarship()->where('status', 'approved')->count();
                $total = $office->scholarship()->count();
            @endphp
            <div class="progress-group">
                {{ $office->officeDistrict->name }}
                <span class="float-right"><b>{{ $approved }}</b>/{{ $total }}</span>
                <div class="progress progress-sm">
                    <div class="progress-bar"
                        style="width: {!! ($approved * 100) / ($total > 0 ? $total : 1) !!}%; background-color:{!! $colors[$i++] !!}"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>
