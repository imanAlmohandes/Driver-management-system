@extends('admin.master')

@section('title', __('driver.create_driver') . ' | ' . config('app.name'))

@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                {{ __('driver.invite_driver') }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.drivers.invite.send') }}">
                    @csrf

                    <div class="mb-3">
                        <label>{{ __('driver.email') }}</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <button class="btn btn-success w-100">
                        {{ __('driver.send_invitation') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
