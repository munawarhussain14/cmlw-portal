<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="export-form" action="{{ $url }}" method="post" target="_blank">
            @csrf
            <input type="hidden" id="export-status" name="status" value="all" />
            <input type="hidden" id="export-districts" name="districts" />
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Export</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @foreach ($columns as $column)
                            <div class="form-check">
                                <input class="form-check-input" @if (isset($column['checked']) && $column['checked']) checked @endif
                                    name="columns[]" value="{{ $column['value'] }}" type="checkbox">
                                <label class="form-check-label">{{ $column['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Export in Excel</button>
                </div>
            </div>
        </form>
    </div>
</div>
