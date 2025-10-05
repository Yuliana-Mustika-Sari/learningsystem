{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Course</h2>
    </x-slot>

    <form action="{{ route('admin.courses.store') }}" method="POST" class="p-6">
        @csrf
        <div class="mb-4">
            <label class="block">Title</label>
            <input type="text" name="title" class="border rounded w-full" required>
        </div>
        <div class="mb-4">
            <label class="block">Description</label>
            <textarea name="description" class="border rounded w-full"></textarea>
        </div>
        <div class="mb-4">
            <label class="block">Instructor</label>
            <select name="instructor_id" class="border rounded w-full" required>
                @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
    </form>
</x-app-layout>

--}}


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Add User
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-gray-700 font-medium">Title</label>
                    <input type="text" name="title"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Description</label>
                    <input type="text" name="title"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Description</label>
                    <input type="text" name="title"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
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

