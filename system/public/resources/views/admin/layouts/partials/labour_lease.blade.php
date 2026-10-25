@php
    $districts = \App\Models\MineralTitle::selectRaw('district as value, district as text')
        ->distinct('district')
        ->orderBy('district')
        ->get()
        ->toArray();
@endphp
<form id="data-form" action="{{ route('admin.updateLabourMineralTitle', ['labour' => $row->l_id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="card card-info collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Lease Detail</h3>

            <div class="card-tools">
                <a target="_blank" href="{{ route('admin.view_labour_history', ['labour' => $row->l_id]) }}"
                    class="btn btn-tool">
                    <i class="fas fa-history"></i>
                </a>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.layouts.partials.form.select', [
                        'name' => 'district',
                        'label' => 'District',
                        'id' => 'district',
                        'required' => true,
                        'options' => $districts,
                    ])
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mineral Title</label>
                        <select class="select2" required name="mineral_title" id="mineral-title-filter"
                            data-placeholder="Select a Mineral Title" style="width: 100%;">
                        </select>
                        @error('mineral_title')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mineral Title</label>
                        <select name="mining_area" id="mining-area-filter" data-placeholder="Select a Mining Area"
                            style="width: 100%;">
                        </select>
                        @error('mining_area')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Work Start Date</label>
                        <input type="date" required value="{{ old('start') }}" class="form-control"
                            name="start" />
                        @error('start')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Work End Date</label>
                        <input type="date" value="{{ old('end') }}" class="form-control" name="end" />
                        @error('end')
                            <label class="error">{!! $message !!}</label>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer" style="display: none;">
            <button type="submit" class="btn btn-primary pull-right">Update</button>
        </div>
    </div>
</form>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        let district = "";
        $('.select2').select2({
            ajax: {
                method: "post",
                url: '{{ route('admin.getMineralTitle') }}',
                data: function(params) {
                    var query = {
                        district: district,
                        keywords: params.term
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.data
                    };
                }
            }
        });

        $('#mining-area-filter').select2({
            ajax: {
                method: "post",
                url: '{{ route('admin.getMiningArea') }}',
                data: function(params) {
                    var query = {
                        district: district,
                        keywords: params.term
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.data
                    };
                }
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /*$("#statusForm").submit(function(event) {
            var area = $("#mining_area").val();
            if (area == null) {
                alert("Please select Area");
            } else {
                var data = $(this).serialize();
                $.post("", data).then(function(data) {
                    //location.reload();
                });
            }

            event.preventDefault();
        });*/

        $("#district").on("change", function() {
            district = this.value;
            $("#mineral-title-filter").val("");
            $('.select2,#mining-area-filter').val(null).trigger('change');
            // $.post(`{{ route('admin.getMineralTitle') }}`, {
            //     "district": this.value
            // }).then(function(data) {});
        });

        function onChangeDistrict(e) {
            $("#mining_area").attr("disabled", true);
            $.get(`/portal/api/miningArea/all/${e.value}`).then(function(data) {
                var areas = data.areas;
                /*$("#mining_area").empty();
                $("#mining_area").append(
                    $("<option></option>")
                    .text("Select Mining Area")
                    .attr("disabled", true)
                    .attr("selected", true)
                );


                $.each(areas, function(index, area) {
                    $("#mining_area").append(
                        $("<option></option>")
                        .text(area.code + " - " + area.parties)
                        .val(area.code)
                    );
                });*/
                $("#mining_area").attr("disabled", false);

                var titles = data.mineral_title;
                $("#mineral_title").empty();
                $("#mineral_title").append(
                    $("<option></option>")
                    .text("Select Mineral Title")
                    .attr("disabled", true)
                    .attr("selected", true)
                );

                $.each(titles, function(index, title) {
                    $("#mineral_title").append(
                        $("<option></option>")
                        .text(title.code + " - " + title.parties)
                        .val(title.code)
                    );
                });
                $("#mining_area").attr("disabled", false);

                //mineral_title

                //$('#mining_area').selectpicker();

            });
        }
    </script>
@endpush
