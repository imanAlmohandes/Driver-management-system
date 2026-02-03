{{-- We check if we are on the EDIT page by checking if the model exists in DB --}}
@if ($fuelLog->exists)

    {{-- CODE FOR EDIT PAGE  --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>{{ __('driver.driver') }} :</label>
            <input type="text" class="form-control" value="{{ $fuelLog->driver->user->name ?? '-' }}" readonly>
            <input type="hidden" name="driver_id" value="{{ $fuelLog->driver_id }}">
        </div>
        <div class="col-md-4 mb-3">
            <label>{{ __('driver.trip') }} :</label>
            @php
                $trip_info = $fuelLog->trip
                    ? ($fuelLog->trip->fromStation->name ?? '-') . ' -> ' . ($fuelLog->trip->toStation->name ?? '-')
                    : 'N/A';
            @endphp
            <input type="text" class="form-control" value="{{ $trip_info }}" readonly>
            <input type="hidden" name="trip_id" value="{{ $fuelLog->trip_id }}">
        </div>
        <div class="col-md-4 mb-3">
            <label>{{ __('driver.vehicle_used_in_trip') }} :</label>
            <input type="text" class="form-control bg-light"
                value="{{ $fuelLog->trip->vehicle->model ?? '-' }} ({{ $fuelLog->trip->vehicle->plate_number ?? '-' }})"
                readonly>
        </div>
    </div>
@else
    {{--  CODE FOR CREATE PAGE --}}
    <div class="mb-3">
        <label>{{ __('driver.select_driver') }} :</label>
        <select name="driver_id" class="form-control" id="driver-select">
            <option value="">-- {{ __('driver.select_driver') }} --</option>
            @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                    {{ $driver->user->name ?? '-' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            <label>{{ __('driver.select_trip') }} :</label>
            <select name="trip_id" class="form-control" id="trip-select">
                <option value="">-- {{ __('driver.select_driver_first') }} --</option>
            </select>
        </div>
        <div class="col-6 mb-3">
            <label>{{ __('driver.vehicle_for_selected_trip') }}</label>
            {{-- This input will be populated by our JavaScript --}}
            <input type="text" class="form-control bg-light" id="vehicle-info" readonly
                placeholder="Vehicle info will appear here...">
        </div>
    </div>


@endif

<hr>

{{--  COMMON FIELDS FOR CREATE & EDIT  --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label>{{ __('driver.receipt') }} :</label>
        <input type="text" name="receipt_number" class="form-control"
            value="{{ old('receipt_number', $fuelLog->receipt_number ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label>{{ __('driver.station') }} :</label>
        <input type="text" name="station_name" class="form-control"
            value="{{ old('station_name', $fuelLog->station_name ?? '') }}">
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label>{{ __('driver.fuel_amount_liters') }} :</label>
        <input type="number" step="0.01" name="fuel_amount" class="form-control"
            value="{{ old('fuel_amount', $fuelLog->fuel_amount ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>{{ __('driver.fuel_cost') }} :</label>
        <input type="number" step="0.01" name="fuel_cost" class="form-control"
            value="{{ old('fuel_cost', $fuelLog->fuel_cost ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label>{{ __('driver.log_date') }} :</label>
        <input type="date" name="log_date" class="form-control"
            value="{{ old('log_date', optional($fuelLog->log_date)->format('Y-m-d') ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label>{{ __('driver.receipt_image') }} :</label>
    <input type="file" name="receipt_image_path" class="form-control">
    @if ($fuelLog->receipt_image_path)
        <div class="mt-2">
            <label>{{ __('driver.current_image') }} :</label><br>
            <img src="{{ asset('uploads/fuelLogs/' . $fuelLog->receipt_image_path) }}" alt="" width="100"
                alt="Current Receipt Image" class="rounded">
    @endif
</div>



@section('scripts')
    {{-- This AJAX script will only be included if we are on the CREATE page --}}
    @if (!$fuelLog->exists)
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                var $driverSelect = $('#driver-select');
                var $tripSelect = $('#trip-select');
                var $vehicleInfo = $('#vehicle-info');

                $driverSelect.on('change', function() {
                    var driverId = $(this).val();
                    $tripSelect.html('<option value="">Loading...</option>');
                    $vehicleInfo.val('');

                    if (!driverId) {
                        $tripSelect.html('<option value="">-- Select a driver first --</option>');
                        return;
                    }

                    // Make sure the route matches your web.php file
                    $.get(`/admin/fuel-logs/trips/${driverId}`)
                        .done(function(trips) {
                            console.log('Trips Received:', trips); // For debugging

                            $tripSelect.empty().append('<option value="">-- Select Trip --</option>');

                            if (!trips || trips.length === 0) {
                                $tripSelect.html('<option value="">-- No trips found --</option>');
                                return;
                            }

                            $.each(trips, function(index, trip) {
                                var from = trip.from_station ? trip.from_station.name : 'N/A';
                                var to = trip.to_station ? trip.to_station.name : 'N/A';

                                // Check if trip.vehicle exists and has data
                                var vehicleText = trip.vehicle ?
                                    `${trip.vehicle.model} (${trip.vehicle.plate_number})` :
                                    '!! No Vehicle Assigned !!';

                                var option = $(
                                    `<option value="${trip.id}" data-vehicle-info="${vehicleText}">${from} -> ${to}</option>`
                                );
                                $tripSelect.append(option);
                            });
                        });
                });

                $tripSelect.on('change', function() {
                    // Get vehicle info from the selected trip's data attribute
                    var vehicleInfo = $(this).find('option:selected').data('vehicle-info');
                    $vehicleInfo.val(vehicleInfo || ''); // Set the text input value
                });
            });
        </script>
    @endif
@endsection
