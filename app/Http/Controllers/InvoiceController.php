<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Expense;
use App\Mail\SendInvoice;
use App\Services\EmailInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function fetchInvoices(EmailInvoiceService $emailInvoiceService)
    {
        $invoices = $emailInvoiceService->fetchInvoices();

        foreach ($invoices as $invoice) {
            Expense::create([
                'item_name' => $invoice['invoice_number'],
                'amount'    => $invoice['total_amount'],
                'category'  => 'Invoice',
                'date'      => now(),
            ]);
        }

        return redirect()->route('expense.history')->with('success', 'Invoices fetched and added to expenses!');
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', auth()->id())->paginate(10);
        return view('invoices.invoice', compact('invoices'));
    }

    public function create()
    {
        $nextId = $this->generateInvoiceNumber(); // Use the custom generator

        return view('invoices.create', compact('nextId'));
    }

    // Generate the next invoice number
    private function generateInvoiceNumber()
    {
        $latestInvoice = Invoice::orderBy('id', 'desc')->first();
        $nextNumber = $latestInvoice ? intval(preg_replace('/[^0-9]/', '', $latestInvoice->invoice_number)) + 1 : 1;
        return 'INV ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Store a new invoice
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_unit_price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        $invoiceNumber = $this->generateInvoiceNumber();

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $validated['subtotal'],
            'tax' => $validated['tax'],
            'total' => $validated['total'],
            'user_id' => auth()->id(),
        ]);
        
        // Save items for the invoice and calculate total for each item
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_unit_price' => $item['quantity'] * $item['unit_price'],
                'invoice_id' => $invoice->id,
            ]);
        }

        return redirect()->route('invoice')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        // Retrieve the invoice by its ID
        $invoice = Invoice::findOrFail($id);

        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $invoice->load('items');

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('invoices.create', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Update the invoice details
        $invoice->update([
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'subtotal' => $request->subtotal,
            'tax' => $request->tax,
            'total' => $request->total,
        ]);

        // Update items
        $invoice->items()->delete();
        foreach ($request->items as $item) {
            $invoice->items()->create($item);
        }

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice updated successfully!');
    }

    private function processInvoiceData($request)
    {
        $validated = $request->validate([
            'invoice_number' => 'string|required',
            'client_name' => 'string|required',
            'client_email' => 'email|required',
            'invoice_date' => 'date|required',
            'due_date' => 'date|required',
            'items' => 'array|required',
            'items.*.description' => 'string|required',
            'items.*.quantity' => 'integer|min:1|required',
            'items.*.unit_price' => 'numeric|min:0|required',
            'items.*.total_unit_price' => 'numeric|min:0|required',
            'subtotal' => 'numeric|required',
            'tax' => 'numeric|required',
            'total' => 'numeric|required',
            'invoice_id' => 'integer|required',
        ]);

        return $validated;
    }

    public function preview(Request $request)
    {
        $validated = $this->processInvoiceData($request);

        $user = auth()->user();

        return view('invoices.preview', compact('validated'));
    }

    public function sendInvoice(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'client_email' => 'required|email',
        ]);

        $invoice = Invoice::with('items')->findOrFail($validated['invoice_id']);

        // Prepare data for the email
        $emailData = [
            'invoice_number' => $invoice->invoice_number,
            'client_name' => $invoice->client_name,
            'client_email' => $invoice->client_email,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'items' => $invoice->items->map(function ($item) {
                return [
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_unit_price' => $item->total_unit_price,
                ];
            })->toArray(),
            'subtotal' => $invoice->subtotal,
            'tax' => $invoice->tax,
            'total' => $invoice->total,
            'sender_name' => auth()->user()->name,
            'sender_email' => auth()->user()->email,
        ];

        Mail::to($validated['client_email'])->send(new SendInvoice($emailData));

        return redirect()->route('invoice')->with('success', 'Invoice sent successfully!');
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'Invalid data provided.']);
        }

        try {
            Invoice::whereIn('id', $ids)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}