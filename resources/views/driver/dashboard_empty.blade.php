<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Account Pending
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white  bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    
                    <div class="py-10">
                        <i class="fas fa-user-cog fa-4x text-yellow-400 mb-4"></i>
                        
                        <h3 class="text-2xl font-bold text-gray-900 ">
                            Your Driver Profile is Not Fully Configured
                        </h3>
                        
                        <p class="mt-2 text-gray-600  text-gray-400">
                            Your user account is active, but your specific driver details (like license information) have not been set up yet.
                            <br>
                            Please contact the system administrator to complete your profile.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> 
    @endpush
</x-app-layout>
