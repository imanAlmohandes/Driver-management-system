{{-- Driver (User) --}}
<div class="mb-3">
    <label>{{ __('driver.driver_user') }}</label>
    <select name="user_id" class="form-control">
        <option value="">{{ __('driver.select_driver') }}</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}"
                {{ old('user_id', $driver->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
</div>

{{-- License Number --}}
<div class="mb-3">
    <label>{{ __('driver.license_number') }}</label>
    <input type="text" name="license_number" class="form-control"
        value="{{ old('license_number', $driver->license_number ?? '') }}">
</div>

{{-- License Type --}}
<div class="mb-3">
    <label>{{ __('driver.license_type') }}</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="license_type[{{ $localeCode }}]" class="form-control"
                value="{{ old('license_type.' . $localeCode, $driver->getTranslation('license_type', $localeCode) ?? '') }}">
        </div>
    @endforeach
    {{-- <input type="text" name="license_type" class="form-control" value="{{ old('license_type', $driver->license_type ?? '') }}"> --}}
</div>

{{-- Expiry Date --}}
<div class="mb-3">
    <label>{{ __('driver.license_expiry') }}</label>
    <input type="date" name="license_expiry_date" class="form-control"
        value="{{ old('license_expiry_date', $driver->license_expiry_date ?? '') }}">
</div>
{{-- Driver Image --}}
<div class="mb-3">
    <label>{{ __('driver.driver_image') }}</label>
    <input type="file" name="driver_image" class="form-control">

    @if ($driver->driver_image)
        <div class="mt-2">
            <label>{{ __('driver.current_image') }}:</label><br>
            <img src="{{ asset('uploads/drivers/' . $driver->driver_image) }}" alt="Current Driver Image"
                width="80" class="rounded">
        </div>
    @endif
</div>
