@extends('admin.master')

{{-- @section('title', 'Drivers Trash | ' . env('APP_NAME')) --}}
@section('title', __('driver.drivers_trash'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 mb-4 text-gray-800">{{ __('driver.drivers_trash') }}</h1>

        @if ($drivers->count() > 0)
            <div class="mb-3 d-flex gap-2">
                <a href="{{ route('admin.drivers.restore_all') }}" class="btn btn-success" data-confirm data-action="restore"
                    data-item="{{ __('driver.drivers') }}">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.drivers.delete_all') }}" class="btn btn-danger ml-2" data-confirm data-action="delete"
                    data-item="{{ __('driver.drivers') }}">
                    {{ __('driver.delete_all') }}
                </a>
            </div>
        @endif
    </div>

    @if ($drivers->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.image') }}</th>
                                <th>{{ __('driver.name') }}</th>
                                <th>{{ __('driver.license_number') }}</th>
                                <th>{{ __('driver.license_type') }}</th>
                                <th>{{ __('driver.license_expiry') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->id }}</td>
                                    <td>
                                        @if ($driver->driver_image)
                                            <img src="{{ asset('uploads/drivers/' . $driver->driver_image) }}"
                                                alt="" width="60" class="">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->user->name) }}&background=random"
                                                alt="" width="50" class="rounded-circle">
                                        @endif

                                    </td>
                                    <td>{{ $driver->user->name ?? '-' }}</td>
                                    <td>{{ $driver->license_number }}</td>
                                    <td>{{ $driver->license_type }}</td>
                                    <td>{{ $driver->license_expiry_date }}</td>
                                    <td>
                                        <a href="{{ route('admin.drivers.restore', $driver->id) }}"
                                            class="btn btn-success btn-sm">
                                            {{ __('driver.restore') }}
                                        </a>
                                        <a href="{{ route('admin.drivers.forcedelete', $driver->id) }}"
                                            class="btn btn-danger btn-sm" data-confirm
                                            data-message="{{ __('driver.confirm_permanent_delete') }}">
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
            {{ __('driver.no_deleted_drivers') }} </div>
    @endif

    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_drivers') }} </a>

@stop
