<div class="mb-3">
    <label>{{ __('driver.name') }} :</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                value="{{ old('name.' . $localeCode, $company->getTranslation('name', $localeCode) ?? '') }}">
        </div>
    @endforeach
    {{-- <input type="text" name="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required> --}}
</div>

<div class="mb-3">
    <label>{{ __('driver.phone') }} :</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone ?? '') }}">
</div>

<div class="mb-3">
    <label>{{ __('driver.address') }} :</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="address[{{ $localeCode }}]" class="form-control"
                value="{{ old('address.' . $localeCode, $company->getTranslation('address', $localeCode) ?? '') }}">
        </div>
    @endforeach
    {{-- <input type="text" name="address" class="form-control" value="{{ old('address', $company->address ?? '') }}"> --}}
</div>
