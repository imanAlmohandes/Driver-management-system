{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Activate Your Driver Account</title>
    <style>
        body {
            background: linear-gradient(135deg, #f4f7fb, #e9eef5);
        }

        .activate-card {
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .activate-card .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .activate-card .btn-success {
            border-radius: 14px;
            font-size: 16px;
            letter-spacing: .5px;
        }

        .activate-card .card-header {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
        }

        .activate-card label {
            font-size: 14px;
            color: #555;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="card shadow-lg border-0 rounded-4 activate-card">
                    <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                        <h4 class="mb-0"> Activate Your Driver Account</h4>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('driver.invite.store', $token) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Enter your full name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">License Number</label>
                                <input type="text" name="license_number" class="form-control"
                                    placeholder="Your driving license number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">License Type</label>
                                <input type="text" name="license_type" class="form-control"
                                    placeholder="Your driving license type" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">License Expiry Date</label>
                                <input type="date" name="license_expiry_date" class="form-control"
                                    placeholder="license expiry date" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Create a secure password" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Repeat your password" required>
                            </div>

                            <button class="btn btn-success w-100 py-2 fw-semibold">
                                Activate Account
                            </button>
                        </form>
                    </div>

                    <div class="card-footer bg-light text-center py-3">
                        <form method="POST" action="{{ route('driver.activate.resend', $invitation->token) }}">
                            @csrf
                            <button class="btn btn-link text-decoration-none small">
                                Resend activation link
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html> --}}
{{-- @extends('layouts.guest')

@section('title', __('driver.activate_account') . ' | ' . config('app.name'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card auth-card shadow-sm">
                <div class="card-header text-white">
                    <div class="d-flex align-items-center">
                        <div class="mr-3"
                            style="height:44px;width:44px;border-radius:14px;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">{{ __('driver.activate_account') ?? 'Activate Account' }}</h5>
                            <small class="opacity-75">Complete your driver profile</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Fix the following:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('msg'))
                        <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
                            {{ session('msg') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('driver.invite.store', $token) }}">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">Full name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="e.g. Ahmed Ali" required>
                            <div class="hint mt-1">Your name will appear in your driver profile.</div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">License number</label>
                                <input type="text" name="license_number" class="form-control"
                                    value="{{ old('license_number') }}" placeholder="e.g. LCS-123456" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">License type</label>
                                <input type="text" name="license_type" class="form-control"
                                    value="{{ old('license_type') }}" placeholder="e.g. B" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">License expiry date</label>
                            <input type="date" name="license_expiry_date" class="form-control"
                                value="{{ old('license_expiry_date') }}" required>
                        </div>

                        <hr class="my-4">

                        <div class="form-group">
                            <label class="font-weight-bold">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Create a secure password" required>
                            <div class="hint mt-1">Minimum 8 characters.</div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repeat password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <i class="fas fa-check-circle mr-2"></i>
                            Activate account
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            This link expires in 24 hours.
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-white text-center py-3">
                    <form method="POST" action="{{ route('driver.invite.resend', $token) }}">
                        @csrf
                        <button class="btn btn-link text-decoration-none small">
                            <i class="fas fa-redo mr-1"></i>
                            Resend activation link
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
@endsection --}}
@extends('layouts.guest')

@section('title', __('driver.activate_account') . ' | ' . config('app.name'))

@push('styles')
    <style>
        .auth-wrap {
            max-width: 560px;
            margin: auto;
        }

        .auth-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
        }

        .auth-head {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 18px 20px;
            color: #fff;
        }

        .logo-badge {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .btn-main {
            border-radius: 14px;
            padding: 12px 14px;
            font-weight: 600;
        }

        .muted-hint {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="card auth-card shadow-sm">
            <div class="auth-head">
                <div class="d-flex align-items-center">
                    <div class="logo-badge">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <div class="h5 mb-0">{{ __('driver.activate_account') ?? 'Activate Account' }}</div>
                        <div style="opacity:.85;font-size:13px;">{{ config('app.name') }} • Driver onboarding</div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                {{-- Flash message (success/error) --}}
                @if (session('msg'))
                    <div class="alert alert-{{ session('type', 'info') }} alert-dismissible fade show" role="alert">
                        {{ session('msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                {{-- Validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('driver.invite.store', $token) }}">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-bold">Full name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="e.g. Ahmed Ali" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">License number</label>
                            <input type="text" name="license_number" class="form-control"
                                value="{{ old('license_number') }}" placeholder="e.g. LCS-123456" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold">License type</label>
                            <input type="text" name="license_type" class="form-control" value="{{ old('license_type') }}"
                                placeholder="e.g. B" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">License expiry date</label>
                        <input type="date" name="license_expiry_date" class="form-control"
                            value="{{ old('license_expiry_date') }}" required>
                    </div>

                    <hr class="my-4">

                    <div class="form-group">
                        <label class="font-weight-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Confirm password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repeat password" required>
                        <div class="muted-hint mt-2">
                            This link expires in 24 hours.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-main">
                        <i class="fas fa-check-circle mr-2"></i> Activate account
                    </button>
                </form>
            </div>

            <div class="card-footer bg-white text-center py-3">
                <form method="POST" action="{{ route('driver.invite.resend', $token) }}">
                    @csrf
                    <button class="btn btn-link text-decoration-none small">
                        <i class="fas fa-redo mr-1"></i> Resend activation link
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
