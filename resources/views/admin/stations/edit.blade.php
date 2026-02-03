@extends('admin.master')

@section('title', __('driver.edit_station') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_station') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.stations.update', $station->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.stations._form')

        <button type="submit" class="btn btn-info">
            {{ __('driver.update_station') }} </button>
    </form>

@stop
