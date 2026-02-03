@extends('admin.master')

@section('title', __('driver.stations') . ' | ' . config('app.name'))

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_stations') }}</h1>
        <div class="d-flex justify-content-end align-items-center w-75 ">
            <a class="btn btn-success w-25 mr-2" href="{{ route('admin.stations.create') }}">
                <i class="fas fa-plus"></i>{{ __('driver.add_new_station') }}
            </a>
            <a class="btn btn-danger w-25" href="{{ route('admin.stations.trash') }}">
                <i class="fas fa-trash"></i> {{ __('driver.recycle_bin') }}
            </a>
        </div>
    </div>

    @include('admin.alert')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.filters') }}</h6>
            <a href="{{ route('admin.export.stations', request()->query()) }}" class="btn btn-info w-25">
                <i class="fas fa-file-excel mr-2"></i> {{ __('driver.export') }}
            </a>
        </div>
        {{-- Filter Form --}}
        <div class="card-body">
            <form action="{{ route('admin.stations.index') }}" method="GET">
                <div class="d-flex">
                    <input type="text" name="search" class="form-control"
                        placeholder="{{ __('driver.search_by_name_city') }}" value="{{ request('search') }}">
                    <div class="col-md-3 d-flex">
                        <button class="btn btn-primary mr-2 w-75" type="submit"><i class="fas fa-search"></i>
                            {{ __('driver.search') }}</button>
                        <a href="{{ route('admin.stations.index') }}" class="btn btn-secondary"><i
                                class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('driver.stations_list') }}</h6>
        </div>
        <div class="card-body">
            <div id="table-wrapper">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('driver.name') }}</th>
                            <th>{{ __('driver.city') }}</th>
                            <th>{{ __('driver.from_her') }}</th>
                            <th>{{ __('driver.to_her') }}</th>
                            <th>{{ __('driver.created_at') }}</th>
                            <th>{{ __('driver.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stations as $station)
                            <tr>
                                <td>{{ $station->id }}</td>
                                <td>{{ $station->name }}</td>
                                <td>{{ $station->city }}</td>
                                <td>
                                    <span class="badge bg-success"
                                        style="color: #fff">{{ $station->trips_from_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary"
                                        style="color: #fff">{{ $station->trips_to_count }}</span>
                                </td>

                                <td>{{ $station->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.stations.show', $station->id) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.stations.edit', $station->id) }}"><i
                                            class="fas fa-edit"></i></a>
                                    <form class="delete-form d-inline"
                                        action="{{ route('admin.stations.destroy', $station->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-sm btn-danger" data-confirm
                                            data-action="delete" data-item="{{ __('driver.station') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('driver.no_stations_found') }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Add withQueryString() to keep filter parameters in pagination --}}
                @if ($stations->hasPages())
                    <div class="mt-3">
                        {{ $stations->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    @stop
