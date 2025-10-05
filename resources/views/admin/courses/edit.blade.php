<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Course</h2>
    </x-slot>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block">Title</label>
            <input type="text" name="title" value="{{ $course->title }}" class="border rounded w-full" required>
        </div>
        <div class="mb-4">
            <label class="block">Description</label>
            <textarea name="description" class="border rounded w-full">{{ $course->description }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block">Instructor</label>
            <select name="instructor_id" class="border rounded w-full" required>
                @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}" @if($course->instructor_id == $instructor->id) selected @endif>
                        {{ $instructor->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</x-app-layout>
