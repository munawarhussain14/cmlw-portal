@push('styles')
    @once
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    @endonce
@endpush
@push('scripts')
    @once
        <script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

        <script type="text/javascript">
            /*$('#data-table tfoot th').each(function() {
                                                                                                                                                                                                                                                                                                                var title = $(this).text();
                                                                                                                                                                                                                                                                                                                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                                                                                                                                                                                                                                                                                                            });*/

            const urlWithParams = new URL(
                "{{ isset($params['custom_url']) ? $params['custom_url'] : route($params['route'] . '.index') }}");

            // myUrlWithParams.searchParams.append("city", "Rome");
            // myUrlWithParams.searchParams.append("price", "200");

            // console.log(myUrlWithParams.href);
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // initComplete: function() {
                //     // Apply the search
                //     this.api()
                //         .columns()
                //         .every(function() {
                //             var that = this;

                //             $('input', this.footer()).on('keyup change clear', function() {
                //                 if (that.search() !== this.value) {
                //                     that.search(this.value).draw();
                //                 }
                //             });
                //         });
                // },
                ajax: urlWithParams.href,
                columns: [
                    @foreach ($params['columns'] as $values)
                        {{ '{' }}
                        @foreach ($values as $key => $value)
                            {{ "$key:`$values[$key]`," }}
                        @endforeach
                        {{ '},' }}
                    @endforeach
                ]
            });

            const onFilter = (params = {}) => {

                let status_filter = $("#status-filter").val();
                if (status_filter != "all") {
                    urlWithParams.searchParams.append("status", status_filter);
                } else {
                    urlWithParams.searchParams.delete("status");
                }

                let district_filter = $("#district-filter").val();
                if (district_filter.length > 0) {
                    urlWithParams.searchParams.append("districts", district_filter);
                } else {
                    urlWithParams.searchParams.delete("districts");
                }

                table.ajax.url(urlWithParams.href).load();
            }

            const onRemove = (id) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        jQuery.ajax({
                            url: "{{ url($params['basic']) }}/" + id,
                            method: 'delete',
                            success: function(result) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your entery has been deleted.',
                                    'success'
                                );
                                table.ajax.reload();
                            }
                        });
                    }
                });
            }
        </script>
    @endonce
@endpush

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $params['plural_title'] }}</h3>
        @if (isset($params['module_name']))
            @can('create-' . $params['module_name'])
                <div class="card-header-actions">
                    <a href="{{ isset($params['custom_create_url']) ? $params['custom_create_url'] : route($params['route'] . '.create') }}"
                        class="card-header-action"><i class="fa fa-plus"></i> Create {{ $params['singular_title'] }}</a>
                </div>
            @endcan
        @endif
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="data-table" class="table table-bordered table-hover">
            <thead>
                <tr>
                    @foreach ($params['columns'] as $values)
                        <th
                            {{ $values['name'] == 'ID' ? 'width=auto' : ($values['name'] == 'Action' || $values['name'] == 'Status' ? 'width=18%' : '') }}>
                            {{ $values['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody></tbody>
            {{-- <tfoot>
                <tr>
                    @foreach ($params['columns'] as $values)
                        <th
                            {{ $values['name'] == 'ID' ? 'width=auto' : ($values['name'] == 'Action' || $values['name'] == 'Status' ? 'width=18%' : '') }}>
                            {{ $values['name'] }}</th>
                    @endforeach
                </tr>
            </tfoot> --}}
        </table>
    </div>
    <!-- /.card-body -->
</div>
