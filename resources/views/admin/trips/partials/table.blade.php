<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('driver.driver') }}</th>
            <th>{{ __('driver.vehicle') }}</th>
            <th>{{ __('driver.from_station') }}</th>
            <th>{{ __('driver.to_station') }}</th>
            <th>{{ __('driver.start_time') }}</th>
            <th>{{ __('driver.end_time') }}</th>
            <th>{{ __('driver.distance_km') }}</th>
            <th>{{ __('driver.status') }}</th>
            <th>{{ __('driver.notes') }}</th>
            <th>{{ __('driver.actions') }}</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($trips as $trip)
            <tr>
                <td>{{ $trip->id }}</td>
                <td>{{ $trip->driver->user->name ?? '-' }}</td>
                <td>{{ $trip->vehicle->type ?? '-' }} - {{ $trip->vehicle->plate_number ?? '-' }}</td>
                <td>{{ $trip->fromStation->name ?? '-' }}</td>
                <td>{{ $trip->toStation->name ?? '-' }}</td>
                <td>{{ $trip->start_time }}</td>
                <td>{{ $trip->end_time }}</td>
                <td>{{ $trip->distance_km }}</td>

                <td>
                    @php
                        $status = strtolower($trip->status);
                        $badgeClass =
                            [
                                'pending' => 'secondary',
                                'ongoing' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ][$status] ?? 'dark';
                    @endphp

                    <span class="badge badge-{{ $badgeClass }}">
                        {{ $trip->status_label }}
                    </span>
                </td>

                <td>{!! Str::words($trip->notes, 5, ' . . .') !!}</td>

                <td>
                    {{-- View --}}
                    <a href="{{ route('admin.trips.show', $trip->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-eye"></i>
                    </a>

                    {{-- Edit --}}
                    <a class="btn btn-sm btn-info" href="{{ route('admin.trips.edit', $trip->id) }}">
                        <i class="fas fa-edit"></i>
                    </a>

                    {{--   Yellow cancel button (only Pending/Ongoing) opens the modal --}}
                    @if (in_array($trip->status, ['Pending', 'Ongoing']))
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#cancelTripModal" data-action="{{ route('admin.trips.cancel', $trip->id) }}"
                            data-trip="#{{ $trip->id }}">
                            <i class="fas fa-ban"></i>
                        </button>
                    @endif

                    {{-- Delete trip --}}
                    <form class="delete-form d-inline" action="{{ route('admin.trips.destroy', $trip->id) }}"
                        method="post">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-sm btn-danger" data-confirm data-action="delete"
                            data-item="{{ __('driver.trip') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                    {{--   Dropdown to change status (cancel removed to avoid duplication) --}}
                    <div class="btn-group d-inline">
                        <button type="button" class="btn btn-sm btn-dark dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <div class="dropdown-menu">
                            @foreach (App\Models\Trip::STATUSES as $statusKey => $statusValue)
                                {{-- لا تعرض نفس الحالة الحالية --}}
                                @if ($trip->status == $statusKey)
                                    @continue
                                @endif

                                {{--   Hide Cancelled here because we already have the yellow cancel button --}}
                                @if ($statusKey == 'Cancelled')
                                    @continue
                                @endif

                                <form action="{{ route('admin.trips.change_status', $trip->id) }}" method="POST"
                                    class="px-2">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $statusKey }}">
                                    <button class="dropdown-item p-1" type="submit">
                                        {{ __('driver.status_' . strtolower($statusKey)) }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">{{ __('driver.no_trips_found') }}.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $trips->withQueryString()->links() }}
</div>


{{--   Cancel Trip Modal (ONE modal for all trips) --}}
<div class="modal fade" id="cancelTripModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-ban mr-2"></i> {{ __('driver.cancel_trip') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="cancelTripForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p id="cancelTripText" class="mb-3">
                        {{ __('driver.confirm_cancel_trip') }}
                    </p>

                    <div class="form-group">
                        <label class="font-weight-bold">
                            {{ __('driver.notes') }} ({{ __('driver.optional') ?? 'Optional' }})
                        </label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="{{ __('driver.write_reason') ?? 'Write reason...' }}"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('driver.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-1"></i> {{ __('driver.cancel_trip') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


{{--   Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#cancelTripModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);

            const action = button.data('action');
            const tripText = button.data('trip') || '';

            // Set form action
            $('#cancelTripForm').attr('action', action);

            // Set text
            const baseText = @json(__('driver.confirm_cancel_trip'));
            $('#cancelTripText').text(tripText ? `${baseText} ${tripText}` : baseText);

            // Clear textarea
            $('#cancelTripForm textarea[name="notes"]').val('');
        });

    });
</script>
