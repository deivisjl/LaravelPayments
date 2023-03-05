@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make a payment</div>

                <div class="card-body">
                    <form action="{{ route('pay') }}" method="POST" id="paymentForm">
                        @csrf
                        <div class="row">
                            <div class="col-auto">
                                <label for="">How much you want to pay?</label>
                                <input  class="form-control"  value={{ mt_rand(500,100000) / 100}} type="number" name="value" min="5" step="0.01">
                                <small class="form-text text-muted">
                                    Use values with up to two decimal positions, using dots ","
                                </small>
                            </div>
                            <div class="col-auto">
                                <label for="">Currency</label>
                                <select class="custom-select" name="currency" required>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->iso}}">
                                            {{ strtoupper($currency->iso)}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="">Select the desired payment platform:</label>
                                <div class="form-group" id="toggler">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        @foreach ($paymentPlatforms as $paymentPlatform)
                                            <label
                                            class="btn btn-outline-secondary rounded m-2 p-1"
                                            data-target="#{{ $paymentPlatform->name }}Collapse"
                                            data-toggle="collapse">
                                                <input type="radio" name="payment_patform" value="{{ $paymentPlatform->id}}" required>
                                                <img src="img-thumbnail" src="{{ asset($paymentPlatform->image) }}">
                                            </label>
                                        @endforeach
                                    </div>
                                    @foreach ($paymentPlatforms as $paymentPlatform)
                                        <div
                                            id="{{ $paymentPlatform->name }}Collapse"
                                            class="collapse"
                                            data-parent="#toggler">
                                            @includeIf('components.'.strtolower($paymentPlatform->name).'-collapse')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <p class="border-bottom border-primary rounded">
                                    @if(!optional(auth()->user())->hasActiveSubscription())
                                        Would you like a discount every time?
                                        <a href="{{ route('subscribe.show') }}">Suscribe</a>
                                    @else
                                    You get a <span class="font-weight-bold">10%</span> off as part of your subscription (will bea applied in the echeckout)
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="payButton">Pay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
