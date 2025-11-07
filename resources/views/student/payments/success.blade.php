<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Pembayaran Berhasil</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p>Terima kasih. Pembayaran untuk order <strong>{{ $payment->order_number }}</strong> telah diterima.</p>
            <div class="mt-4 flex items-center space-x-3">
                <a href="{{ route('student.listcourse') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Kembali ke Daftar Kursus</a>
                <a href="{{ route('student.listcourse') }}" class="text-gray-600 hover:underline">Lihat Kursus</a>
            </div>
        </div>
    </div>
</x-app-layout>
