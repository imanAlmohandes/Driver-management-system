@extends('admin.master')

@section('title', __('driver.stations_trash') . ' | ' . config('app.name'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="h3 mb-4 text-gray-800">{{ __('driver.stations_trash') }}</h1>

        @if ($stations->count() > 0)
            <div class="mb-3 d-flex gap-2">
                <a href="{{ route('admin.stations.restore_all') }}" class="btn btn-success" data-confirm data-action="restore"
                    data-item="{{ __('driver.stations') }}">
                    {{ __('driver.restore_all') }}
                </a>
                <a href="{{ route('admin.stations.delete_all') }}" class="btn btn-danger ml-2" data-confirm
                    data-action="delete" data-item="{{ __('driver.stations') }}">
                    {{ __('driver.delete_all') }}
                </a>
            </div>
        @endif
    </div>


    @if ($stations->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.name') }}</th>
                                <th>{{ __('driver.city') }}</th>
                                <th>{{ __('driver.deleted_at') }}</th>
                                <th>{{ __('driver.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stations as $station)
                                <tr>
                                    <td>{{ $station->id }}</td>
                                    <td>{{ $station->name }}</td>
                                    <td>{{ $station->city }}</td>
                                    <td>{{ $station->deleted_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.stations.restore', $station->id) }}"
                                            class="btn btn-success btn-sm">{{ __('driver.restore') }}</a>
                                        <a href="{{ route('admin.stations.forcedelete', $station->id) }}"
                                            class="btn btn-danger btn-sm" data-confirm
                                            data-message="{{ __('driver.confirm_permanent_delete_station') }}">
                                            {{ __('driver.delete') }}
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            {{ __('driver.no_deleted_stations') }}.
        </div>
    @endif

    <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary mt-3">
        {{ __('driver.back_to_stations') }}
    </a>

@stop
