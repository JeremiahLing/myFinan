<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="bg-gray-100 dark:bg-gray-900 py-10 px-6 flex flex-col items-center animate__animated animate__fadeInUp" style="background-color: #CCAAFFaa;">
        <div class="container max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg" style="background-color: rgba(255, 255, 255, 0.5);">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Invoice #{{ $invoice->invoice_number }}</h1>

            <p><strong>Client Name:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Client Email:</strong> {{ $invoice->client_email }}</p>
            <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>

            <h2 class="text-lg font-semibold mt-4 mb-2">Items</h2>
            <table class="table-auto w-full border mb-6" style="background-color: rgba(255, 255, 255, 0.3);">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Unit Price</th>
                        <th class="border px-4 py-2">Total Unit Price</th>
                    </tr>
                </thead>
                @if($invoice->items && $invoice->items->isNotEmpty())
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td class="border px-4 py-2">{{ $item->description }}</td>
                                <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                <td class="border px-4 py-2">RM{{ number_format($item->unit_price, 2) }}</td>
                                <td class="border px-4 py-2">RM{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                @else
                    <tr>
                        <td colspan="4" class="text-center">No items found.</td>
                    </tr>
                @endif
            </table>

            <p><strong>Subtotal:</strong> RM{{ number_format($invoice->subtotal, 2) }}</p>
            <p><strong>Tax:</strong> {{ number_format($invoice->tax, 2) }}%</p>
            <p><strong>Total:</strong> RM{{ number_format($invoice->total, 2) }}</p>
        </div>

        <!-- Design Button -->
        <div class="py-6 button-container mx-auto">
            <a href="{{ route('invoice') }}" class="btn btn-primary cancel-btn shadow-lg">
                Back
            </a>
           
            <a href="{{ route('invoices.edit', $invoice->id) }}" 
            class="history-btn px-4 py-2">
                Edit Invoice
            </a>

            <form action="{{ route('invoices.preview') }}" method="POST">
                @csrf
                <input type="hidden" name="invoice_number" value="{{ $invoice->invoice_number }}">
                <input type="hidden" name="client_name" value="{{ $invoice->client_name }}">
                <input type="hidden" name="client_email" value="{{ $invoice->client_email }}">
                <input type="hidden" name="invoice_date" value="{{ $invoice->invoice_date }}">
                <input type="hidden" name="due_date" value="{{ $invoice->due_date }}">
                @foreach ($invoice->items as $index => $item)
                    <input type="hidden" name="items[{{ $index }}][description]" value="{{ $item->description }}">
                    <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}">
                    <input type="hidden" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}">
                    <input type="hidden" name="items[{{ $index }}][total_unit_price]" value="{{ $item->total_unit_price }}">
                @endforeach
                <input type="hidden" name="subtotal" value="{{ $invoice->subtotal }}">
                <input type="hidden" name="tax" value="{{ $invoice->tax }}">
                <input type="hidden" name="total" value="{{ $invoice->total }}">
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                <button type="submit" class="btn save-btn shadow-lg">Preview</button>
            </form>
        </div>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
            });
        @endif
    </script>
    <!--Footer-->
    @include('components.footer')
</x-app-layout>
