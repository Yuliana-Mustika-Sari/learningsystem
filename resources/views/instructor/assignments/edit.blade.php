<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Edit Assignment - {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-6 mx-w-3xl mx-auto">
        <form action="{{ route('instructor.assignments.update', $assignment) }}" method="POST"
            class="bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-medium">Title</label>
                <input type="text" name="title" value="{{ old('title', $assignment->title) }}"
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200">{{ old('description', $assignment->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Due Date</label>
                <input type="datetime-local" name="due_date"
                    value="{{ old('due_date', \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d\TH:i')) }}"
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Max Score</label>
                <input type="number" name="max_score" value="{{ old('max_score', $assignment->max_score) }}"
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" required>
            </div>

            <div class="flex justify-between items-center">
                <a href="{{ route('instructor.assignments.index') }}" class="text-gray-600 hover:underline">← Back</a>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Update Assignment
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
