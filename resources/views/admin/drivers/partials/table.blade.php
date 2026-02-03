    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('driver.image') }}</th>
                <th>{{ __('driver.name') }}</th>
                <th>{{ __('driver.status') }}</th>
                <th>{{ __('driver.license_number') }}</th>
                <th>{{ __('driver.license_type') }}</th>
                <th>{{ __('driver.license_expiry') }}</th>
                <th>{{ __('driver.created_at') }}</th>
                <th>{{ __('driver.updated_at') }}</th>
                <th>{{ __('driver.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($drivers as $driver)
                <tr>
                    <td>{{ $driver->id }}</td>
                    <td>
                        @if ($driver->driver_image)
                            <img src="{{ asset('uploads/drivers/' . $driver->driver_image) }}" alt=""
                                width="60">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->user->name) }}&background=random"
                                alt="" width="50" class="rounded-circle">
                        @endif
                    </td>
                    <td>{{ $driver->user->name }}</td>
                    <td>
                        @if ($driver->user->is_active)
                            <span class="badge badge-success">{{ __('driver.active') }}</span>
                        @else
                            <span class="badge badge-danger">{{ __('driver.inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ $driver->license_number }}</td>
                    <td>{{ $driver->license_type }}</td>
                    <td>{{ $driver->license_expiry_date }}</td>
                    <td>{{ $driver->created_at ? $driver->created_at->diffForHumans() : '' }}</td>
                    <td>{{ $driver->updated_at ? $driver->created_at->diffForHumans() : '' }}</td>
                    <td>
                        <a href="{{ route('admin.drivers.show', $driver->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a class="btn btn-sm btn-info" href="{{ route('admin.drivers.edit', $driver->id) }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form class="delete-form d-inline" action="{{ route('admin.drivers.destroy', $driver->id) }}"
                            method="post">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-sm btn-danger" data-confirm data-action="delete"
                                data-item="{{ __('driver.driver') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        <form class="d-inline" action="{{ route('admin.drivers.toggle_status', $driver->user->id) }}"
                            method="post">
                            @csrf
                            @if ($driver->user->is_active)
                                <button class="btn btn-sm btn-warning"
                                    onclick="return confirm('{{ __('driver.confirm_deactivate') }}')">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            @else
                                <button class="btn btn-sm btn-primary"
                                    onclick="return confirm('{{ __('driver.confirm_activate') }}')">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">{{ __('driver.no_drivers_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($drivers->hasPages())
        <div class="mt-3">
            {{-- Use withQueryString to keep filter parameters in pagination links --}}
            {{ $drivers->withQueryString()->links() }}
        </div>
    @endif
