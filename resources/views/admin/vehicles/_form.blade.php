{{-- Type --}}
<div class="mb-3">
    <label>{{ __('driver.type') }}</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="type[{{ $localeCode }}]" class="form-control"
                value="{{ old('type.' . $localeCode, $vehicle->getTranslation('type', $localeCode) ?? '') }}">
        </div>
    @endforeach

    {{-- <input type="text" name="type" class="form-control" value="{{ old('type', $vehicle->type ?? '') }}"> --}}
</div>

{{-- Model --}}
<div class="mb-3">
    <label>{{ __('driver.model') }}</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="model[{{ $localeCode }}]" class="form-control"
                value="{{ old('model.' . $localeCode, $vehicle->getTranslation('model', $localeCode) ?? '') }}">
        </div>
    @endforeach

    {{-- <input type="text" name="model" class="form-control" value="{{ old('model', $vehicle->model ?? '') }}"> --}}
</div>

{{-- Plate Number --}}
<div class="mb-3">
    <label>{{ __('driver.plate_number') }}</label>
    <input type="text" name="plate_number" class="form-control"
        value="{{ old('plate_number', $vehicle->plate_number ?? '') }}">
</div>

{{-- Status --}}
<div class="mb-3">
    <label>{{ __('driver.status') }}</label>
    <select name="status" class="form-control">
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}"
                {{ old('status', $vehicle->status ?? '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
