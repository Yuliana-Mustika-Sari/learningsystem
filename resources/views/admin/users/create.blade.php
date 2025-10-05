<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Add User
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-gray-700 font-medium">Name</label>
                    <input type="text" name="name"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-gray-700 font-medium">Role</label>
                    <select name="role_id"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 shadow">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

