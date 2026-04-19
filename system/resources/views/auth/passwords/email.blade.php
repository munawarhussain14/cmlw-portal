@extends('layouts.authApp')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Reset Password') }}</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group mb-3">
            <input 
            id="email" 
            type="email" 
            name="email"
            class="form-control @error('email') is-invalid @enderror" 
            placeholder="{{ __('Email Address') }}"
            value="{{ old('email') }}" required autocomplete="email" autofocus
            />
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Send Password Reset Link') }}    
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
