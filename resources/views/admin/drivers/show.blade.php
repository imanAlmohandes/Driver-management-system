@extends('admin.master')

@section('title', __('driver.driver_details') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.driver_details') }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <div class="mb-3">
                @if ($driver->driver_image)
                    <img src="{{ asset('uploads/drivers/' . $driver->driver_image) }}" alt="" width="150"
                        class="">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->user->name) }}&size=150&background=random"
                        alt="" width="150" class="rounded-circle">
                @endif

            </div>

            <p><strong>{{ __('driver.name') }}:</strong> {{ $driver->user->name ?? '-' }}</p>
            <p><strong>{{ __('driver.license_number') }}:</strong> {{ $driver->license_number }}</p>
            <p><strong>{{ __('driver.license_type') }}:</strong> {{ $driver->license_type }}</p>
            <p><strong>{{ __('driver.license_expiry') }}:</strong> {{ $driver->license_expiry_date }}</p>

            <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
                {{ __('driver.back_to_drivers') }}
            </a>
        </div>
    </div>

@endsection
