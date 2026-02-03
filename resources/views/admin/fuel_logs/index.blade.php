@extends('admin.master')

@section('title', __('driver.fuelLogs') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_fuel_logs') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.fuel_logs.create') }}">
                <i class="fas fa-plus"></i> {{ __('driver.add_new_fuel_log') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.fuel_logs.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.fuel_logs', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>
        <div class="card-body">
<form id="filter-form" action="{{ route('admin.fuel_logs.index') }}" method="GET">
                <div class="row d-flex align-items-end">
                    <div class="col-md-3">
                        <label>{{ __('driver.driver') }} :</label>
                        <select name="driver_id" class="form-control">
                            <option value="">-- {{ __('driver.all_drivers') }} --</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}"
                                    {{ request('driver_id') == $driver->id ? 'selected' : '' }}>{{ $driver->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('driver.from_date') }} :</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('driver.to_date') }} :</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3 d-flex">
                        {{-- <label class="mb-2">iman</label> --}}
                        <button class="btn btn-primary w-100 mr-2 form-control"><i class="fas fa-filter"></i>
                            {{ __('driver.filter') }}</button>
                        <a href="{{ route('admin.fuel_logs.index') }}" class="btn btn-secondary w-100 form-control"><i
                                class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.fuel_logs_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                @include('admin.fuel_logs.partials.table')

            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script>
        function fetch_data(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => document.getElementById('table-wrapper').innerHTML = html);
        }
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const url =
                `{{ route('admin.fuel_logs.index') }}?${new URLSearchParams(new FormData(this)).toString()}`;
            history.pushState(null, '', url);
            fetch_data(url);
        });
        document.addEventListener('click', function(e) {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                const url = e.target.href;
                history.pushState(null, '', url);
                fetch_data(url);
            }
        });
    </script>
@endsection
