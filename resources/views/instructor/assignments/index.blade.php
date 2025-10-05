<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My Assignments</h2>
    </x-slot>

    <div class="py-6 space-y-6">
        @foreach ($courses as $course)
            <div class="bg-white shadow rounded p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                    <a href="{{ route('instructor.assignments.create', $course) }}"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">+ Add Assignment</a>
                </div>

                @if ($course->assignments->count())
                    <table class="w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Title</th>
                                <th class="p-2 text-left">Description</th>
                                <th class="p-2 text-left">Due Date</th>
                                <th class="p-2 text-center">Max Score</th>
                                <th class="p-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($course->assignments as $assignment)
                                <tr class="border-t">
                                    <td class="p-2">{{ $assignment->title }}</td>
                                    <td class="p-2">{{ $assignment->description }}</td>
                                    <td class="p-2">
                                        {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}</td>
                                    <td class="p-2 text-center">{{ $assignment->max_score }}</td>
                                    <td class="p-2 text-center space-x-2">
                                        <a href="{{ route('instructor.assignments.edit', $assignment) }}"
                                            class="text-blue-500 hover:underline">Edit</a>
                                        <form action="{{ route('instructor.assignments.destroy', $assignment) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this assignment?')"
                                                class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500">No assignments yet for this course.</p>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
