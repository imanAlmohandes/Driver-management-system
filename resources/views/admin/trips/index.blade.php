@extends('admin.master')

@section('title', __('driver.all_trips') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_trips') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.trips.create') }}">
                <i class="fas fa-plus"></i> {{ __('driver.add_new_trip') }}
            </a>
            <a class="btn btn-danger w-25 mr-2" href="{{ route('admin.trips.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>

        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.trips', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>

        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row align-items-end">
                    <div class="col-md-2"><label>{{ __('driver.driver') }} :</label><select name="driver_id"
                            class="form-control">
                            <option value="">{{ __('driver.all') }}</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><label>{{ __('driver.vehicle') }} :</label><select name="vehicle_id"
                            class="form-control">
                            <option value="">{{ __('driver.all') }}</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><label>{{ __('driver.status') }} :</label><select name="status"
                            class="form-control">
                            <option value="">{{ __('driver.all') }}</option>
@foreach (App\Models\Trip::STATUSES as $key => $val)
    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
        {{ __('driver.status_' . strtolower($key)) }}
    </option>
@endforeach
                        </select></div>
                    <div class="col-md-2"><label>{{ __('driver.from_station') }} :</label><input type="date"
                            name="from_date" class="form-control"></div>
                    <div class="col-md-2"><label>{{ __('driver.to_station') }} :</label><input type="date"
                            name="to_date" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex"><button type="submit" class="btn btn-primary mr-2 w-100 mt-3"><i
                                class="fas fa-filter"></i></button><a href="{{ route('admin.trips.index') }}"
                            class="btn btn-secondary w-100 mt-3"><i class="fas fa-times"></i></a></div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.trips_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                @include('admin.trips.partials.table')
            </div>
        </div>
    </div>
@stop
