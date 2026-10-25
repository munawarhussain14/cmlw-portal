@extends('admin.layouts.app')

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/qrcode/qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.10.377/build/pdf.min.js"></script>
    {{-- https://www.cssscript.com/qr-code-generator-logo-title/ --}}
    <script type="text/javascript"></script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            @include('admin.layouts.partials.showLabour', [
                'title' => 'Labour Detail',
                'labour' => $row,
            ])
        </div>
        <div class="col-12">
            <section class="content">
                <div class="container-fluid">

                    <!-- Timelime example  -->
                    <div class="row">
                        @if (count($history) == 0)
                            <div class="col-12 text-center">
                                <div class="card">
                                    <div class="card-body">
                                        <h3>No History Found</h3>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <!-- The time line -->
                                @foreach ($history as $row)
                                    <div class="timeline">
                                        <!-- timeline time label -->
                                        <div class="time-label">
                                            <span class="bg-red">{{ $row->created_at->format('d-m-Y h:m:s') }}</span>
                                        </div>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fas fa-arrow-right bg-blue"></i>
                                            <div class="timeline-item">
                                                {{-- <span class="time"><i class="fas fa-clock"></i> 12:05</span> --}}
                                                <h3 class="timeline-header">Start working in <a
                                                        href="#">{{ $row->mineral_title->district }}</a>
                                                </h3>

                                                <div class="timeline-body">
                                                    <h4>Mineral Title Detail</h4>
                                                    <table>
                                                        <tr>
                                                            <th>Code</th>
                                                            <td>:7{{ $row->mineral_title->code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Parties</th>
                                                            <td>:{{ $row->mineral_title->parties }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Minerals</th>
                                                            <td>:{{ $row->mineral_title->mineral_group }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="timeline-footer">
                                                    {{-- <a class="btn btn-primary btn-sm">Read more</a>
                                                <a class="btn btn-danger btn-sm">Delete</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->
                                        <!-- timeline item -->
                                        <div>
                                            <i class="fas fa-arrow-right bg-yellow"></i>
                                            <div class="timeline-item">
                                                {{-- <span class="time"><i class="fas fa-clock"></i> 27 mins ago</span> --}}
                                                <h3 class="timeline-header"><a href="#">Mining Area</a></h3>
                                                <div class="timeline-body">
                                                    <table>
                                                        <tr>
                                                            <th>Code</th>
                                                            <td>:7{{ $row->mining_area->code }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Parties</th>
                                                            <td>:{{ $row->mining_area->parties }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="timeline-footer">
                                                    {{-- <a class="btn btn-warning btn-sm">View comment</a> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <!-- /.col -->
                    </div>
                </div>
                <!-- /.timeline -->

            </section>
        </div>
    </div>
@endsection

@push('styles')
    <style></style>
@endpush
