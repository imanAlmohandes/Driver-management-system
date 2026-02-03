{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  text-gray-200 leading-tight">
            Report New Maintenance Issue
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form action="{{ route('driver.maintenance.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="vehicle_id" class="block font-medium text-sm text-gray-700   ">My Assigned Vehicle</label>
                                <select name="vehicle_id" id="vehicle_id" class="block mt-1 w-full border-gray-300  border-gray-700  bg-gray-900    rounded-md shadow-sm">
                                    @forelse ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}">{{ $vehicle->model }} ({{ $vehicle->plate_number }})</option>
                                    @empty
                                        <option value="">No vehicle assigned to you.</option>
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('vehicle_id')" class="mt-2" />
                            </div>

                            <div>
                                <label for="service_type" class="block font-medium text-sm text-gray-700   ">Issue Type</label>
                                <input type="text" name="service_type" id="service_type" value="{{ old('service_type') }}" class="block mt-1 w-full border-gray-300  border-gray-700  bg-gray-900    rounded-md shadow-sm" placeholder="e.g., Engine Noise, Flat Tire">
                                <x-input-error :messages="$errors->get('service_type')" class="mt-2" />
                            </div>

                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700   ">Describe the Issue</label>
                                <textarea name="notes" id="notes" rows="4" class="block mt-1 w-full border-gray-300  border-gray-700  bg-gray-900    rounded-md shadow-sm">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                Report Issue to Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight ">
            Registering New Maintenance Log Data </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form action="{{ route('driver.maintenance.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            {{-- Vehicle Selection --}}
                            <div>
                                <label for="vehicle_id" class="block font-medium text-sm text-gray-700  ">Vehicle</label>
                                {{-- We add a red border if there is an error for this specific field --}}
                                <select name="vehicle_id" id="vehicle_id"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm @error('vehicle_id') border-red-500 @enderror">
                                    <option value="">-- Select Assigned Vehicle --</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->model }} ({{ $vehicle->plate_number }})
                                        </option>
                                    @endforeach
                                </select>
                                {{-- This displays the error message specifically for 'vehicle_id' --}}
                                @error('vehicle_id')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>



                            {{-- Company Selection --}}
                            <div>
                                <label for="company_id" class="block font-medium text-sm text-gray-700">Maintenance
                                    Company</label>
                                <select name="company_id" id="company-select"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Select from list --</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                    <option value="other">-- Other (New Company) --</option>
                                </select>
                            </div>

                            {{-- New Company Name (Initially hidden) --}}
                            <div id="new-company-wrapper" style="display: none;">
                                <label for="new_company_name" class="block font-medium text-sm text-gray-700">New
                                    Company Name</label>
                                <input type="text" name="new_company_name" id="new_company_name"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            </div>

                            {{-- Issue Details --}}
                            <div>
                                <label for="service_type" class="block font-medium text-sm text-gray-700">Issue
                                    Type</label>
                                <input type="text" name="service_type" id="service_type"
                                    value="{{ old('service_type') }}"
                                    class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="e.g., Engine Noise, Flat Tire">
                            </div>
                            <div>
                                <label for="notes" class="block font-medium text-sm text-gray-700">Describe the
                                    Issue</label>
                                <textarea name="notes" id="notes" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Report Issue to Admin
                            </button>

                        </div>
                        <div class="flex items-center justify-end mt-6 gap-4 ">
                            <a href="{{ route('driver.dashboard') }}"
                                class="text-sm text-white bg-red-600 border border-transparent px-4 py-2">
                                Back to Dashboard
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('company-select').addEventListener('change', function() {
                var newCompanyWrapper = document.getElementById('new-company-wrapper');
                if (this.value === 'other') {
                    newCompanyWrapper.style.display = 'block';
                } else {
                    newCompanyWrapper.style.display = 'none';
                }
            });
        </script>
    @endpush
</x-app-layout>
