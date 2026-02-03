@extends('admin.master')
@section('title', __('driver.all_drivers') . ' | ' . config('app.name'))
{{-- @section('title', 'Drivers | ' . env('APP_NAME')) --}}

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_drivers') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-50 ">
            <a class="btn btn-success w-25 mr-2 newMargin" href="{{ route('admin.drivers.create') }}">
                <i class="fas fa-plus"></i> {{ __('driver.add_new') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.drivers.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.drivers', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>

        <div class="card-body">
            <form id="filter-form">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>{{ __('driver.search') }}</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('driver.search_by_name_license') }}">
                    </div>
                    <div class="col-md-4">
                        <label>{{ __('driver.status') }}</label>
                        <select name="status" class="form-control">
                            <option value="">{{ __('driver.all_statuses') }}</option>
                            <option value="active">{{ __('driver.active') }}</option>
                            <option value="inactive">{{ __('driver.inactive') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary mr-2 w-100"><i class="fas fa-filter"></i>
                            {{ __('driver.filter') }}</button>
                        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary w-25"
                            title="{{ __('driver.clear') }}"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.drivers_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                {{-- The table will be loaded here initially and via AJAX --}}
                @include('admin.drivers.partials.table')
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        // Function to fetch and update the table
        function fetch_data(url) {
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('table-wrapper').innerHTML = html;
                });
        }

        // Handle form submission
        document.getElementById('filter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const params = new URLSearchParams(new FormData(this));
            const url = `{{ route('admin.drivers.index') }}?${params.toString()}`;
            history.pushState(null, '', url); // Optional: Update URL in browser
            fetch_data(url);
        });

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                const url = e.target.href;
                history.pushState(null, '', url); // Optional: Update URL in browser
                fetch_data(url);
            }
        });
    </script>

@endsection
