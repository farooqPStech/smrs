@extends('layouts.auth-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <br>
            <br>
            <br>
            <div class="card" style="background-color: #e6f2f2; padding: 20px">
                <img src="{{ asset('img/brand/icon.png') }}" style="height: 1000%; width: 100%; margin-left: auto; margin-right: auto;">
                <i style="margin-left: auto; margin-right: auto;">Spot Meter Reading System test</i>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                            <label for="email" class="col-md-4 col-form-label">{{ __('Email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-smrs" style="float: right; width: 100%">
                                    {{ __('Login') }}
                                </button>
                                <br><br>
                                <b>For handheld user, please login using device</b>
                                <br>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" style="float: left" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
