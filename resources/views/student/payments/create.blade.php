<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Payment for {{ $course->title }}</h2>
    </x-slot>

    <div class="py-6 px-12">
        <div class="bg-white rounded p-6 max-w-xl mx-auto">
            <p class="mb-4">Price: <strong>Rp {{ number_format($course->price, 0, ',', '.') }}</strong></p>

            <form action="{{ route('student.payment.store', $course->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Payment method</label>
                    <select name="payment_method" class="w-full border rounded p-2">
                        <option value="credit_card">Online Payment</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('student.courses') }}" class="px-4 py-2 bg-gray-200 rounded">Back</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Proceed to Pay</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
