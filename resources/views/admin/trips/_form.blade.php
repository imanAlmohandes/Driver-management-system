{{-- Check if we are on the EDIT page --}}
@if ($trip->exists)
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>{{ __('driver.driver') }} :</label>
            <input type="text" name="driver_id" class="form-control" value="{{ $trip->driver->user->name ?? '-' }}"
                readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label>{{ __('driver.vehicle') }} :</label>
            <input type="text" class="form-control" name="vehicle_id"
                value="{{ $trip->vehicle->model ?? '-' }} ({{ $trip->vehicle->plate_number ?? '-' }})" readonly>
        </div>
    </div>
@else
    {{-- Driver --}}
    <div class="mb-3">
        <label>{{ __('driver.driver') }} :</label>
        <select name="driver_id" class="form-control">
            <option value=""> {{ __('driver.select_driver') }} </option>
            @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}"
                    {{ old('driver_id', $trip->driver_id ?? '') == $driver->id ? 'selected' : '' }}>
                    {{ $driver->user->name }}
                </option>
            @endforeach
        </select>
    </div>
    {{-- Vehicle --}}
    <div class="mb-3">
        <label>{{ __('driver.vehicle') }} :</label>
        <select name="vehicle_id" class="form-control">
            <option value=""> {{ __('driver.select_vehicle') }} </option>

            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}"
                    {{ old('vehicle_id', $trip->vehicle_id ?? '') == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->type }} - {{ $vehicle->plate_number }}
                </option>
            @endforeach
        </select>
    </div>
@endif

{{-- From Station --}}
<div class="mb-3">
    <label>{{__('driver.from_station')}} :</label>
    <select name="from_station_id" class="form-control">
        <option value=""> {{ __('driver.select_from_station') }} </option>

        @foreach ($stations as $station)
            <option value="{{ $station->id }}"
                {{ old('from_station_id', $trip->from_station_id ?? '') == $station->id ? 'selected' : '' }}>
                {{ $station->name }}
            </option>
        @endforeach
    </select>
</div>

{{-- To Station --}}
<div class="mb-3">
    <label>{{__('driver.to_station')}} :</label>
    <select name="to_station_id" class="form-control">
        <option value=""> {{ __('driver.select_to_station') }} </option>

        @foreach ($stations as $station)
            <option value="{{ $station->id }}"
                {{ old('to_station_id', $trip->to_station_id ?? '') == $station->id ? 'selected' : '' }}>
                {{ $station->name }}
            </option>
        @endforeach
    </select>
</div>

{{-- Start Time --}}
<div class="mb-3">
    <label>{{__('driver.start_time')}} :</label>
    <input type="datetime-local" name="start_time" class="form-control"
        value="{{ old('start_time', optional($trip->start_time)->format('Y-m-d\TH:i') ?? '') }}">
</div>

{{-- End Time --}}
<div class="mb-3">
    <label>{{__('driver.end_time')}} :</label>
    <input type="datetime-local" name="end_time" class="form-control"
        value="{{ old('end_time', optional($trip->end_time)->format('Y-m-d\TH:i') ?? '') }}">
</div>

{{-- Distance --}}
<div class="mb-3">
    <label>{{__('driver.distance_km')}} :</label>
    <input type="number" step="0.01" name="distance_km" class="form-control"
        value="{{ old('distance_km', $trip->distance_km ?? '') }}">
</div>

{{-- Status --}}
<div class="mb-3">
    <label>{{ __('driver.status') }} :</label>
    <select name="status" class="form-control">

        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $trip->status ?? '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

{{-- Notes --}}
{{-- <div class="mb-3">
    <label>Notes :</label>
    <textarea name="notes" class="form-control">{{ old('notes', $trip->notes ?? '') }}</textarea>
</div> --}}
<div class="mb-3">
    <label for="notes" class="">{{ __('driver.notes') }} :</label>
    <div class="">
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <textarea name="notes[{{ $localeCode }}]" class="form-control" rows="5" id="mytextarea">{{ old('notes.' . $localeCode, $trip->getTranslation('notes', $localeCode)) }}</textarea>
        </div>
    @endforeach

        {{-- <textarea class="form-control" name="notes" rows="5" id="mytextarea">{{ $trip->notes }}</textarea> --}}
    </div>
</div>
