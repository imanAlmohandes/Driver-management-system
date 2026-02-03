@extends('admin.master')

@section('title', __('driver.create_station') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.create_station') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.stations.store') }}" method="POST">
        @csrf
        @include('admin.stations._form')
        <button type="submit" class="btn btn-primary">{{ __('driver.add_new_station') }}</button>
    </form>

@stop
