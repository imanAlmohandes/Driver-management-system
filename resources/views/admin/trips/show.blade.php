@extends('admin.master')

@section('title', __('driver.trip_details') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.trip_details') }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <p><strong>{{ __('driver.driver') }} :</strong> {{ $trip->driver->user->name ?? '-' }}</p>
            <p><strong>{{ __('driver.vehicle') }} :</strong> {{ $trip->vehicle->plate_number ?? '-' }}</p>
            <p><strong>{{ __('driver.from_station') }} :</strong> {{ $trip->from_station ?? '-' }}</p>
            <p><strong>{{ __('driver.to_station') }} :</strong> {{ $trip->to_station ?? '-' }}</p>
            <p><strong>{{ __('driver.start_time') }} :</strong> {{ $trip->start_time ?? '-' }}</p>
            <p><strong>{{ __('driver.end_time') }} :</strong> {{ $trip->end_time ?? '-' }}</p>
            <p><strong>{{ __('driver.distance_km') }} :</strong> {{ $trip->distance_km ?? '-' }}</p>
            <p><strong>{{ __('driver.status') }} :</strong> {{ $trip->status_label }}</p>
            <p><strong>{{ __('driver.notes') }} :</strong> {{ $trip->notes ?? '-' }}</p>

            <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">
                {{ __('driver.back_to_trips') }}
            </a>
        </div>
    </div>

@endsection
