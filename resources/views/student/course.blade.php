<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Available Courses</h2>
    </x-slot>

    <div class="py-6 px-12 grid grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md p-4">
                <img src="{{ asset('storage/'.$course->thumbnail) }}" class="w-full h-44 object-cover rounded-lg mb-3" alt="{{ $course->title }}" />
                <h3 class="text-lg font-bold">{{ $course->title }}</h3>
                <p class="text-gray-600">{{ $course->description }}</p>
                <p class="mt-2 text-blue-600 font-semibold">
                    Price: {{ $course->price > 0 ? 'Rp ' . number_format($course->price, 0, ',', '.') : 'Free' }}
                </p>
                @if ($course->price > 0)
                    <a href="{{ route('student.pay', $course->id) }}" class="mt-3 inline-block bg-blue-500 text-white px-3 py-2 rounded">Buy Now</a>
                @else
                    {{-- For free courses, call the same pay route (it will auto-enroll) --}}
                    <a href="{{ route('student.pay', $course->id) }}" class="mt-3 inline-block bg-green-500 text-white px-3 py-2 rounded">Enroll Free</a>
                @endif
                <div class="mt-2">
                    <a href="{{ route('courses.discussions.index', $course) }}" class="text-sm text-gray-600 hover:text-blue-600">View Discussions</a>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
