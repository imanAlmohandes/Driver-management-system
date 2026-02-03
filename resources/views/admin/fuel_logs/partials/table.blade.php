    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('driver.driver') }}</th>
                <th>{{ __('driver.vehicle') }}</th>
                <th>{{ __('driver.receipt') }}</th>
                <th>{{ __('driver.station') }}</th>
                <th>{{ __('driver.fuel_amount') }}</th>
                <th>{{ __('driver.fuel_cost') }}</th>
                <th>{{ __('driver.log_date') }}</th>
                <th>{{ __('driver.created_at') }}</th>
                <th>{{ __('driver.updated_at') }}</th>
                <th>{{ __('driver.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fuelLogs as $fuelLog)
                <tr>
                    <td>{{ $fuelLog->id }}</td>
                    <td>{{ $fuelLog->driver->user->name ?? '-' }}</td>
                    <td>{{ $fuelLog->trip?->vehicle?->type ?? '-' }} - {{ $fuelLog->trip?->vehicle?->plate_number ?? '-' }}</td>
                    <td>{{ $fuelLog->receipt_number }}</td>
                    <td>{{ $fuelLog->station_name }}</td>
                    <td>{{ $fuelLog->fuel_amount }} {{ __('driver.l') }}</td>
                    <td>{{ $fuelLog->fuel_cost }} {{ __('driver.$') }}</td>
                    <td>{{ $fuelLog->log_date }}</td>
                    <td>{{ $fuelLog->created_at?->diffForHumans() }}</td>
                    <td>{{ $fuelLog->updated_at?->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.fuel_logs.show', $fuelLog->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.fuel_logs.edit', $fuelLog->id) }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form class="d-inline" action="{{ route('admin.fuel_logs.destroy', $fuelLog->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"
                                onclick="return confirm('{{ __('driver.confirm_delete') }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">{{ __('driver.no_fuel_logs_found') }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($fuelLogs->hasPages())
        <div class="mt-3">{{ $fuelLogs->withQueryString()->links() }}</div>
    @endif
