@extends('layouts.auth-master')

@section('title', __('auth.login_title'))
@section('image-class', 'bg-login-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">{{ __('auth.welcome_back') }}</h1>
    </div>

    <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

    @if($errors->any())
        <div class="alert alert-danger p-2 small">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form class="user" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" class="form-control form-control-user" placeholder="{{ __('auth.email_placeholder') }}" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control form-control-user" placeholder="{{ __('auth.password_placeholder') }}" required>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
                <label class="custom-control-label" for="customCheck">{{ __('auth.remember_me') }}</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('auth.log_in') }}</button>
        <hr>
        {{-- <a href="{{ route('social.redirect', 'google') }}" class="btn btn-google btn-user btn-block"><i class="fab fa-google fa-fw"></i> Login with Google</a>
        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-facebook btn-user btn-block"><i class="fab fa-facebook-f fa-fw"></i> Login with Facebook</a>
        <a href="{{ route('social.redirect', 'twitter') }}" class="btn btn-twitter btn-user btn-block"><i class="fab fa-twitter fa-fw"></i> Login with Twitter</a> --}}
        <a href="/auth/google" class="btn btn-google btn-user btn-block"><i class="fab fa-google fa-fw"></i> {{ __('auth.login_with_google') }}</a>
        {{-- <a href="/auth/facebook" class="btn btn-facebook btn-user btn-block"><i class="fab fa-facebook-f fa-fw"></i> Login with Facebook</a>
        <a href="/auth/x" class="btn btn-twitter btn-user btn-block"><i class="fab fa-twitter fa-fw"></i> Login with Twitter</a> --}}
    </form>
    <hr>
    <div class="text-center">
        @if (Route::has('password.request'))
            <a class="small" href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
        @endif
    </div>
    {{-- <div class="text-center">
        @if (Route::has('register'))
            <a class="small" href="{{ route('register') }}">Create an Account!</a>
        @endif
    </div> --}}
@endsection
