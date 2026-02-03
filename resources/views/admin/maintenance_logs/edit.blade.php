@extends('admin.master')

@section('title', __('driver.edit_maintenance_log') . ' | ' . config('app.name'))


@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_maintenance_log') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.maintenance_logs.update', $maintenanceLog->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.maintenance_logs._form')

        <button type="submit" class="btn btn-info">
            {{ __('driver.update_maintenance_log') }}
        </button>
    </form>

@stop
