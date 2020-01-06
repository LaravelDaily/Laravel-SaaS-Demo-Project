@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Billing</div>

                <div class="card-body">
                    You are now on Free Plan. Please choose plan to upgrade:
                    <br /><br />
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <h3>Bronze plan</h3>
                            <b>$9.99 / month</b>
                            <hr />
                            <a href="#" class="btn btn-primary">Subscribe to Bronze Plan</a>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3>Bronze plan</h3>
                            <b>$9.99 / month</b>
                            <hr />
                            <a href="#" class="btn btn-primary">Subscribe to Bronze Plan</a>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3>Bronze plan</h3>
                            <b>$9.99 / month</b>
                            <hr />
                            <a href="#" class="btn btn-primary">Subscribe to Bronze Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
