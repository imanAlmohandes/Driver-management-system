@if ($maintenanceLog->exists)
    {{-- On Edit Page --}}
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>{{ __('driver.vehicle') }} :</label>
            <input type="text" class="form-control" value="{{ $maintenanceLog->vehicle->plate_number ?? '-' }}"
                readonly>

        </div>
        <div class="col-md-6 mb-3">
            <label>{{ __('driver.maintenance_company') }}:</label>
            <input type="text" name="company_id" class="form-control" value="{{ $maintenanceLog->company->name ?? '-' }}"
                readonly>
        </div>
    </div>
@else
    {{-- On Create Page --}}
    {{-- Vehicle --}}
    <div class="mb-3">
        <label>{{ __('driver.vehicle') }} :</label>
        {{-- <select name="vehicle_id" class="form-control">
            <option value="">-- Select Vehicle --</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}"
                    {{ old('vehicle_id', $maintenanceLog->vehicle_id ?? '') == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->type ?? '-' }} - {{ $vehicle->plate_number ?? '-' }}
                </option>
            @endforeach
        </select> --}}
        {{-- هان لو بدي اغير حالة المركبة من اندكس المركبات --}}
        {{-- إذا تم تحديد المركبة مسبقاً، اجعل الحقل للقراءة فقط --}}
        <select name="vehicle_id" class="form-control" {{ $maintenanceLog->vehicle_id ? 'disabled' : '' }}>
            <option value="">-- {{ __('driver.select_vehicle') }} --</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}"
                    {{ old('vehicle_id', $maintenanceLog->vehicle_id ?? '') == $vehicle->id ? 'selected' : '' }}>
                    {{ $vehicle->type ?? '-' }} - {{ $vehicle->plate_number ?? '-' }}
                </option>
            @endforeach
        </select>

        {{-- إذا تم تعطيل الحقل، أضف حقلاً مخفياً ليتم إرسال القيمة --}}
        @if ($maintenanceLog->vehicle_id)
            <input type="hidden" name="vehicle_id" value="{{ $maintenanceLog->vehicle_id }}">
        @endif
    </div>

    {{-- Maintenance Company --}}
    <div class="mb-3">
        <label>{{ __('driver.maintenance_company') }} :</label>
        <select name="company_id" class="form-control" required>
            <option value="">-- {{ __('driver.select_company') }} --</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}"
                    {{ old('company_id', $maintenanceLog->company_id ?? '') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
    </div>
@endif

{{-- Service Type --}}
<div class="mb-3">
    <label>{{ __('driver.service_type') }}:</label>
    @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text">{{ $localeCode }}</span>
            </div>
            <input type="text" name="service_type[{{ $localeCode }}]" class="form-control"
                value="{{ old('service_type.' . $localeCode, $maintenanceLog->getTranslation('service_type', $localeCode) ?? '') }}">
        </div>
    @endforeach
    {{-- <input type="text" name="service_type" class="form-control" value="{{ old('service_type', $maintenanceLog->service_type ?? '') }}"> --}}
</div>

{{-- Cost --}}
<div class="mb-3">
    <label>{{ __('driver.cost') }}:</label>
    <input type="number" step="0.1" name="cost" class="form-control"
        value="{{ old('cost', $maintenanceLog->cost ?? '') }}">
</div>

{{-- Service Date --}}
<div class="mb-3">
    <label>{{ __('driver.service_date') }}:</label>
    <input type="date" name="service_date" class="form-control"
        value="{{ old('service_date', optional($maintenanceLog->service_date)->format('Y-m-d') ?? '') }}"
        max="{{ date('Y-m-d') }}">
    {{-- <small class="text-muted">Cannot be in the future</small> --}}
</div>

{{-- Notes --}}

<div class="mb-3">
    <label for="mytextarea">{{ __('driver.notes') }}:</label>
    <div class="">
        @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $localeCode }}</span>
                </div>
                <textarea name="notes[{{ $localeCode }}]" class="form-control" rows="5" id="mytextarea">{{ old('notes.' . $localeCode, $maintenanceLog->getTranslation('notes', $localeCode)) }}</textarea>
            </div>
        @endforeach
        {{-- <textarea class="form-control" name="notes" rows="5" id="mytextarea">{{ old('notes', $maintenanceLog->notes ?? '') }}</textarea> --}}
    </div>
</div>



@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#vehicle-select').on('change', function() {
            var vehicleId = $(this).val();
            var $dateInput = $('#service-date');
            var $hint = $('#date-hint');

            if (vehicleId) {
                $.get('/admin/maintenance-logs/last_service/' + vehicleId)
                    .done(function(data) {
                        var lastDate = data.last_service_date || null;
                        if (lastDate) {
                            $dateInput.attr('min', lastDate);
                            $hint.text('Cannot be before last service date: ' + lastDate);
                        } else {
                            $dateInput.removeAttr('min');
                            $hint.text('Cannot be in the future');
                        }
                    })
                    .fail(function() {
                        $dateInput.removeAttr('min');
                        $hint.text('Cannot be in the future');
                    });
            } else {
                $dateInput.removeAttr('min');
                $hint.text('Cannot be in the future');
            }
        });
    </script>
@endsection
