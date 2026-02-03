@extends('admin.master')

@section('title', __('driver.station_details') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.station_details') }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <p><strong>{{ __('driver.name') }}:</strong> {{ $station->name }}</p>
            <p><strong>{{ __('driver.city') }}:</strong> {{ $station->city }}</p>

            <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary">
                {{ __('driver.back_to_stations') }}
            </a>
        </div>
    </div>

@endsection
