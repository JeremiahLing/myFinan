<?php

namespace App\Services;

use Webklex\PHPIMAP\ClientManager;
use Illuminate\Support\Facades\Storage;

class EmailInvoiceService
{
    public function fetchInvoices()
    {
        $client = (new ClientManager())->make([
            'host'          => env('IMAP_HOST'),
            'port'          => env('IMAP_PORT'),
            'encryption'    => env('IMAP_ENCRYPTION'),
            'validate_cert' => env('IMAP_VALIDATE_CERT'),
            'username'      => env('IMAP_USERNAME'),
            'password'      => env('IMAP_PASSWORD'),
            'protocol'      => env('IMAP_PROTOCOL'),
        ]);

        $client->connect();

        $mailbox = $client->getFolder('INBOX');
        $messages = $mailbox->query()->unseen()->get();

        $invoices = [];

        foreach ($messages as $message) {
            $subject = $message->getSubject();
            $body = $message->getTextBody();

            foreach ($message->getAttachments() as $attachment) {
                $filePath = 'invoices/' . $attachment->getName();
                Storage::put($filePath, $attachment->getContent());
            }

            $invoiceData = $this->parseInvoice($body);

            if ($invoiceData) {
                $invoices[] = $invoiceData;
            }

            $message->markAsSeen();
        }

        return $invoices;
    }

    private function parseInvoice($body)
    {
        preg_match('/Invoice Number: (\w+)/', $body, $invoiceNumber);
        preg_match('/Total Amount: RM([\d.]+)/', $body, $totalAmount);

        if (!empty($invoiceNumber) && !empty($totalAmount)) {
            return [
                'invoice_number' => $invoiceNumber[1],
                'total_amount'   => $totalAmount[1],
                'items'          => [],
            ];
        }

        return null;
    }
}
