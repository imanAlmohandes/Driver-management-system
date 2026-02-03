@extends('admin.master')
@section('title', __('driver.all_notifications'))
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">{{ __('driver.all_notifications') }}
            ({{ trans_choice('driver.unread_notifications', $notifications->whereNull('read_at')->count()) }})
            {{-- ({{ $notifications->whereNull('read_at')->count() }} Unread) --}}
        </h1>
        <div>
            @if ($notifications->whereNull('read_at')->count() > 0)
                <a href="{{ route('admin.notifications.read_all') }}" class="btn btn-sm btn-success"><i
                        class="fas fa-check-double"></i> {{ __('driver.mark_all_as_read') }}</a>
            @endif
            @if ($notifications->count() > 0)
                <a href="{{ route('admin.notifications.destroy_all') }}" class="btn btn-sm btn-danger"
                    onclick="return confirm('{{ __('driver.confirm_delete_all_notifications') }}')">
                    <i class="fas fa-times-circle"></i> {{ __('driver.delete_all') }}</a>
            @endif
        </div>
    </div>

    {{--  For Success, Info, Warning, Danger Messages --}}
    @if (session('msg'))
        <div class="alert alert-{{ session('type') ?? 'info' }} alert-dismissible fade show" role="alert">
            {{ session('msg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    {{--  For Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ __('driver.validation_error_title') }}.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse ($notifications as $notification)
                    <div class="list-group-item d-flex align-items-center {{ $notification->read_at ? '' : 'bg-light' }}">

                        <a href="{{ route('admin.notifications.read', $notification->id) }}"
                            class="flex-fill text-decoration-none text-gray-800">
                            <div class="d-flex w-100">
                                <div class="mr-3">
                                    <div class="icon-circle bg-{{ $notification->data['color'] ?? 'primary' }}">
                                        <i class="fas {{ $notification->data['icon'] ?? 'fa-bell' }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-fill">
                                    <p class="{{ $notification->read_at ? '' : 'font-weight-bold' }} mb-0">
                                        {{ $notification->data['text'] }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>

                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('driver.confirm_delete') }}')">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-circle btn-outline-danger ml-3" title="Delete"><i
                                    class="fas fa-trash"></i></button>
                        </form>
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted p-5">{{ __('driver.no_notifications') }}.</div>
                @endforelse
            </div>
        </div>
        @if ($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
@stop
