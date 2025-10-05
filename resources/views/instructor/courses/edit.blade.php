<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Course</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">

        <div class="p-6 py-10 max-w-3xl mx-auto bg-white shadow rounded">
            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-4">
                    <label class="block font-medium">Title</label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}"
                        class="w-full border rounded px-3 py-2" required>
                    @error('title')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block font-medium">Description</label>
                    <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description', $course->description) }}</textarea>
                </div>

                <!-- Thumbnail -->
                <div class="mb-4">
                    <label class="block font-medium">Thumbnail</label>
                    @if ($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail"
                                class="w-48 rounded">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" class="w-full border rounded px-3 py-2">
                    @error('thumbnail')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('instructor.courses.index') }}"
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
