@extends('layouts.auth-master')

@section('title', __('auth.verify_email_title'))
@section('image-class', 'bg-login-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">{{ __('auth.verify_email_title') }}</h1>
    </div>


    <div class="mb-4 text-sm text-gray-600">{{ __('auth.verify_email_text') }}</div>


    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm alert alert-success">{{ __('auth.verification_link_sent') }}</div>
    @endif


    <div class="mt-4 d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">@csrf<button type="submit"
                class="btn btn-primary btn-user">{{ __('auth.resend_verification') }}</button></form>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"
                class="btn btn-link text-sm">{{ __('auth.logout') }}</button></form>

    </div>
@endsection
