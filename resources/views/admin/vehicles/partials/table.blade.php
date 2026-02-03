    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('driver.type') }}</th>
                <th>{{ __('driver.model') }}</th>
                <th>{{ __('driver.plate_number') }}</th>
                <th>{{ __('driver.status') }}</th>
                <th>{{ __('driver.created_at') }}</th>
                <th>{{ __('driver.updated_at') }}</th>
                <th>{{ __('driver.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->type }}</td>
                    <td>{{ $vehicle->model }}</td>
                    <td>{{ $vehicle->plate_number }}</td>
                    {{-- <td>
                        @if ($vehicle->status == 'Available')
                            <span
                                class="badge badge-success">{{ App\Models\Vehicle::STATUSES[$vehicle->status] }}</span>
                        @elseif ($vehicle->status == 'InMaintenance')
                            <span class="badge badge-danger">{{ App\Models\Vehicle::STATUSES[$vehicle->status] }}</span>
                        @else
                            <span
                                class="badge badge-secondary">{{ App\Models\Vehicle::STATUSES[$vehicle->status] ?? $vehicle->status }}</span>
                        @endif
                    </td> --}}
                    <td>
                        @php
                            $status = strtolower($vehicle->status);
                            $badgeClass =
                                [
                                    'available' => 'success',
                                    'unavailable' => 'secondary',
                                    'inmaintenance' => 'danger',
                                ][$status] ?? 'dark';
                        @endphp

                        <span class="badge badge-{{ $badgeClass }}">
                            {{ $vehicle->status_label }}
                        </span>
                    </td>

                    <td>{{ $vehicle->created_at?->diffForHumans() }}</td>
                    <td>{{ $vehicle->updated_at?->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a class="btn btn-sm btn-info" href="{{ route('admin.vehicles.edit', $vehicle->id) }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form class="delete-form d-inline" action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger" data-confirm data-action="delete"
                                data-item="{{ __('driver.vehicle') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        {{--  القائمة المنسدلة الجديدة لتغيير الحالة  --}}
                        <div class="btn-group d-inline">
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-sync-alt"></i>
                            </button>

                            <div class="dropdown-menu">
                                @foreach (App\Models\Vehicle::STATUSES as $statusKey => $statusValue)
                                    @if ($vehicle->status == $statusKey)
                                        @continue
                                    @endif

                                    {{-- إذا كان التغيير إلى InMaintenance نروح لإنشاء سجل صيانة --}}
                                    @if ($statusKey == 'InMaintenance')
                                        <a class="dropdown-item"
                                            href="{{ route('admin.maintenance_logs.create', ['vehicle_id' => $vehicle->id]) }}">
                                            {{ __('driver.status_' . strtolower($statusKey)) }}
                                        </a>
                                    @else
                                        <form action="{{ route('admin.vehicles.change_status', $vehicle->id) }}"
                                            method="POST" class="px-2">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $statusKey }}">
                                            <button class="dropdown-item p-1" type="submit">
                                                {{ __('driver.status_' . strtolower($statusKey)) }}
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        {{ __('driver.no_vehicles_found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($vehicles->hasPages())
        <div class="mt-3">
            {{ $vehicles->withQueryString()->links() }}
        </div>
    @endif
