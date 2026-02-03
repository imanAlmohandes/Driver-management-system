@extends('admin.master')

@section('title', __('driver.trips_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 mb-4 text-gray-800">{{ __('driver.trips_trash') }}</h1>

        @if ($trips->count() > 0)
            <div class="mb-3 d-flex gap-2">
                <a href="{{ route('admin.trips.restore_all') }}" class="btn btn-success" data-confirm data-action="restore"
                    data-item="{{ __('driver.trips') }}">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.trips.delete_all') }}" class="btn btn-danger ml-2" data-confirm data-action="delete"
                    data-item="{{ __('driver.trips') }}">
                    {{ __('driver.delete_all') }}
                </a>

            </div>
        @endif
    </div>
    @if ($trips->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.driver') }}</th>
                                <th>{{ __('driver.vehicle') }}</th>
                                <th>{{ __('driver.from_station') }} </th>
                                <th>{{ __('driver.to_station') }} </th>
                                <th>{{ __('driver.start_time') }} </th>
                                <th>{{ __('driver.end_time') }} </th>
                                <th>{{ __('driver.distance_km') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trips as $trip)
                                <tr>
                                    <td>{{ $trip->id }}</td>
                                    <td>{{ $trip->driver->name ?? '-' }}</td>
                                    <td>{{ $trip->vehicle->type ?? '-' }} - {{ $trip->vehicle->plate_number ?? '-' }}</td>
                                    <td>{{ $trip->fromStation->name ?? '-' }}</td>
                                    <td>{{ $trip->toStation->name ?? '-' }}</td>
                                    <td>{{ $trip->start_time }}</td>
                                    <td>{{ $trip->end_time }}</td>
                                    <td>{{ $trip->distance_km }}</td>

                                    <td>
                                        <a href="{{ route('admin.trips.restore', $trip->id) }}"
                                            class="btn btn-success btn-sm">
                                            {{ __('driver.restore') }}
                                        </a>
                                        <a href="{{ route('admin.trips.forcedelete', $trip->id) }}"
                                            class="btn btn-danger btn-sm" data-confirm
                                            data-message="{{ __('driver.confirm_permanent_delete_trip') }}">
                                            {{ __('driver.delete') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            {{ __('driver.no_deleted_trips') }}.
        </div>
    @endif

    <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_trips') }}
    </a>

@stop
