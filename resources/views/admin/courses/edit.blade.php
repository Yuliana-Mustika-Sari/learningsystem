<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Add Course
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <!-- Name -->
                <div>
                    <label class="block text-gray-700 font-medium">Title</label>
                    <input type="text" name="title" value="{{ $course->title }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Description</label>
                    <input type="text" name="title" value="{{ $course->description }}"
                        class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                        required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Instructor</label>
                    <select name="instructor_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200">
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if ($course->instructor_id == $instructor->id) selected
                            @endif>{{ $instructor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}"
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

