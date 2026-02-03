@extends('admin.master')

@section('title', __('driver.edit_fuel_log') . ' | ' . config('app.name'))

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_fuel_log') }}</h1>

@include('admin.errors')

<form action="{{ route('admin.fuel_logs.update', $fuelLog->id) }}"  method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.fuel_logs._form')

    <button type="submit" class="btn btn-info">
        {{ __('driver.update_fuel_log') }}
    </button>
</form>

@stop
