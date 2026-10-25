@push('styles')
@endpush

@push('scripts')
    <script>
        @if ($message = Session::get('message'))
            toastr.info('{{ $message }}')
        @endif

        @if ($message = Session::get('success'))
            toastr.success('{{ $message }}')
        @endif

        @if ($message = Session::get('error'))
            toastr.error('{{ $message }}')
        @endif

        @if ($message = Session::get('warning'))
            toastr.warning('{{ $message }}')
        @endif

        @if ($message = Session::get('info'))
            toastr.info('{{ $message }}')
        @endif

        @if ($errors->any())
            toastr.error('There are some requirements to be filled in form');
        @endif
    </script>
@endpush
