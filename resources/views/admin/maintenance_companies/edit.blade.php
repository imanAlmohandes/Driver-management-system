@extends('admin.master')

@section('title', __('driver.update_maintenance_company') . ' | ' . config('app.name'))

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_maintenance_company') }}</h1>

@include('admin.errors')

<form action="{{ route('admin.maintenance_companies.update', $company->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('admin.maintenance_companies._form')

    <button type="submit" class="btn btn-info">
        {{ __('driver.update_maintenance_company') }}
    </button>
</form>

@stop
