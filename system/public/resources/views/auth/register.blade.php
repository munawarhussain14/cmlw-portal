@extends('layouts.authApp')

@section('content')
<!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">{{ __('Register a new membership') }}</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-group mb-3">
                <input id="name"
                placeholder="{{ __('Full Name') }}"
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                name="name" value="{{ old('name') }}" 
                required autocomplete="name" 
                autofocus/>
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

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

            <div class="input-group mb-3">
                <input 
                id="password" 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                name="password" 
                required 
                placeholder="{{ __('Password') }}"
                autocomplete="new-password"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input 
                id="password-confirm" 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                name="password_confirmation" 
                required 
                placeholder="{{ __('Confirm Password') }}"
                autocomplete="new-password"/>
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                    <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                    <label for="agreeTerms">
                    I agree to the <a href="#">terms</a>
                    </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
                </div>
                <!-- /.col -->
            </div>
            <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
        </form>            
    </div>
    <!-- /.login-card-body -->
  </div>
@endsection
