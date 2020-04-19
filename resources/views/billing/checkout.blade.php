@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subscribe to {{ $plan->name }}</div>

                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    <input type="hidden" id="plan-paying-amount" value="{{ number_format($subtotal / 100, 2) }}" />
                    <input type="hidden" id="tax-percent" value="{{ $taxPercent }}" />

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            Name or Company Name:
                            <br />
                            <input type="text" name="company_name" class="form-control" required />
                        </div>
                        <div class="col-md-4">
                            Address line 1:
                            <br />
                            <input type="text" name="address_line_1" class="form-control" required />
                        </div>
                        <div class="col-md-4">
                            Address line 2 (optional):
                            <br />
                            <input type="text" name="address_line_2" class="form-control" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            Country:
                            <br />
                            <select name="country_id" class="form-control">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            City:
                            <br />
                            <input type="text" name="city" class="form-control" required />
                        </div>
                        <div class="col-md-4">
                            Postcode:
                            <br />
                            <input type="text" name="postcode" class="form-control" />
                        </div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-4">
                            Discount code:
                            <br />
                            <input type="text" name="coupon" id="coupon" class="form-control" />
                            <div id="coupon-text" style="font-size:12px"></div>
                        </div>
                        <div class="col-md-8">
                            <br />
                            <input type="button" name="coupon-check" id="coupon-check" value="Apply code" class="btn btn-sm btn-primary" />
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="col-md-6">
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                                @csrf
                                <input type="hidden" name="billing_plan_id" value="{{ $plan->id }}" />
                                <input type="hidden" name="payment-method" id="payment-method" value="" />

                                <input id="card-holder-name" type="text" placeholder="Card holder name" class="form-control">

                                <!-- Stripe Elements Placeholder -->
                                <div id="card-element"></div>

                                <br />

                                Subtotal:
                                <span class="font-weight-bold" id="amount_subtotal">${{ number_format($subtotal / 100, 2) }}</span>
                                <br />
                                Tax ({{ $taxPercent }}%):
                                <span class="font-weight-bold" id="amount_taxes">${{ number_format($taxAmount / 100, 2) }}</span>
                                <br />
                                Total:
                                <span class="font-weight-bold" id="amount_total">${{ number_format($total / 100, 2) }}</span>
                                <hr />

                                <button id="card-button" class="btn btn-primary">
                                    Pay ${{ number_format($total / 100, 2) }}
                                </button>

                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
      $( document ).ready(function() {
        let stripe = Stripe("{{ env('STRIPE_KEY') }}")
        let elements = stripe.elements()
        let style = {
          base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        }

        let card = elements.create('card', {style: style})
        card.mount('#card-element')

        let paymentMethod = null
        $('#checkout-form').on('submit', function (e) {
          $('#card-button').prop('disabled',true);
          if (paymentMethod) {
            return true
          }
          stripe.confirmCardSetup(
            "{{ $intent->client_secret }}",
            {
              payment_method: {
                card: card,
                billing_details: {name: $('#card-holder-name').val()}
              }
            }
          ).then(function (result) {
            if (result.error) {
              console.log(result)
              alert('error')
            } else {
              paymentMethod = result.setupIntent.payment_method
              $('#payment-method').val(paymentMethod)
              $('#checkout-form').submit()
            }
          })
          return false
        })

        $('#coupon-check').on('click', function (e) {
          $('#coupon-text').text('');
          $.get({
            url: "{{ route('coupon') }}?coupon_code=" + $('#coupon').val(),
            contentType: "application/json",
            dataType: 'json'
          }).done(function(result) {
            let plan_paying_amount = $('#plan-paying-amount').val();
            if (result.error_text) {
              $('#coupon-text').text(result.error_text);
              $('#card-button').text('Pay $' + plan_paying_amount);
            } else {
              $('#coupon-text').text(result.name);
              let tax_percent = $('#tax-percent').val();
              let pay_amount = (plan_paying_amount * (1 - result.percent_off / 100)).toFixed(2);
              $('#amount_subtotal').text('$' + pay_amount);
              let tax_amount = (pay_amount * tax_percent / 100).toFixed(2);
              $('#amount_taxes').text('$' + tax_amount);
              pay_amount = (pay_amount + tax_amount).toFixed(2);
              $('#amount_total').text('$' + pay_amount);
              $('#card-button').text('Pay $' + pay_amount);
            }
          })
        })
      });
    </script>
@endsection

@section('styles')
    <style>
        .StripeElement {
            box-sizing: border-box;
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }
        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }
        .StripeElement--invalid {
            border-color: #fa755a;
        }
        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
@endsection
