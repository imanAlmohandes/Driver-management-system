@extends('admin.master')
@section('title', __('driver.fuel_log_details') . ' | ' . config('app.name'))
@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('driver.fuel_log_details') }}</h1>
<div class="card shadow">
    <div class="card-body">
        @if ($fuelLog->receipt_image_path)
            <div class="mb-3">
                <a href="{{ asset('uploads/fuelLogs/' . $fuelLog->receipt_image_path) }}" target="_blank">
                    <img src="{{ asset('uploads/fuelLogs/' . $fuelLog->receipt_image_path) }}" alt="Receipt Image" width="200" class="img-thumbnail">
                </a>
            </div>
        @endif
        <p><strong>{{ __('driver.receipt') }}:</strong> {{ $fuelLog->receipt_number }}</p>
        <p><strong>{{ __('driver.station') }}:</strong> {{ $fuelLog->station_name ?? 'N/A' }}</p>
        <p><strong>{{ __('driver.driver') }}:</strong> {{ $fuelLog->driver->user->name ?? '-' }}</p>
        <p><strong>{{ __('driver.trip') }}:</strong> {{ $fuelLog->trip->id ?? '-' }} ({{ $fuelLog->trip->vehicle->plate_number ?? '-' }})</p>
        <p><strong>{{ __('driver.fuel_amount') }}:</strong> {{ $fuelLog->fuel_amount }} {{ __('driver.l') }}</p>
        <p><strong>{{ __('driver.fuel_cost') }}:</strong> {{ $fuelLog->fuel_cost }} {{ __('driver.$') }}</p>
        <p><strong>{{ __('driver.log_date') }}:</strong> {{ $fuelLog->log_date }}</p>
        <a href="{{ route('admin.fuel_logs.index') }}" class="btn btn-secondary">{{ __('driver.back_to_fuel_logs') }}</a>
    </div>
</div>
@endsection
