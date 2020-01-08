<?php

namespace App\Http\Controllers;

use App\Plan;

class BillingController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $currentPlan = auth()->user()->subscription('default')->stripe_plan ?? NULL;
        return view('billing.index', compact('plans', 'currentPlan'));
    }
}
