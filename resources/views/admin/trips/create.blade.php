@extends('admin.master')

@section('title', __('driver.create_trip') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.create_trip') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.trips.store') }}" method="POST">
        @csrf
        @include('admin.trips._form')
        <button type="submit" class="btn btn-info">
            {{ __('driver.add_new_trip') }}
        </button>
    </form>

@stop
