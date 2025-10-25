<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add New Course</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block">Title</label>
                    <input type="text" name="title" class="border rounded w-full" required>
                </div>
                <div>
                    <label class="block">Description</label>
                    <textarea name="description" class="border rounded w-full"></textarea>
                </div>
                <div>
                    <label class="block">Item Type</label>
                    <textarea name="item_type" class="border rounded w-full"></textarea>
                </div>
                <div>
                    <label class="block">Price</label>
                    <input type="number" name="price" class="border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Course Type</label>
                    <select name="is_premium" class="w-full border rounded p-2">
                        <option value="0" {{ isset($course) && !$course->is_premium ? 'selected' : '' }}>Free
                        </option>
                        <option value="1" {{ isset($course) && $course->is_premium ? 'selected' : '' }}>Paid
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block">Thumbnail</label>
                    <input type="file" name="thumbnail" class="border rounded w-full">
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
