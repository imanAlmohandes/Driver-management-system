<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
مش هاي الصفحة        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Welcome Message --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600">Here is a summary of your performance.</p>
            </div>

            {{-- Chart Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Your Monthly Trips</h3>
                    {{-- Canvas for Chart.js --}}
                    <canvas id="myTripsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Script for Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('myTripsChart');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Should come from controller
                    datasets: [{
                        label: '# of Trips',
                        data: [12, 19, 3, 5], // Should come from controller
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { scales: { y: { beginAtZero: true } } }
            });
        </script>
    @endpush
</x-app-layout>
