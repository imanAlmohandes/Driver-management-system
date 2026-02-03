@extends('admin.master')

@section('title', __('driver.create_vehicle') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.create_vehicle') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.vehicles.store') }}" method="POST">
        @csrf
        @include('admin.vehicles._form')
        <button type="submit" class="btn btn-info">
            {{ __('driver.add_new_vehicle') }}
        </button>
    </form>

@stop
