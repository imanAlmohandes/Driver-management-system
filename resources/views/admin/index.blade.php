@extends('admin.master')

{{-- @section('title', 'Dashboard | ' . config('app.name')) --}}
@section('title', __('driver.dashboard') . ' | ' . config('app.name'))

@section('styles')
    <style>
        .card:hover {
            transform: scale(1.02);
            transition: .2s;
        }
    </style>
    @if (app()->currentLocale() == 'ar')
        <style>
            .mr-3 {
                margin-left: 1rem;
                margin-right: 0 !important
            }
        </style>
    @endif
@endsection

@section('content')

    <h1 class="h3 mb-4 text-gray-800">{{ __('driver.dashboard') }}</h1>
    {{-- <div class="h6 mb-0 font-weight-bold text-gray-800">Generate</div> --}}


    {{-- ===== Alerts ===== --}}
    <div class="row mb-4">
        @if ($expiringLicenses > 0)
            <div class="col-md-6">
                <div class="alert alert-warning shadow-sm">
                    {{-- Using trans_choice for pluralization --}}
                    {{-- <strong>{{ $expiringLicenses }}</strong> drivers have licenses expiring within 30 days --}}
                    {{ trans_choice('driver.expiring_licenses', $expiringLicenses, ['count' => $expiringLicenses]) }}
                </div>
            </div>
        @endif

        @if ($vehiclesInMaintenance > 0)
            <div class="col-md-6">
                <div class="alert alert-danger shadow-sm">
                    {{-- <strong>{{ $vehiclesInMaintenance }}</strong> vehicles currently in maintenance --}}
                    {{-- trans_choice(...): هذه هي الدالة المستخدمة للتعامل مع التعدد. نمرر لها مفتاح الترجمة، العدد ($expiringLicenses)، ومصفوفة تحتوي على قيمة :count. --}}
                    {{ trans_choice('driver.vehicles_in_maintenance', $vehiclesInMaintenance, ['count' => $vehiclesInMaintenance]) }}

                </div>
            </div>
        @endif
    </div>

    {{-- ===== Statistics Cards ===== --}}
    <div class="row">

        @php
            $cards = [
                [
                    'title' => __('driver.drivers'),
                    'count' => $driversCount,
                    'icon' => 'fa-id-card',
                    'color' => 'primary',
                    'route' => 'admin.drivers.index',
                ],
                [
                    'title' => __('driver.vehicles'),
                    'count' => $vehiclesCount,
                    'icon' => 'fa-car',
                    'color' => 'success',
                    'route' => 'admin.vehicles.index',
                ],
                [
                    'title' => __('driver.trips'),
                    'count' => $tripsCount,
                    'icon' => 'fa-route',
                    'color' => 'info',
                    'route' => 'admin.trips.index',
                ],
                [
                    'title' => __('driver.stations'),
                    'count' => $stationCount,
                    'icon' => 'fa-charging-station',
                    'color' => 'dark',
                    'route' => 'admin.stations.index',
                ],
                [
                    'title' => __('driver.maintenance'),
                    'count' => $maintenanceCount,
                    'icon' => 'fa-tools',
                    'color' => 'warning',
                    'route' => 'admin.maintenance_logs.index',
                ],
                [
                    'title' => __('driver.fuelLogs'),
                    'count' => $fuelLogsCount,
                    'icon' => 'fa-gas-pump',
                    'color' => 'danger',
                    'route' => 'admin.fuel_logs.index',
                ],
                [
                    'title' => __('driver.maintenanceCompanies'),
                    'count' => $companiesCount,
                    'icon' => 'fa-building',
                    'color' => 'secondary',
                    'route' => 'admin.maintenance_companies.index',
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route($card['route']) }}" class="text-decoration-none">

                    <div class="card border-bottom-{{ $card['color'] }} shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <div class="font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                        {{ $card['title'] }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $card['count'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas {{ $card['icon'] }} fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach

    </div>


    <div class="row">
        {{-- ===== Latest Activities ===== --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header font-weight-bold text-dark ">
                    {{ __('driver.latest_activities') }}
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse ($latestActivities as $activity)
                            <li class="list-group-item d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                </div>

                                <div class="flex-fill">
                                    <a href="{{ $activity['route'] }}" class="font-weight-bold text-dark">
                                        {{ $activity['text'] }}
                                    </a>
                                    <div class="small text-muted">
                                        {{ $activity['time']->diffForHumans() }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">
                                {{ __('driver.no_recent_activities') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>



        {{-- ===== Vehicles & Trips Status ===== --}}
        <div class="col-lg-6 mb-4">
            {{-- شوفي هذا --}}
            {{-- Vehicles Status --}}
            <div class="card shadow mb-2">
                <div class="card-header font-weight-bold text-success">
                    {{ __('driver.vehicles_status') }}
                </div>
                <div class="card-body">
                    @foreach ($vehiclesStatus as $statusKey => $count)
                        <div>
                            <div class="list-group-item d-flex justify-content-between cursor-pointer vehicle-status"
                                data-status="{{ $statusKey }}">
                                <span>{{ __('driver.status_' . strtolower($statusKey)) }}</span>
                                <span
                                    class="badge badge-{{ $statusKey == 'Available' ? 'success' : ($statusKey == 'UnAvailable' ? 'secondary' : 'danger') }}">
                                    {{ $count ?? 0 }}
                                </span>
                            </div>

                            {{-- Hidden table --}}
                            <div class="table-responsive mt-2" id="vehicles-table-{{ $statusKey }}"
                                style="display: none;">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Model</th>
                                            <th>Plate Number</th>
                                        </tr>
                                    </thead>
                                    <tbody class="vehicles-body-{{ $statusKey }}"></tbody>
                                </table>
                                {{-- {{ $vehiclesStatus->links() }} --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Trips Status --}}
            <div class="card shadow">
                <div class="card-header font-weight-bold text-info">
                    {{ __('driver.trips_status') }}
                </div>
                <div class="card-body">
                    @foreach ($tripsStatus as $statusKey => $count)
                        <div>
                            <div class="list-group-item d-flex justify-content-between cursor-pointer trip-status"
                                data-status="{{ $statusKey }}">
                                <span>{{ __('driver.status_' . strtolower($statusKey)) }}</span>
                                {{-- <span>{{ ucfirst($statusKey) }}</span> --}}
                                <span
                                    class="badge badge-{{ $statusKey == 'Pending' ? 'warning' : ($statusKey == 'Ongoing' ? 'primary' : ($statusKey == 'Completed' ? 'success' : 'danger')) }}">{{ $count ?? 0 }}</span>
                            </div>

                            {{-- Hidden table --}}

                            <div class="table-responsive mt-2" id="trips-table-{{ $statusKey }}" style="display: none;">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Vehicle</th>
                                            <th>Driver</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Start</th>
                                            <th>End</th>
                                        </tr>
                                    </thead>
                                    <tbody class="trips-body-{{ $statusKey }}"></tbody>
                                </table>
                                {{-- {{ $tripsStatus->links() }} --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
        {{-- شوفي هذا --}}
        <!-- Vehicles / Trips Modal -->
        <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Table will be injected here -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="statusModalTable">
                                <thead id="statusModalHead"></thead>
                                <tbody id="statusModalBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== Latest Data ===== --}}
    <div class="row">

        {{-- Latest Drivers --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header font-weight-bold text-primary">
                    {{ __('driver.latest_drivers') }}
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('driver.name') }}</th>
                                <th>{{ __('driver.license') }}</th>
                                <th>{{ __('driver.expiry') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestDrivers as $driver)
                                <tr>
                                    <td>{{ $driver->user->name ?? '-' }}</td>
                                    <td>{{ $driver->license_number }}</td>
                                    <td>{{ $driver->license_expiry_date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('driver.no_drivers_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Latest Maintenances --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header font-weight-bold text-warning">
                    {{ __('driver.latest_maintenances') }}
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('driver.vehicle') }}</th>
                                <th>{{ __('driver.company') }}</th>
                                <th>{{ __('driver.cost') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestMaintenances as $log)
                                <tr>
                                    <td>{{ $log->vehicle->plate_number ?? '-' }}</td>
                                    <td>{{ $log->company->name ?? '-' }}</td>
                                    <td>{{ $log->cost }} JD</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('driver.no_maintenance_records') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        const statusLabels = {
            Available: @json(__('driver.status_available')),
            UnAvailable: @json(__('driver.status_unavailable')),
            InMaintenance: @json(__('driver.status_inmaintenance')),
            Pending: @json(__('driver.status_pending')),
            Ongoing: @json(__('driver.status_ongoing')),
            Completed: @json(__('driver.status_completed')),
            Cancelled: @json(__('driver.status_cancelled')),
        };
        document.addEventListener('DOMContentLoaded', function() {

            // ===== Vehicles =====
            document.querySelectorAll('.vehicle-status').forEach(item => {
                item.addEventListener('click', function() {
                    const status = this.dataset.status;

                    fetch(`{{ url(app()->getLocale() . '/admin/vehicles-by-status') }}/${status}`)
                        .then(res => res.json())
                        .then(data => {

                            const head = `
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.type') ?? 'Type' }}</th>
                                <th>{{ __('driver.model') ?? 'Model' }}</th>
                                <th>{{ __('driver.plate_number') ?? 'Plate Number' }}</th>
                            </tr>`;

                            const body = data.map(v => `
                            <tr>
                                <td>${v.id ?? '-'}</td>
                                <td>${v.type ?? '-'}</td>
                                <td>${v.model ?? '-'}</td>
                                <td>${v.plate_number ?? '-'}</td>
                            </tr>
                        `).join('');

                            // عنوان المودال مترجم
                            document.getElementById('statusModalLabel').innerText =
                                `{{ __('driver.vehicles_status') }} - ${statusLabels[status] ?? status}`;

                            document.getElementById('statusModalHead').innerHTML = head;
                            document.getElementById('statusModalBody').innerHTML = body;

                            $('#statusModal').modal('show');
                        });
                });
            });

            // ===== Trips =====
            document.querySelectorAll('.trip-status').forEach(item => {
                item.addEventListener('click', function() {
                    const status = this.dataset.status;

                    fetch(`{{ url(app()->getLocale() . '/admin/trips-by-status') }}/${status}`)
                        .then(res => res.json())
                        .then(data => {

                            const head = `
                            <tr>
                                <th>#</th>
                                <th>{{ __('driver.vehicle') ?? 'Vehicle' }}</th>
                                <th>{{ __('driver.driver') ?? 'Driver' }}</th>
                                <th>{{ __('driver.from_station') ?? 'From' }}</th>
                                <th>{{ __('driver.to_station') ?? 'To' }}</th>
                                <th>{{ __('driver.start_time') ?? 'Start' }}</th>
                                <th>{{ __('driver.end_time') ?? 'End' }}</th>
                            </tr>`;

                            const body = data.map(t => `
                            <tr>
                                <td>${t.id ?? '-'}</td>
                                <td>${t.vehicle ?? '-'}</td>
                                <td>${t.driver ?? '-'}</td>
                                <td>${t.from ?? '-'}</td>
                                <td>${t.to ?? '-'}</td>
                                <td>${t.start_time ?? '-'}</td>
                                <td>${t.end_time ?? '-'}</td>
                            </tr>
                        `).join('');

                            // عنوان المودال مترجم
                            document.getElementById('statusModalLabel').innerText =
                                `{{ __('driver.trips_status') }} - ${statusLabels[status] ?? status}`;

                            document.getElementById('statusModalHead').innerHTML = head;
                            document.getElementById('statusModalBody').innerHTML = body;

                            $('#statusModal').modal('show');
                        });
                });
            });

        });
    </script>


@endsection
