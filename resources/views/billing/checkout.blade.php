@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Subscribe to {{ $plan->name }}</div>

                <div class="card-body">
                    Payment form will be here.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
