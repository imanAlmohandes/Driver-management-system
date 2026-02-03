@extends('admin.master')

@section('title', __('driver.vehicles_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-4 text-gray-800">{{ __('driver.vehicles_trash') }}</h1>

        @if ($vehicles->count() > 0)
            <div class="mb-3 d-flex gap-2">
                {{-- <a href="{{ route('admin.vehicles.restore_all') }}" class="btn btn-success "
                    onclick="return confirm('{{ __('driver.confirm_restore_all_vehicles') }}')">
                    {{ __('driver.restore_all') }}
                </a>

                <a href="{{ route('admin.vehicles.delete_all') }}" class="btn btn-danger  ml-2"
                    onclick="return confirm('{{ __('driver.confirm_delete_all_vehicles') }}')">
                    {{ __('driver.delete_all') }}
                </a> --}}

                <a href="{{ route('admin.vehicles.restore_all') }}" class="btn btn-success" data-confirm data-action="restore"
                    data-item="{{ __('driver.vehicles') }}">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.vehicles.delete_all') }}" class="btn btn-danger ml-2" data-confirm data-action="delete"
                    data-item="{{ __('driver.vehicles') }}">
                    {{ __('driver.delete_all') }}
                </a>
            </div>
        @endif
    </div>

    @if ($vehicles->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.type') }}</th>
                                <th>{{ __('driver.model') }}</th>
                                <th>{{ __('driver.plate_number') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->id }}</td>
                                    <td>{{ $vehicle->type }}</td>
                                    <td>{{ $vehicle->model }}</td>
                                    <td>{{ $vehicle->plate_number }}</td>
                                    <td>
                                        <a href="{{ route('admin.vehicles.restore', $vehicle->id) }}"
                                            class="btn btn-success btn-sm">
                                            {{ __('driver.restore') }}
                                        </a>
                                        <a href="{{ route('admin.vehicles.forcedelete', $vehicle->id) }}"
                                            class="btn btn-danger btn-sm" data-confirm
                                            data-message="{{ __('driver.confirm_permanent_delete_vehicle') }}">
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
            {{ __('driver.no_deleted_vehicles') }}.
        </div>
    @endif

    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_vehicles') }}
    </a>

@stop
