<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Daily Work') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Next Trip Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">My Next Assigned Trip</h3>
                        <div class="text-gray-700">
                            @if (isset($next_trip) && $next_trip)
                                <p><strong>From:</strong> {{ $next_trip->fromStation->name ?? 'N/A' }}</p>
                                <p><strong>To:</strong> {{ $next_trip->toStation->name ?? 'N/A' }}</p>
                                <p><strong>Time:</strong>
                                    {{ \Carbon\Carbon::parse($next_trip->start_time)->format('D, M j, Y g:i A') }}</p>
                            @else
                                <p class="text-gray-500">No upcoming trips assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assigned Vehicle Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">My Assigned Vehicle</h3>
                        <div class="text-gray-700">
                            @if (isset($assigned_vehicle) && $assigned_vehicle)
                                <p><strong>Model:</strong> {{ $assigned_vehicle->model }}</p>
                                <p><strong>Plate Number:</strong> {{ $assigned_vehicle->plate_number }}</p>
                                <p><strong>Status:</strong> <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($assigned_vehicle->status) }}</span>
                                </p>
                            @else
                                <p class="text-gray-500">No vehicle assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
