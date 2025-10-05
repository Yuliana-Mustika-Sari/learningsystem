<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Course Management</h2>
    </x-slot>

    <div class="py-6 px-12">
        <a href="{{ route('admin.courses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Course</a>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-md text-left border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Title</th>
                        <th class= "px-4 py-3">Description</th>
                        <th class="px-4 py-3">Instructor</th>
                        <th class="px-4 py-3">Created At</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($courses as $course)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $course->id }}</td>
                            <td class="px-4 py-3">{{ $course->title }}</td>
                            <td class="px-4 py-3">{{$course->description}}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-600">
                                    {{ $course->instructor->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $course->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center space-x-3">
                                <a href="{{ route('admin.courses.edit', $course) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete course?')"
                                        class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
