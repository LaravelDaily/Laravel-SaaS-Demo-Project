<?php

namespace App\Services;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicesService {

    public function generateInvoice($payment) {
        $customer = new Buyer([
            'name'          => $payment->user->name,
            'custom_fields' => [
                'email' => $payment->user->email,
            ],
        ]);

        $item = (new InvoiceItem())->title('Subscription fee')->pricePerUnit(number_format($payment->total / 100, 2));

        $invoice = Invoice::make()
            ->buyer($customer)
            ->filename('invoices/' . $payment->id)
            ->addItem($item);

        $invoice->save();
    }

}