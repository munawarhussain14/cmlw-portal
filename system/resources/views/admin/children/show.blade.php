@extends('admin.layouts.app')

@push('styles')
    <style>
        .urdu {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/plugins/moment/moment.min.js') }}"></script>
    {{-- <script src="{{asset('assets/admin/plugins/inputmask/jquery.inputmask.min.js')}}"></script> --}}
    <script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.js') }}"></script>

    <script>
        $(function() {
            // $('[data-mask]').inputmask();
            $(".cnic").mask('00000-0000000-0');
            $(".cell").mask('0000-0000000');
        });
    </script>
@endpush

@section('content')
    @include('admin.scholarships.partials.showStudent', ['student' => $row])
    @include('admin.scholarships.partials.studentHistory', ['history' => $row->history])
@endsection
