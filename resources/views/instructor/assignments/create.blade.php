<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Assignment - {{ $course->title }}</h2>
    </x-slot>

    <div class="py-6">
        <form action="{{ route('instructor.assignments.store') }}" method="POST"
            class="bg-white p-6 rounded shadow">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="mb-4">
                <label class="block mb-1 font-medium">Title</label>
                <input type="text" name="title" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border-gray-300 rounded p-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Due Date</label>
                <input type="datetime-local" name="due_date" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Max Score</label>
                <input type="number" name="max_score" value="100" class="w-full border-gray-300 rounded p-2">
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Save Assignment
            </button>
        </form>
    </div>
</x-app-layout>
