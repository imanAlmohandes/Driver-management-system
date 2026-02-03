<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800   leading-tight">
            {{ __('driver.my_dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('msg'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
                        <div>
                            <p class="font-bold">Success!</p>
                            <p class="text-sm">{{ session('msg') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900  ">
                    {{ __('driver.welcome_back', ['name' => Auth::user()->name]) }}</h1>
                <p class="text-gray-500 text-gray-400">{{ __('driver.performance_summary') }}</p>
            </div>

            @if ($licenseExpiryDays !== null && $licenseExpiryDays < 30)
                {{-- License Alert --}}
            @endif

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('driver.my_work') }}"
                    class="bg-white bg-gray-800 p-4 rounded-lg shadow-sm text-center">
                    <i class="fas fa-briefcase fa-2x text-blue-500 mb-2"></i>
                    <p class="font-semibold text-gray-700">My Work</p>
                </a>
                <a href="{{ route('driver.fuel_logs.create') }}"
                    class="bg-white bg-gray-800 p-4 rounded-lg shadow-sm text-center">
                    <i class="fas fa-gas-pump fa-2x text-green-500 mb-2"></i>
                    <p class="font-semibold text-gray-700">Fuel Log</p>
                </a>
                <a href="{{ route('driver.maintenance.create') }}"
                    class="bg-white bg-gray-800 p-4 rounded-lg shadow-sm text-center">
                    <i class="fas fa-tools fa-2x text-red-500 mb-2"></i>
                    <p class="font-semibold text-gray-700">Maintenance Log</p>
                </a>
                {{-- <a href="{{ route('profile.edit') }}" class="bg-white bg-gray-800 p-4 rounded-lg shadow-sm text-center">
                    <i class="fas fa-user-cog fa-2x text-gray-500 mb-2"></i>
                    <p class="font-semibold text-gray-700  ">My Profile</p>
                </a> --}}
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-blue-600 bg-gray-700 mr-4"><i
                            class="fas fa-route"></i></div>
                    <div>
                        <p class="text-sm text-gray-500 text-gray-400">Trips This Month</p>
                        <p class="text-2xl font-bold text-gray-900  ">{{ $tripsThisMonth }}</p>
                    </div>
                </div>
                <div class="bg-white bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-green-600 bg-gray-700 mr-4"><i
                            class="fas fa-road"></i></div>
                    <div>
                        <p class="text-sm text-gray-500 text-gray-400">Distance This Month</p>
                        <p class="text-2xl font-bold text-gray-900  ">{{ round($distanceThisMonth, 1) }} <span
                                class="text-base font-normal text-gray-500">km</span></p>
                    </div>
                </div>
                <div class="bg-white bg-gray-800 p-6 rounded-lg shadow-sm flex items-center">
                    <div
                        class="p-3 rounded-full {{ $licenseExpiryDays > 30 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} mr-4">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 text-gray-400">License Status</p>
                        <p
                            class="text-xl font-bold {{ $licenseExpiryDays > 30 ? 'text-gray-900  ' : 'text-red-600' }}">
                            @if ($licenseExpiryDays > 0)
                                Expires in {{ $licenseExpiryDays }} days
                            @else
                                Expired
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-3 bg-white bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900   mb-4">Weekly Trip Performance</h3>
                        <div class="h-80"><canvas id="myTripsChart"></canvas></div>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-white bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-gray-900   mb-4">Upcoming Trips</h3>
                        <div class="space-y-4">
                            @forelse ($upcoming_trips as $trip)
                                <div
                                    class="flex items-start p-3 rounded-lg {{ $loop->first ? 'bg-gray-100 bg-gray-700' : '' }}">
                                    <div class="mt-1 p-2 text-blue-600 text-blue-400 mr-3"><i
                                            class="fas fa-truck-moving"></i></div>
                                    <div>
                                        <p class="font-semibold text-gray-800  ">
                                            {{ $trip->fromStation->name ?? 'N/A' }} <i
                                                class="fas fa-arrow-right mx-1 text-xs"></i>
                                            {{ $trip->toStation->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-500 text-gray-400">
                                            {{ $trip->start_time->format('D, M j, g:i A') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8"><i
                                        class="fas fa-glass-cheers fa-3x text-green-400 mb-3"></i>
                                    <h4 class="font-semibold text-gray-700  ">All Clear!</h4>
                                    <p class="text-sm text-gray-500 text-gray-400">You have no upcoming trips.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white  bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900  text-gray-100 mb-4">Your Recent Fuel Logs</h3>

                    <div id="fuel-logs-table-wrapper">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200  divide-gray-700">
                                <thead class="bg-gray-50  bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Station</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Amount (L)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white  bg-gray-800 divide-y divide-gray-200 ">
                                    @forelse ($recent_fuel_logs as $log)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900   ">
                                                {{ $log->log_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900   ">
                                                {{ $log->trip->vehicle->plate_number ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->station_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900   ">
                                                {{ $log->fuel_amount }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900   ">
                                                ${{ number_format($log->fuel_cost, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">You haven't
                                                logged any fuel entries yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination links that will be targeted by AJAX --}}
                        @if ($recent_fuel_logs->hasPages())
                            <div class="mt-3">
                                {{ $recent_fuel_logs->withQueryString()->links() }}
                            </div>
                        @endif
                        {{-- <div class="mt-4">
                            {{ $recent_fuel_logs->links() }}
                        </div> --}}
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-900  text-gray-100 mb-4">Your Recent Maintenance Logs
                    </h3>
                    <div id="maintenance-logs-table-wrapper">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200  divide-gray-700">
                                <thead class="bg-gray-50  bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Vehicle</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Service Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-200    uppercase">
                                            Cost</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white  bg-gray-800 divide-y divide-gray-200  divide-gray-700">
                                    @forelse ($recent_maintenance_logs as $log)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->service_date }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                {{ $log->vehicle->plate_number ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->service_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                ${{ number_format($log->cost, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">You
                                                haven't logged any maintenance entries yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- Pagination links that will be targeted by AJAX --}}
                        @if ($recent_maintenance_logs->hasPages())
                            <div class="mt-3">
                                {{ $recent_maintenance_logs->withQueryString()->links() }}
                            </div>
                        @endif

                    </div>
                </div>

            </div>


        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
