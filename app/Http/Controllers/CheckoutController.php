<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);

        $currentPlan = auth()->user()->subscription('default')->stripe_plan ?? NULL;
        if (!is_null($currentPlan) && $currentPlan != $plan->stripe_plan_id) {
            auth()->user()->subscription('default')->swap($plan->stripe_plan_id);
            return redirect()->route('billing');
        }

        $intent = auth()->user()->createSetupIntent();
        return view('billing.checkout', compact('plan', 'intent'));
    }

    public function processCheckout(Request $request)
    {
        $plan = Plan::findOrFail($request->input('billing_plan_id'));
        try {
            auth()->user()->newSubscription('default', $plan->stripe_plan_id)->create($request->input('payment-method'));
            return redirect()->route('billing')->withMessage('Subscribed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

    }
}
