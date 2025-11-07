<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Menunggu Pembayaran</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p>Order: <strong>{{ $payment->order_number }}</strong></p>
            @if(!empty($payment->va_number))
                <p>Virtual Account: <strong>{{ $payment->va_number }}</strong></p>
            @endif
            @if(!empty($payment->payment_url))
                <p class="mt-4"><a href="{{ $payment->payment_url }}" class="bg-blue-600 text-white px-4 py-2 rounded">Buka Halaman Pembayaran</a></p>
            @endif

            <div class="mt-4 text-sm text-gray-600">Status: <span id="status">{{ $payment->payment_status }}</span></div>

            <div class="mt-4 flex items-center space-x-3">
                @if(!empty($payment->payment_url))
                    <a href="{{ $payment->payment_url }}" class="bg-blue-600 text-white px-4 py-2 rounded">Buka Halaman Pembayaran</a>
                @endif
                <a href="{{ route('student.listcourse') }}" class="text-gray-600 hover:underline">Kembali ke Daftar Kursus</a>
            </div>
        </div>
    </div>

    <script>
        // Polling to check status every 5 seconds
        setInterval(function(){
            fetch("{{ route('student.payment.status', $payment) }}")
                .then(r => r.json())
                .then(data => {
                    document.getElementById('status').innerText = data.status;
                    if (data.status === 'completed') {
                        window.location.href = "{{ route('student.payment.success', $payment) }}";
                    }
                }).catch(()=>{});
        }, 5000);
    </script>
</x-app-layout>
