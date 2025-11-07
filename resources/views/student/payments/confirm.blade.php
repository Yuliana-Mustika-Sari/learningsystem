<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Konfirmasi Pembayaran: {{ $course->title }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Pesanan</h3>

                    <div class="border rounded-lg p-4 mb-6">
                        <div class="flex gap-4">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' ) }}" alt="{{ $course->title }}" class="w-24 h-24 object-cover rounded">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg">{{ $course->title }}</h4>
                                {{-- <p class="text-sm text-gray-600">Kode: {{ $course->code }}</p> --}}
                                {{-- <p class="text-sm text-gray-600">Stok tersedia: {{ $course->stock }}</p> --}}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Satuan:</span>
                            <span class="font-semibold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-semibold">1</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-xl font-bold text-blue-600">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('student.payment.process', $course) }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                Bayar Sekarang
                            </button>
                            <a href="{{ route('student.listcourse') }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

