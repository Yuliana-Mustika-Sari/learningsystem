<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Kursus</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                @php
                    // Cek apakah user sudah bayar atau sudah enroll
                    $paidStatuses = ['completed', 'settlement', 'capture', 'success'];
                    $hasPaid = $payments
                        ->where('course_id', $course->id)
                        ->whereIn('status', $paidStatuses)
                        ->isNotEmpty();
                    $isEnrolled = in_array($course->id, $enrollments ?? []);
                @endphp

                <div class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition">
                    @if ($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                            class="w-full h-44 object-cover">
                    @else
                        <div class="w-full h-44 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mt-2">{{ Str::limit($course->description, 100) }}</p>

                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-blue-600 font-bold text-sm">
                                @if ($course->price > 0)
                                    Rp {{ number_format($course->price, 0, ',', '.') }}
                                @else
                                    Gratis
                                @endif
                            </span>

                            {{-- Kondisi tombol --}}
                            @if ($course->price > 0)
                                @if ($hasPaid || $isEnrolled)
                                    <a href="{{ route('student.assignments.index', $course->id) }}"
                                        class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 text-sm">
                                        Mulai Belajar
                                    </a>
                                @else
                                    <a href="{{ route('student.payment.confirm', $course->id) }}"
                                        class="bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600 text-sm">
                                        Bayar Sekarang
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('student.course.show', $course->id) }}"
                                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 text-sm">
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
