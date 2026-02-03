@extends('admin.master')

@section('title', __('driver.edit_vehicle') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_vehicle') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.vehicles._form')

        <button type="submit" class="btn btn-info">
            {{ __('driver.update_vehicle') }}

        </button>
    </form>

@stop
