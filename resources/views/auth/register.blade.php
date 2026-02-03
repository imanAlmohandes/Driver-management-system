@extends('layouts.auth-master')

@section('title', 'Register')
@section('image-class', 'bg-register-image')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger p-2 small">
            @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- The form action should point to 'register', not 'password.store' --}}
    <form class="user" method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <input type="text" name="name" class="form-control form-control-user"
                placeholder="Full Name" value="{{ old('name') }}" required autofocus>
        </div>

        {{-- Email Address --}}
        <div class="form-group">
            <input type="email" name="email" class="form-control form-control-user"
                placeholder="Email Address" value="{{ old('email') }}" required>
        </div>

        {{-- Password --}}
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" name="password" class="form-control form-control-user"
                    placeholder="Password" required>
            </div>
            <div class="col-sm-6">
                <input type="password" name="password_confirmation" class="form-control form-control-user"
                    placeholder="Repeat Password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Register Account
        </button>

        <hr>

        {{-- Social Register Buttons --}}
        <a href="{{ route('social.redirect', 'google') }}" class="btn btn-google btn-user btn-block"><i class="fab fa-google fa-fw"></i> Register with Google</a>
        <a href="{{ route('social.redirect', 'facebook') }}" class="btn btn-facebook btn-user btn-block"><i class="fab fa-facebook-f fa-fw"></i> Register with Facebook</a>
        <a href="{{ route('social.redirect', 'twitter') }}" class="btn btn-twitter btn-user btn-block"><i class="fab fa-twitter fa-fw"></i> Register with Twitter</a>
    </form>

    <hr>

    <div class="text-center">
        <a class="small" href="{{ route('password.request') }}">Forgot Password?</a>
    </div>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
    </div>
@endsection
