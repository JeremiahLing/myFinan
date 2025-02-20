<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $latestInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextNumber = $latestInvoice ? intval(substr($latestInvoice->invoice_number, 4)) + 1 : 1;
            $invoice->invoice_number = 'INV ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
    
    protected $with = ['items'];

    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}