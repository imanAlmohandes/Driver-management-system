@extends('layouts.auth-master')

@section('title', __('auth.reset_password_title'))
@section('image-class', 'bg-password-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-2">{{ __('auth.reset_password_title') }}</h1>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger p-2 small">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form class="user" method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group"><input type="email" name="email" class="form-control form-control-user"
                placeholder="{{ __('auth.email_placeholder') }}" value="{{ old('email', $request->email) }}" required
                autofocus></div>

        <div class="form-group"><input type="password" name="password" class="form-control form-control-user"
                placeholder="{{ __('auth.new_password_placeholder') }}" required></div>
        <div class="form-group"><input type="password" name="password_confirmation" class="form-control form-control-user"
                placeholder="{{ __('auth.confirm_new_password_placeholder') }}" required></div>


        <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('auth.reset_password') }}</button>
    </form>
@endsection
