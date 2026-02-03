@extends('admin.master')

@section('title', __('driver.edit_driver') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_driver') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.drivers._form')

        <button type="submit" class="btn btn-info">
            {{ __('driver.update_driver') }}
        </button>
    </form>

@stop
