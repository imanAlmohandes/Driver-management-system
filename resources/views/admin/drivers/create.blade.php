@extends('admin.master')

@section('title', __('driver.create_driver') . ' | ' . config('app.name'))

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.create_driver') }}</h1>

    @include('admin.errors')

    <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.drivers._form')
        <button type="submit" class="btn btn-info">
            {{ __('driver.add_driver') }}
        </button>
    </form>
@stop
