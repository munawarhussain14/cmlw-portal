@extends('layouts.authApp')

@section('content')
<!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body" style="padding:40px;">
      <p class="login-box-msg">
        <h4 class="pl-2 text-center">CMLW</h4>
        {{-- {{ __('Login') }} --}}
      </p>
      <br>

      <form method="POST" action="{{ route('login') }}">
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
        <div class="input-group mb-3">
          <input 
          id="password" 
          type="password" 
          class="form-control @error('password') is-invalid @enderror" 
          name="password" 
          placeholder="Password"
          required 
          autocomplete="current-password"
          />
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
          {{-- <div class="col-8">
            <div class="icheck-primary">
              <input 
              class="form-check-input" 
              type="checkbox" 
              name="remember" 
              id="remember" {{ old('remember') ? 'checked' : '' }}/>
              <label for="remember">
                {{ __('Remember Me') }}
              </label>
            </div>
          </div> --}}
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->
      @if (false&&Route::has('password.request'))
        <p class="mb-1">
          <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
        </p>
      @endif

      {{-- @if (Route::has('register'))
        <p class="mb-0">
          <a href="{{ route('register') }}" class="text-center">{{ __('Register') }}</a>
        </p>
      @endif --}}
    
    </div>
    <!-- /.login-card-body -->
  </div>
@endsection
