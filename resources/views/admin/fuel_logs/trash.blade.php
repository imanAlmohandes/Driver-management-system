@extends('admin.master')

@section('title', __('driver.fuel_logs_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-4 text-gray-800">{{ __('driver.fuel_logs_trash') }}</h1>

        @if ($fuelLogs->count() > 0)
            <div class="mb-3 d-flex gap-2">
                <a href="{{ route('admin.fuel_logs.restore_all') }}" class="btn btn-success "
                    onclick="return confirm('{{ __('driver.confirm_restore_all_fuel') }}')">{{ __('driver.restore_all') }}</a>
                <a href="{{ route('admin.fuel_logs.delete_all') }}" class="btn btn-danger "
                    onclick="return confirm('{{ __('driver.confirm_delete_all_fuel') }}')">{{ __('driver.delete_all') }}</a>
            </div>
        @endif
    </div>
    @if ($fuelLogs->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.driver') }}</th>
                                <th>{{ __('driver.vehicle') }}</th>
                                <th>{{ __('driver.receipt') }}</th>
                                <th>{{ __('driver.station') }}</th>
                                <th>{{ __('driver.fuel_amount') }}</th>
                                <th>{{ __('driver.fuel_cost') }}</th>
                                <th>{{ __('driver.log_date') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fuelLogs as $fuelLog)
                                <tr>
                                    <td>{{ $fuelLog->id }}</td>
                                    <td>{{ $fuelLog->driver->user->name ?? '-' }}</td>
                                    <td>{{ $fuelLog->trip?->vehicle?->type ?? '-' }} - {{ $fuelLog->trip?->vehicle?->plate_number ?? '-' }}</td>

                                    <td>{{ $fuelLog->receipt_number }}</td>
                                    <td>{{ $fuelLog->station_name }}</td>
                                    <td>{{ $fuelLog->fuel_amount }} {{ __('driver.l') }}</td>
                                    <td>{{ $fuelLog->fuel_cost }} {{ __('driver.$') }}</td>
                                    <td>{{ $fuelLog->log_date }}</td>
                                    <td>
                                        <a href="{{ route('admin.fuel_logs.restore', $fuelLog->id) }}"
                                            class="btn btn-success btn-sm">{{ __('driver.restore') }}</a>
                                        <a href="{{ route('admin.fuel_logs.forcedelete', $fuelLog->id) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('driver.confirm_permanent_delete_fuel') }}')">
                                            {{ __('driver.delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">{{ __('driver.no_deleted_fuel_logs') }}.</div>
    @endif

    <a href="{{ route('admin.fuel_logs.index') }}" class="btn btn-secondary mt-3">{{ __('driver.back_to_fuel_logs') }}</a>

@stop
