<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('driver.my_work') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Oops!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if (session('msg'))
                <div class="p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Info</p>
                    <p>{{ session('msg') }}</p>
                </div>
            @endif

            {{-- Current Task --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('driver.current_task') }}</h3>
                </div>

                <div class="p-6">

                    {{-- Case 1: Ongoing trip --}}
                    @if ($active_trip)
                        <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded">
                            <p class="font-bold">{{ __('driver.trip_in_progress') }}</p>

                            <p class="text-gray-700 mt-2">
                                Route:
                                <strong>
                                    {{ $active_trip->fromStation->name ?? 'N/A' }}
                                    &rarr;
                                    {{ $active_trip->toStation->name ?? 'N/A' }}
                                </strong>
                            </p>

                            <form action="{{ route('driver.trips.complete', $active_trip->id) }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="w-full justify-center inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                    <i class="fas fa-flag-checkered mr-2"></i> {{ __('driver.complete_trip') }}
                                </button>
                            </form>
                        </div>

                    {{-- Case 2: Next pending trip --}}
                    @elseif($next_trip)

                        @php
                            $start = \Carbon\Carbon::parse($next_trip->start_time);
                            $showStart = $start->isBetween(now()->subMinutes(15), now()->addMinutes(30));

                            // السائق يلغي فقط خلال أول 15 دقيقة بعد وقت البداية
                            $canCancel = now()->between($start, $start->copy()->addMinutes(15));
                        @endphp

                        @if ($showStart)
                            <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded">
                                <p class="font-bold text-green-800">Ready to Go!</p>

                                <p class="text-gray-700 mt-2">
                                    Route:
                                    <strong>
                                        {{ $next_trip->fromStation->name ?? 'N/A' }}
                                        &rarr;
                                        {{ $next_trip->toStation->name ?? 'N/A' }}
                                    </strong>
                                    <br>
                                    Scheduled at: <strong>{{ $start->format('g:i A') }}</strong>
                                </p>

                                <form action="{{ route('driver.trips.start', $next_trip->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-500">
                                        <i class="fas fa-play-circle mr-2"></i> {{ __('driver.start_trip') ?? 'START TRIP' }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-clock fa-3x text-gray-400 mb-3"></i>
                                <h4 class="font-semibold text-gray-700">Your Next Trip is Scheduled</h4>
                                <p class="text-sm text-gray-500 mt-2">
                                    Route:
                                    <strong>
                                        {{ $next_trip->fromStation->name ?? 'N/A' }}
                                        &rarr;
                                        {{ $next_trip->toStation->name ?? 'N/A' }}
                                    </strong>
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Starts {{ $start->diffForHumans() }} ({{ $start->format('g:i A') }})
                                </p>
                            </div>
                        @endif

                        {{-- Cancel button (driver) --}}
                        @if ($canCancel)
                            <div class="mt-4 border-t pt-4">
                                <p class="text-sm text-gray-600">
                                    You can cancel this trip within 15 minutes after start time.
                                </p>

                                <button type="button"
                                    class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white font-semibold text-xs uppercase rounded"
                                    data-toggle="modal" data-target="#driverCancelTripModal">
                                    <i class="fas fa-ban mr-2"></i> {{ __('driver.cancel_trip') ?? 'Cancel Trip' }}
                                </button>
                            </div>

                            {{-- Driver Cancel Modal --}}
                            <div class="modal fade" id="driverCancelTripModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('driver.trips.cancel', $next_trip->id) }}" method="POST">
                                            @csrf

                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-ban mr-2"></i>
                                                    {{ __('driver.cancel_trip') ?? 'Cancel Trip' }}
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <p>
                                                    Trip:
                                                    <strong>
                                                        {{ $next_trip->fromStation->name ?? 'N/A' }}
                                                        &rarr;
                                                        {{ $next_trip->toStation->name ?? 'N/A' }}
                                                    </strong>
                                                </p>

                                                <div class="form-group mt-3">
                                                    <label class="font-weight-bold">
                                                        {{ __('driver.cancellation_reason') ?? 'Reason (Required)' }}
                                                    </label>
                                                    <textarea name="notes" class="form-control" rows="4" required minlength="10"
                                                        placeholder="{{ __('driver.write_reason') ?? 'Write reason...' }}"></textarea>
                                                    <small class="text-muted">
                                                        {{ __('driver.reason_min_10') ?? 'Minimum 10 characters.' }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    {{ __('driver.cancel') ?? 'Close' }}
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    {{ __('driver.confirm_cancellation') ?? 'Confirm Cancellation' }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                    {{-- Case 3: No tasks --}}
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-coffee fa-3x text-gray-400 mb-3"></i>
                            <h4 class="font-semibold text-gray-700">{{ __('driver.no_active_tasks') }}</h4>
                            <p class="text-sm text-gray-500">{{ __('driver.no_active_tasks_text') }}</p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Assigned Vehicle --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('driver.assigned_vehicle') }}</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if ($assigned_vehicle)
                        <p><strong>{{ __('driver.model') }}:</strong> {{ $assigned_vehicle->model }}</p>
                        <p>
                            <strong>{{ __('driver.plate_number') }}:</strong>
                            <span class="font-mono bg-gray-100 p-1 rounded">{{ $assigned_vehicle->plate_number }}</span>
                        </p>
                    @else
                        <p class="text-gray-500">{{ __('driver.no_vehicle_assigned') }}</p>
                    @endif
                </div>
            </div>

            {{-- Today Trips --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('driver.todays_schedule') }}</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($today_trips as $trip)
                                    @php
                                        $badge = [
                                            'Completed' => 'bg-green-100 text-green-800',
                                            'Ongoing'   => 'bg-blue-100 text-blue-800',
                                            'Pending'   => 'bg-yellow-100 text-yellow-800',
                                            'Cancelled' => 'bg-red-100 text-red-800',
                                        ][$trip->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            {{ \Carbon\Carbon::parse($trip->start_time)->format('g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                            {{ $trip->fromStation->name ?? 'N/A' }} -> {{ $trip->toStation->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badge }}">
                                                {{ ucfirst($trip->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('driver.no_trips_today') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
