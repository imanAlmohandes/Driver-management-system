@extends('admin.master')

@section('title', __('driver.maintenance_logs_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.maintenance_logs_trash') }}</h1>

    @if ($maintenanceLogs->count() > 0)
        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('admin.maintenance_logs.restore_all') }}" class="btn btn-success "
                onclick="return confirm('{{ __('driver.confirm_restore_all_logs') }}')">
                {{ __('driver.restore_all') }}</a>
            <a href="{{ route('admin.maintenance_logs.delete_all') }}" class="btn btn-danger "
                onclick="return confirm('{{ __('driver.confirm_delete_all_logs') }}')">
                {{ __('driver.delete_all') }}
            </a>
        </div>
    @endif
    </div>
    @if ($maintenanceLogs->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.vehicle') }}</th>
                                <th>{{ __('driver.maintenance_company') }}</th>
                                <th>{{ __('driver.service_type') }} </th>
                                <th>{{ __('driver.cost') }}</th>
                                <th>{{ __('driver.service_date') }} </th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($maintenanceLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->vehicle->type ?? '-' }} - {{ $log->vehicle->plate_number ?? '-' }}</td>
                                    <td>{{ $log->company->name ?? '-' }}</td>
                                    <td>{{ $log->service_type }}</td>
                                    <td>{{ $log->cost }}</td>
                                    <td>{{ $log->service_date }}</td>
                                    <td>
                                        <a href="{{ route('admin.maintenance_logs.restore', $log->id) }}"
                                            class="btn btn-success btn-sm">{{ __('driver.restore') }}</a>
                                        <a href="{{ route('admin.maintenance_logs.forcedelete', $log->id) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ __('driver.confirm_permanent_delete_log') }}')">{{ __('driver.delete') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">{{ __('driver.no_deleted_logs') }}.</div>
    @endif

    <a href="{{ route('admin.maintenance_logs.index') }}"
        class="btn btn-secondary mt-3">{{ __('driver.back_to_logs') }}</a>

@endsection
