<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enroll Course') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('student.courses.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="course" class="block text-sm font-medium text-gray-700">Select Course</label>
                        <select name="course_id" id="course" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Enroll
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
