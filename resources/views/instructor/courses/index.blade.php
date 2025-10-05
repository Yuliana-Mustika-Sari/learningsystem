<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My Courses</h2>
    </x-slot>

    <div class="p-6 px-16">
        <a href="{{ route('instructor.courses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add
            Course</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            @foreach ($courses as $course)
                <div class="bg-white shadow rounded p-4">
                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://via.placeholder.com/150' }}"
                        class="w-full h-40 object-cover rounded mb-2">
                    <h3 class="font-bold">{{ $course->title }}</h3>
                    <p class="text-gray-600">{{ Str::limit($course->description, 100) }}</p>
                    <p class="mt-2 text-sm text-gray-500">Status: {{ ucfirst($course->status) }}</p>

                    <div class="flex space-x-2 mt-3">
                        <a href="{{ route('instructor.courses.edit', $course) }}"
                            class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>
                        <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST"
                            onsubmit="return confirm('Delete this course?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 bg-red-500 text-white rounded">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
