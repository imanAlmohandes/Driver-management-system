@extends('admin.master')

@section('title', __('driver.log_details') . ' | ' . config('app.name'))


@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.log_details') }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <p><strong>{{ __('driver.vehicle') }} :</strong> {{ $maintenanceLog->vehicle->type ?? '-' }} -
                {{ $maintenanceLog->vehicle->plate_number ?? '-' }}</p>
            <p><strong>{{ __('driver.maintenance_company') }} :</strong> {{ $maintenanceLog->company->name ?? '-' }}</p>
            <p><strong>{{ __('driver.service_type') }} :</strong> {{ $maintenanceLog->service_type }}</p>
            <p><strong>{{ __('driver.cost_range') }} :</strong> {{ $maintenanceLog->cost }}</p>
            <p><strong>{{ __('driver.service_date') }} :</strong> {{ $maintenanceLog->service_date }}</p>
            <p><strong>{{ __('driver.notes') }} :</strong> {{ $maintenanceLog->notes ?? '-' }}</p>
            <a href="{{ route('admin.maintenance_logs.index') }}"
                class="btn btn-secondary">{{ __('driver.back_to_logs') }}</a>
        </div>
    </div>

@endsection
