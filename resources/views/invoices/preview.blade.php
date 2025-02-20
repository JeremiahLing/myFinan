<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Preview Your Invoice') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 py-10 px-6" style="background-color: #CCAAFFaa;">
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Invoice Preview</h1>

            <div class="p-6 border rounded-lg">
                <h1 class="text-xl font-bold mb-2">Invoice: {{ $validated['invoice_number'] }}</h1>
                <p>Client: {{ $validated['client_name'] }}</p>
                <p>Email: {{ $validated['client_email'] }}</p>
                <p>Date: {{ $validated['invoice_date'] }}</p>
                <p>Due: {{ $validated['due_date'] }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Items</h2>
                <ul>
                    @foreach ($validated['items'] as $item)
                        <li>
                            {{ $item['description'] }} - {{ $item['quantity'] }} x RM{{ number_format($item['unit_price'], 2) }} = 
                            RM{{ number_format($item['total_unit_price'], 2) }}
                        </li>
                    @endforeach
                </ul>
                <hr class="my-4 mt-2">
                <p><strong>Subtotal:</strong> RM{{ number_format($validated['subtotal'], 2) }}</p>
                <p><strong>Tax:</strong> {{ number_format($validated['tax'], 2) }}%</p>
                <hr class="my-4 mt-2">
                <p><strong>Total:</strong> RM{{ number_format($validated['total'], 2) }}</p>
                <hr class="my-4 mt-2">
                <h2 class="font-semibold">Beneficial</h2>
                <p>Sender: {{ Auth::user()->name }}</p>
                <p>Email: {{ Auth::user()->email }}</p>
            </div>

            <div class="py-6 flex justify-between">
                <a href="{{ url()->previous() }}" class="btn cancel-btn shadow-lg">Back</a>
                <a href="{{ route('invoice') }}" class="btn history-btn shadow-lg">Invoice</a>

                <form method="POST" action="{{ route('invoice.send') }}">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $validated['invoice_id'] }}">
                    <input type="hidden" name="client_email" value="{{ $validated['client_email'] }}">
                    <button type="submit" class="btn save-btn shadow-lg">Send</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
