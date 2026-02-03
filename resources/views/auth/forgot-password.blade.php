@extends('layouts.auth-master')

@section('title', __('auth.forgot_password'))
@section('image-class', 'bg-password-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-2">{{ __('auth.forgot_password_title') }}</h1>
        <p class="mb-4 small">{{ __('auth.forgot_password_text') }}</p>
    </div>

    <x-auth-session-status class="mb-4 alert alert-success small" :status="session('status')" />

    @if($errors->any())
        <div class="alert alert-danger p-2 small">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form class="user" method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" class="form-control form-control-user" placeholder="{{ __('auth.email_placeholder') }}" value="{{ old('email') }}" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('auth.email_reset_link') }}</button>
    </form>
    <hr>
    <div class="text-center">
        {{-- <a class="small" href="{{ route('register') }}">Create an Account!</a> --}}
    </div>
    <div class="text-center">
    <div class="text-center"><a class="small" href="{{ route('login') }}">{{ __('auth.already_have_account') }}</a></div>
    </div>
@endsection
