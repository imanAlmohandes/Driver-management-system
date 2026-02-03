{{-- @extends('admin.master')

@section('title', __('driver.companies_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 mb-4">{{ __('driver.companies_trash') }}</h1>

        @if ($companies->count() > 0)
            <div class="mb-3">
                <a href="{{ route('admin.maintenance_companies.restore_all') }}" class="btn btn-success "
                    onclick="return confirm('{{ __('driver.confirm_restore_all_companies') }}')">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.maintenance_companies.delete_all') }}" class="btn btn-danger "
                    onclick="return confirm('{{ __('driver.confirm_delete_all_companies') }}')">
                    {{ __('driver.delete_all') }}
                </a>
            </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('driver.name') }}</th>
                <th>{{ __('driver.phone') }}</th>
                <th>{{ __('driver.address') }}</th>
                <th>{{ __('driver.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->name }}</td>
                    <td>{{ $company->phone }}</td>
                    <td>{{ $company->address }}</td>
                    <td>
                        <a href="{{ route('admin.maintenance_companies.restore', $company->id) }}"
                            class="btn btn-success btn-sm">{{ __('driver.restore') }}</a>
                        <a href="{{ route('admin.maintenance_companies.forcedelete', $company->id) }}"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('{{ __('driver.confirm_permanent_delete_company') }}')">{{ __('driver.delete') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info">{{ __('driver.no_deleted_companies') }}.</div>
    @endif

    <a href="{{ route('admin.maintenance_companies.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_companies') }}
    </a>

@endsection --}}
@extends('admin.master')

@section('title', __('driver.companies_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-4">{{ __('driver.companies_trash') }}</h1>

        @if ($companies->count() > 0)
            <div class="mb-3">
                <a href="{{ route('admin.maintenance_companies.restore_all') }}" class="btn btn-success" data-confirm
                    data-action="restore" data-item="{{ __('driver.all_maintenance_companies') }}">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.maintenance_companies.delete_all') }}" class="btn btn-danger ml-2" data-confirm
                    data-action="delete" data-item="{{ __('driver.all_maintenance_companies') }}">
                    {{ __('driver.delete_all') }}
                </a>

            </div>
        @endif
    </div>

    @if ($companies->count() > 0)
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.name') }}</th>
                                <th>{{ __('driver.phone') }}</th>
                                <th>{{ __('driver.address') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td>{{ $company->id }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>{{ $company->phone }}</td>
                                    <td>{{ $company->address }}</td>
                                    <td>
                                        <a href="{{ route('admin.maintenance_companies.restore', $company->id) }}"
                                            class="btn btn-success btn-sm">{{ __('driver.restore') }}</a>
                                        <a href="{{ route('admin.maintenance_companies.forcedelete', $company->id) }}"
                                            class="btn btn-danger btn-sm" data-confirm
                                            data-message="{{ __('driver.confirm_permanent_delete_company') }}">
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
        <div class="alert alert-info">{{ __('driver.no_deleted_companies') }}.</div>
    @endif

    <a href="{{ route('admin.maintenance_companies.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_companies') }}
    </a>
@endsection
