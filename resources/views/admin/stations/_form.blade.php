{{-- لترجمة الحقول تم استخدام حزمة spatie/laravel-translatable--}}
{{-- في الموديل: أضيفي use HasTranslations; وعرّفي $translatable = ['field1', 'field2'];. --}}
{{-- في الـ View: أنشئي حقول إدخال بأسماء مثل name[en] و name[ar]. --}}
{{-- composer require spatie/laravel-translatable --}}

{{-- Station Name --}}
<div class="mb-3">
    <label for="name">{{ __('driver.station') }}</label>
    {{-- Loop through supported languages to create an input for each --}}
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="name[{{ $localeCode }}]" class="form-control"
                value="{{ old('name.' . $localeCode, $station?->getTranslation('name', $localeCode)) }}">
                {{-- الاستفهام عشان الحقل null --}}

        </div>
    @endforeach

    {{-- <input type="text" name="name" id="name" class="form-control"
        value="{{ old('name', $station->name ?? '') }}" required> --}}
</div>

{{-- City --}}
<div class="mb-3">
    <label for="city">{{ __('driver.city') }}</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="city[{{ $localeCode }}]" class="form-control"
                value="{{ old('city.' . $localeCode, $station?->getTranslation('city', $localeCode)) }}">
                {{-- الاستفهام عشان الحقل null --}}

        </div>
    @endforeach
    {{-- <input type="text" name="city" id="city" class="form-control"
        value="{{ old('city', $station->city ?? '') }}" required> --}}
</div>

{{-- Optional Status --}}
{{--
<div class="mb-3">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control">
        @php
            $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
        @endphp
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $station->status ?? '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>
--}}
