<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function pay(Request $request)
    {
        $rules = [
            'value' => ['required','numeric','min:5'],
            'currency' => ['required','exists:currencies,iso'],
            'payment_platform' => ['required','exists:payment_platforms,id']
        ];

        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver
                        ->resolveService($request->get('id'));

        session()->put('paymentPlatformId', $request->payment_platform);

        if($request->user()->hasActiveSubscription())
        {
            $request->value = round($request->value * 0.9,2);
        }

        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        if(session()->has('paymentPlatformId')){
            $paymentPlatform = $this->paymentPlatformResolver
                        ->resolveService(session()->get('paymentPlatformId'));

            return $paymentPlatform->handleApproval();
        }

        return redirect()
            ->route('home')
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
    }

    public function cancelled()
    {
        return redirect()
            ->route('home')
            ->withErrors('You cancelled the payment');
    }
}
