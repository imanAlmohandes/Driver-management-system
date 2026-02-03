@extends('admin.master')

@section('title', __('driver.vehicle_details') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.vehicle_details') }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <p><strong>{{ __('driver.type') }}:</strong> {{ $vehicle->type }}</p>
            <p><strong>{{ __('driver.model') }}:</strong> {{ $vehicle->model }}</p>
            <p><strong>{{ __('driver.plate_number') }} :</strong> {{ $vehicle->plate_number }}</p>
            <p><strong>{{ __('driver.status') }}:</strong>  {{ $vehicle->status_label }}</p>

            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
                {{ __('driver.back_to_vehicles') }}
            </a>
        </div>
    </div>

@endsection
