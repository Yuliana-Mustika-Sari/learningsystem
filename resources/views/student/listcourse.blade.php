<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Kursus</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                        class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mt-2">{{ Str::limit($course->description, 100) }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-blue-600 font-bold">Rp
                                {{ number_format($course->price, 0, ',', '.') }}</span>
                            @if ($course->is_premium)
                                <a href="{{ route('student.course.show', $course->id) }}"
                                    class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                                    Lihat Detail
                                </a>
                            @else
                                <a href="{{ route('student.course.show', $course->id) }}"
                                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                                    Mulai Belajar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
