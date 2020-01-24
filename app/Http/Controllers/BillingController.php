<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Plan;

class BillingController extends Controller
{
    public function index()
    {
        $monthlyPlans = Plan::where('billing_period', 'monthly')->get();
        $yearlyPlans = Plan::where('billing_period', 'yearly')->get();
        $currentPlan = auth()->user()->subscription('default') ?? NULL;

        $paymentMethods = NULL;
        $defaultPaymentMethod = NULL;
        if (!is_null($currentPlan)) {
            $paymentMethods       = auth()->user()->paymentMethods();
            $defaultPaymentMethod = auth()->user()->defaultPaymentMethod();
        }

        $payments = Payment::where('user_id', auth()->id())->latest()->get();

        return view('billing.index', compact('monthlyPlans', 'yearlyPlans', 'currentPlan',
            'paymentMethods', 'defaultPaymentMethod', 'payments'));
    }

    public function cancel()
    {
        auth()->user()->subscription('default')->cancel();
        return redirect()->route('billing');
    }

    public function resume()
    {
        auth()->user()->subscription('default')->resume();
        return redirect()->route('billing');
    }

    public function downloadInvoice($paymentId) {
        $payment = Payment::where('user_id', auth()->id())->where('id', $paymentId)->firstOrFail();
        $filename = storage_path('app/invoices/' . $payment->id . '.pdf');
        if (!file_exists($filename)) {
            abort(404);
        }

        return response()->download($filename);
    }

}
