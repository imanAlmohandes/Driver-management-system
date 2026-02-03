@extends('admin.master')

@section('title', __('driver.edit_trip') . ' | ' . config('app.name'))

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('driver.edit_trip') }}</h1>

@include('admin.errors')

<form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
    @csrf
    @method('PUT')

    @include('admin.trips._form')

    <button type="submit" class="btn btn-info">
        {{ __('driver.update_trip') }}
    </button>
</form>

@stop
