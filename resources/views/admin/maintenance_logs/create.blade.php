@extends('admin.master')

@section('title', __('driver.create_maintenance_log') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.create_maintenance_log') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.maintenance_logs.store') }}" method="POST">
        @csrf
        @include('admin.maintenance_logs._form')
        <button type="submit" class="btn btn-info">
            {{ __('driver.add_maintenance_log') }}
        </button>
    </form>

@stop
