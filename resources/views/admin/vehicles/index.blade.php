@extends('admin.master')

@section('title', __('driver.vehicles') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_vehicles') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.vehicles.create') }}">
                <i class="fas fa-plus"></i> {{ __('driver.add_new_vehicle') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.vehicles.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.vehicles', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>
        <div class="card-body">
            <form id="filter-form">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>{{ __('driver.search') }}</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('driver.search_by_plate_type_model') }}">
                    </div>

                    <div class="col-md-4">
                        <label>{{ __('driver.status') }}</label>
                        <select name="status" class="form-control">
                            <option value="">{{ __('driver.all_statuses') }}</option>
                            @foreach (App\Models\Vehicle::STATUSES as $key => $val)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ __('driver.status_' . strtolower($key)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary mr-2 w-100"><i class="fas fa-filter"></i>
                            {{ __('driver.filter') }}</button>
                        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary w-25"><i
                                class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.vehicles_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                @include('admin.vehicles.partials.table')
            </div>
        </div>
    </div>

@endsection
