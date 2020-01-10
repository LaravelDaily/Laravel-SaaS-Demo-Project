@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My Plan</div>

                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif

                    @if (is_null($currentPlan))
                        You are now on Free Plan. Please choose plan to upgrade:
                        <br /><br />
                    @endif
                    <div class="row">
                        @foreach ($plans as $plan)
                            <div class="col-md-4 text-center">
                                <h3>{{ $plan->name }}</h3>
                                <b>${{ number_format($plan->price / 100, 2) }} / month</b>
                                <hr />
                                @if (!is_null($currentPlan) && $plan->stripe_plan_id == $currentPlan->stripe_plan)
                                    Your current plan.
                                    <br />
                                    @if (!$currentPlan->onGracePeriod())
                                        <a href="{{ route('cancel') }}" class="btn btn-danger" onclick="return confirm('Are you sure?')">Cancel plan</a>
                                    @else
                                        Your subscription will end on {{ $currentPlan->ends_at->toDateString() }}
                                        <br /><br />
                                        <a href="{{ route('resume') }}" class="btn btn-primary">Resume subscription</a>
                                    @endif
                                @else
                                    <a href="{{ route('checkout', $plan->id) }}" class="btn btn-primary">Subscribe to {{ $plan->name }}</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if (!is_null($currentPlan))
                <br />
            <div class="card">
                <div class="card-header">Payment Methods</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Expires at</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($paymentMethods as $paymentMethod)
                            <tr>
                                <td>{{ $paymentMethod->card->brand }}</td>
                                <td>{{ $paymentMethod->card->exp_month }} / {{ $paymentMethod->card->exp_year }}</td>
                                <td>
                                    @if ($defaultPaymentMethod->id == $paymentMethod->id)
                                        default
                                    @else
                                        <a href="{{ route('payment-methods.markDefault', $paymentMethod->id) }}">Mark as Default</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <br />
                    <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">Add Payment Method</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
