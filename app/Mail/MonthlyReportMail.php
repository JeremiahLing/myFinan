<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $month;
    public $transactions;
    public $totalIncome;
    public $totalExpense;
    public $totalBalance;

    /**
     * Create a new message instance.
     */
    public function __construct($month, $transactions, $totalIncome, $totalExpense, $totalBalance)
    {
        $this->month = $month;
        $this->transactions = $transactions;
        $this->totalIncome = $totalIncome;
        $this->totalExpense = $totalExpense;
        $this->totalBalance = $totalBalance;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Monthly Financial Report - {$this->month}")
            ->view('emails.monthly-report')
            ->with([
                'month' => $this->month,
                'transactions' => $this->transactions,
                'totalIncome' => $this->totalIncome,
                'totalExpense' => $this->totalExpense,
                'totalBalance' => $this->totalBalance,
            ]);
    }
}
