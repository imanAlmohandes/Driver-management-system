@extends('layouts.auth-master')

@section('title', __('auth.confirm_password_title'))
@section('image-class', 'bg-password-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">{{ __('auth.confirm_password_title') }}</h1>
    </div>


    <div class="mb-4 text-sm text-gray-600">{{ __('auth.confirm_password_text') }}</div>


    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form class="user" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group"><input type="password" name="password" class="form-control form-control-user"
                placeholder="{{ __('auth.password_placeholder') }}" required></div>
        <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('auth.confirm') }}</button>
    </form>
@endsection
