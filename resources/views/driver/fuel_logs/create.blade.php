<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
            Register New Fuel Log Data </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form action="{{ route('driver.fuel_logs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            {{-- Trip --}}
                            <div>
                                <label for="trip_id" class="block font-medium text-sm text-gray-700   ">Select
                                    Trip</label>
                                <select name="trip_id" id="trip_id"
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Select a Recent Trip --</option>
                                    @foreach ($trips as $trip)
                                        <option value="{{ $trip->id }}"
                                            {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                            {{ $trip->fromStation->name ?? 'N/A' }} ->
                                            {{ $trip->toStation->name ?? 'N/A' }}
                                            ({{ $trip->start_time->format('M d') }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('trip_id')" class="mt-2" />
                            </div>

                            {{-- Receipt Number & Station Name --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="receipt_number"
                                        class="block font-medium text-sm text-gray-700   ">Receipt Number</label>
                                    <input type="text" id="receipt_number" name="receipt_number"
                                        value="{{ old('receipt_number') }}"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <x-input-error :messages="$errors->get('receipt_number')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="station_name" class="block font-medium text-sm text-gray-700   ">Station
                                        Name</label>
                                    <input type="text" id="station_name" name="station_name"
                                        value="{{ old('station_name') }}"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <x-input-error :messages="$errors->get('station_name')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Amount, Cost, Date --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="fuel_amount" class="block font-medium text-sm text-gray-700   ">Fuel
                                        Amount (Liters)</label>
                                    <input type="number" step="0.01" id="fuel_amount" name="fuel_amount"
                                        value="{{ old('fuel_amount') }}"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <x-input-error :messages="$errors->get('fuel_amount')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="fuel_cost" class="block font-medium text-sm text-gray-700   ">Total
                                        Cost</label>
                                    <input type="number" step="0.01" id="fuel_cost" name="fuel_cost"
                                        value="{{ old('fuel_cost') }}"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <x-input-error :messages="$errors->get('fuel_cost')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="log_date"
                                        class="block font-medium text-sm text-gray-700   ">Date</label>
                                    <input type="date" id="log_date" name="log_date"
                                        value="{{ old('log_date', date('Y-m-d')) }}"
                                        class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                    <x-input-error :messages="$errors->get('log_date')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Image Upload --}}
                            <div>
                                <label for="receipt_image_path"
                                    class="block font-medium text-sm text-gray-700   ">Receipt Image (Optional)</label>
                                <input type="file" id="receipt_image_path" name="receipt_image_path"
                                    class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <x-input-error :messages="$errors->get('receipt_image_path')" class="mt-2" />
                            </div>

                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Save Fuel Log
                            </button>
                        </div>
                        <div class="flex items-center justify-end mt-6 gap-4">
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
</x-app-layout>
