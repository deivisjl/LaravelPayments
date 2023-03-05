<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resolvers\PaymentPlatformResolver;

class SubscriptionController extends Controller
{
    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function show(){

    }

    public function store(){

    }

    public function approval(){

    }

    public function cancelled(){

    }
}
