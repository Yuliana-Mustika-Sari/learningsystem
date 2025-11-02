<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Pembayaran: {{ $course->title }}</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <p>Harga: Rp {{ number_format($course->price, 0, ',', '.') }}</p>
            <button id="pay-button" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Bayar Sekarang</button>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        // Send the result to server to update payment status immediately
                        fetch("{{ route('student.pay.notify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(result)
                        }).then(function(resp){
                            return resp.json();
                        }).then(function(json){
                            // proceed to listcourse regardless
                            window.location.href = "{{ route('student.listcourse') }}";
                        }).catch(function(){
                            window.location.href = "{{ route('student.listcourse') }}";
                        });
                    },
                    onPending: function(result) {
                        // notify server of pending state
                        fetch("{{ route('student.pay.notify') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(result)
                        }).finally(function(){
                            window.location.href = "{{ route('student.listcourse') }}";
                        });
                    },
                    onError: function(result) {
                        alert('Terjadi kesalahan pembayaran.');
                    },
                    onClose: function() {
                        alert('Kamu menutup jendela pembayaran.');
                    }
                });
        });
    </script>
</x-app-layout>
