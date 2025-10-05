<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center justify-center">
                    <h3 class="text-2xl font-bold">Welcome back, {{ $user->name }} 👋</h3>
                    <p class="mt-2">
                            You are logged in as
                            <span class="font-semibold">{{ ucfirst($user->role->name ?? 'User') }}</span>.
                        </p>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($data['stats'] as $key => $value)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-bold capitalize justify-center text-center mb-7">{{ str_replace('_', ' ', $key) }}</h3>
                            <p class="text-2xl font-extrabold text-gray-800 justify-center text-center">{{ $value }}</p>
                        </div>

                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
