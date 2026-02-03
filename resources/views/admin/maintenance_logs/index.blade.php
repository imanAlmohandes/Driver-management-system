@extends('admin.master')

@section('title', __('driver.maintenanceLogs') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_maintenance_logs') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.maintenance_logs.create') }}">
                <i class="fas fa-plus"></i>{{ __('driver.add_new_maintenance_log') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.maintenance_logs.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>
    
    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.maintenance_logs', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.maintenance_logs.index') }}" method="GET">
                {{-- Row 1: Main Filters --}}
                <div class="row mb-3">
                    <div class="col-md-4"><label>{{ __('driver.vehicle') }} :</label><select name="vehicle_id"
                            class="form-control">
                            <option value="">{{ __('driver.all') }}</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @selected(request('vehicle_id') == $vehicle->id)>
                                    {{ $vehicle->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><label>{{ __('driver.company') }} :</label><select name="company_id"
                            class="form-control">
                            <option value="">{{ __('driver.all') }}</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->name }}
                                </option>
                            @endforeach
                        </select></div>
                    <div class="col-md-4"><label>{{ __('driver.service_type') }}</label><input type="text"
                            name="service_type" class="form-control" placeholder="e.g., Oil Change"
                            value="{{ request('service_type') }}">
                    </div>
                </div>
                {{-- Row 2: Date and Cost Filters --}}
                <div class="row align-items-end">
                    <div class="col-md-3"><label>{{ __('driver.from_date') }} :</label><input type="date"
                            name="from_date" class="form-control" value="{{ request('from_date') }}"></div>
                    <div class="col-md-3"><label>{{ __('driver.to_date') }} :</label><input type="date" name="to_date"
                            class="form-control" value="{{ request('to_date') }}"></div>
                    <div class="col-md-4">
                        <label>{{ __('driver.cost_range') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="min_cost" class="form-control"
                                placeholder="{{ __('driver.min') }}" value="{{ request('min_cost') }}">
                            <input type="number" step="0.01" name="max_cost" class="form-control"
                                placeholder="{{ __('driver.max') }}" value="{{ request('max_cost') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex"><button type="submit" class="btn btn-primary mr-2 w-100"><i
                                class="fas fa-filter"></i></button><a href="{{ route('admin.maintenance_logs.index') }}"
                            class="btn btn-secondary w-100"><i class="fas fa-times"></i></a></div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.maintenance_logs_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('driver.vehicle') }}</th>
                            <th>{{ __('driver.maintenance_company') }}</th>
                            <th>{{ __('driver.service_type') }} </th>
                            <th>{{ __('driver.cost') }}</th>
                            <th>{{ __('driver.service_date') }} </th>
                            <th>{{ __('driver.notes') }}</th>
                            <th>{{ __('driver.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($maintenanceLogs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->vehicle->type ?? '-' }} - {{ $log->vehicle->plate_number ?? '-' }}</td>
                                <td>{{ $log->company->name ?? '-' }}</td>
                                <td>{{ $log->service_type }}</td>
                                <td>{{ $log->cost }}</td>
                                <td>{{ $log->service_date }}</td>
                                <td>{!! Str::words($log->notes, 5, ' . . .') !!}</td>
                                <td>
                                    <a href="{{ route('admin.maintenance_logs.show', $log->id) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.maintenance_logs.edit', $log->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="d-inline" action="{{ route('admin.maintenance_logs.destroy', $log->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('{{ __('driver.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    {{ __('driver.no_maintenance_logs_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($maintenanceLogs->hasPages())
                    <div class="mt-3">
                        {{ $maintenanceLogs->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
