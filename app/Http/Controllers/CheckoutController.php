<?php

namespace App\Http\Controllers;

use App\Plan;

class CheckoutController extends Controller
{
    public function checkout($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);
        return view('billing.checkout', compact('plan'));
    }
}
