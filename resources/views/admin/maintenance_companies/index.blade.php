@extends('admin.master')

@section('title', __('driver.maintenance_companies') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_maintenance_companies') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.maintenance_companies.create') }}">
                <i class="fas fa-plus"></i> {{ __('driver.add_new_maintenance_company') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.maintenance_companies.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.maintenance_companies', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.maintenance_companies.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label>{{ __('driver.search') }}</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('driver.search_by_name_phone') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-5">
                        <label>{{ __('driver.filter_by_logs') }}</label>
                        <select name="logs_filter" class="form-control">
                            <option value="">{{ __('driver.all_logs') }}</option>
                            <option value="most" @selected(request('logs_filter') == 'most')>{{ __('driver.most_logs') }}</option>
                            <option value="lest" @selected(request('logs_filter') == 'lest')>{{ __('driver.lest_logs') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-primary mr-2 w-100"><i class="fas fa-filter"></i>
                            {{ __('driver.filter') }}</button>
                        <a href="{{ route('admin.maintenance_companies.index') }}" class="btn btn-secondary w-100"><i
                                class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.companies_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('driver.name') }}</th>
                            <th>{{ __('driver.maintenanceLogs') }}</th>
                            <th>{{ __('driver.phone') }}</th>
                            <th>{{ __('driver.address') }}</th>
                            <th>{{ __('driver.created_at') }}</th>
                            <th>{{ __('driver.updated_at') }}</th>
                            <th>{{ __('driver.active') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>
                                    <span class="badge bg-info text-white">
                                        {{ $company->maintenance_logs_count }}
                                    </span>
                                </td>

                                <td>{{ $company->phone ?? '-' }}</td>
                                <td>{{ Str::words($company->address ?? '-', 5, ' . . .') }}</td>
                                <td>{{ $company->created_at->diffForHumans() }}</td>
                                <td>{{ $company->updated_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.maintenance_companies.show', $company->id) }}"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.maintenance_companies.edit', $company->id) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form class="delete-form d-inline"
                                        action="{{ route('admin.maintenance_companies.destroy', $company->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-sm btn-danger" data-confirm
                                            data-action="delete" data-item="{{ __('driver.company') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    {{ __('driver.no_companies_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($companies->hasPages())
                    <div class="mt-3">
                        {{ $companies->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
