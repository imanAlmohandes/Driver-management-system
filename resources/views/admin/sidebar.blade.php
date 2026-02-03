<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.index') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-car"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('driver.dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Pages Collapse Menu -->
    {{-- Drivers --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategory"
            aria-expanded="true" aria-controls="collapseCategory">
            <i class="fas fa-fw fa-users"></i>
            <span>{{ __('driver.drivers') }}</span>
        </a>
        <div id="collapseCategory" class="collapse {{ request()->routeIs('admin.drivers.*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('admin.drivers.index') ? 'active' : '' }}"
                    href="{{ route('admin.drivers.index') }}">{{ __('driver.all_drivers') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.drivers.create') ? 'active' : '' }}"
                    href="{{ route('admin.drivers.create') }}">{{ __('driver.add_new') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.drivers.invite') ? 'active' : '' }}"
                    href="{{ route('admin.drivers.invite') }}">{{ __('driver.invite') }}</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Vehicles --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVehicle"
            aria-expanded="true" aria-controls="collapseVehicle">
            <i class="fas fa-fw fa-truck"></i>
            <span>{{ __('driver.vehicles') }}</span>
        </a>
        <div id="collapseVehicle" class="collapse {{ request()->routeIs('admin.vehicles.*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                <a class="collapse-item {{ request()->routeIs('admin.vehicles.index') ? 'active' : '' }}"
                    href="{{ route('admin.vehicles.index') }}">{{ __('driver.all_vehicles') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.vehicles.create') ? 'active' : '' }}"
                    href="{{ route('admin.vehicles.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Trips --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTrip"
            aria-expanded="true" aria-controls="collapseTrip">
            <i class="fas fa-fw fa-route"></i>
            <span>{{ __('driver.trips') }}</span></a>
        <div id="collapseTrip" class="collapse {{ request()->routeIs('admin.trip.*') ? 'show' : '' }}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                <a class="collapse-item {{ request()->routeIs('admin.trips.index') ? 'active' : '' }}"
                    href="{{ route('admin.trips.index') }}">{{ __('driver.all_trips') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.trips.create') ? 'active' : '' }}"
                    href="{{ route('admin.trips.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Stations --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStations"
            aria-expanded="true" aria-controls="collapseStations">
            <i class="fas fa-fw fa-building"></i>
            <span>{{ __('driver.stations') }}</span>
        </a>
        <div id="collapseStations" class="collapse {{ request()->routeIs('admin.stations.*') ? 'show' : '' }}"
            aria-labelledby="headingStations" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('admin.stations.index') ? 'active' : '' }}"
                    href="{{ route('admin.stations.index') }}">{{ __('driver.all_stations') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.stations.create') ? 'active' : '' }}"
                    href="{{ route('admin.stations.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Fuel Logs --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFuelLog"
            aria-expanded="true" aria-controls="collapseFuelLog">
            <i class="fas fa-fw fa-gas-pump"></i>
            <span>{{ __('driver.fuelLogs') }}</span></a>
        <div id="collapseFuelLog" class="collapse {{ request()->routeIs('admin.fuel_logs.*') ? 'show' : '' }}"
            aria-labelledby="headingFuelLog" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                <a class="collapse-item {{ request()->routeIs('admin.fuel_logs.index') ? 'active' : '' }}"
                    href="{{ route('admin.fuel_logs.index') }}">{{ __('driver.all_fuel_logs') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.fuel_logs.create') ? 'active' : '' }}"
                    href="{{ route('admin.fuel_logs.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Maintenances --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaintenance"
            aria-expanded="true" aria-controls="collapseMaintenance">
            <i class="fas fa-fw fa-tools"></i>
            <span>{{ __('driver.maintenanceLogs') }}</span></a>
        <div id="collapseMaintenance"
            class="collapse {{ request()->routeIs('admin.maintenance_logs.*') ? 'show' : '' }}"
            aria-labelledby="headingMaintenance" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->routeIs('admin.maintenance_logs.index') ? 'active' : '' }}"
                    href="{{ route('admin.maintenance_logs.index') }}">{{ __('driver.all_maintenance_logs') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.maintenance_logs.create') ? 'active' : '' }}"
                    href="{{ route('admin.maintenance_logs.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    {{-- Companies Maintenance --}}
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse"
            data-target="#collapseMaintenanceCompanies" aria-expanded="true"
            aria-controls="collapseMaintenanceCompanies">
            <i class="fas fa-fw fa-lock"></i>
            <span>{{ __('driver.maintenanceCompanies') }}</span></a>
        <div id="collapseMaintenanceCompanies"
            class="collapse {{ request()->routeIs('admin.maintenance_companies.*') ? 'show' : '' }}"
            aria-labelledby="headingMaintenanceCompanies" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                <a class="collapse-item {{ request()->routeIs('admin.maintenance_companies.index') ? 'active' : '' }}"
                    href="{{ route('admin.maintenance_companies.index') }}">{{ __('driver.all_companies') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.maintenance_companies.create') ? 'active' : '' }}"
                    href="{{ route('admin.maintenance_companies.create') }}">{{ __('driver.add_new') }}</a>
            </div>
        </div>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
