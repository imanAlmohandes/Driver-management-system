@extends('admin.master')

@section('title', __('driver.company_details') . ' | ' . config('app.name'))

@section('content')

<h1 class="h3 mb-4">{{ __('driver.company_details') }}</h1>

<div class="card shadow">
    <div class="card-body">
            <p><strong>{{ __('driver.name') }}:</strong> {{ $company->name }}</p>
            <p><strong>{{ __('driver.phone') }}:</strong> {{ $company->phone ?? '-' }}</p>
            <p><strong>{{ __('driver.address') }}:</strong> {{ $company->address ?? '-' }}</p>
            <a href="{{ route('admin.maintenance_companies.index') }}" class="btn btn-secondary">{{ __('driver.back_to_companies') }}</a>
    </div>
</div>

@endsection
