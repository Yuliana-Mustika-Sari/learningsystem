<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tugas - {{ $course->title }}</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white shadow rounded-lg p-6">
            @if ($assignments->isEmpty())
                <p class="text-gray-600">Belum ada tugas untuk kursus ini.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($assignments as $assignment)
                        <li class="border p-4 rounded">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold">{{ $assignment->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ Str::limit($assignment->description, 200) }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Due: {{ $assignment->due_date }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <a href="#"
                                        class="inline-block bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 font-semibold shadow">
                                        Lihat
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-8 text-left">
            <a href="{{ route('student.listcourse') }}"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg shadow transition">
                ← Kembali ke Daftar Kursus
            </a>
        </div>

    </div>
</x-app-layout>
