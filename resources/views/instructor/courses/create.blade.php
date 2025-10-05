<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add New Course</h2>
    </x-slot>

    <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
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
            <label class="block">Thumbnail</label>
            <input type="file" name="thumbnail" class="border rounded w-full">
        </div>
        <div>
            <label class="block">Status</label>
            <select name="status" class="border rounded w-full">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
    </form>
</x-app-layout>
